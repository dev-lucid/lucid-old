<?php

class lucid_controller_authentication extends lucid_controller
{
    public $auth_token_name     = '_lucid_auth_token';
    public $token_expire_time   = 3600000;
    public $token_separator     = '|';
    public $token_length        = 16;
    public $register_key_length = 24;

    public function register()
    {
        lucid::log_request();
        
        lucid_form::send_errors( $this->register_ruleset()->validate() );


        $org = lucid::model('organizations')->create();
        $org->name       = lucid::request('organization_name');
        $org->created_on = time();
        $org->role_id    = 2;
        $org->save();
        $org_id = $org->id();

        $register_key = lucid::security()->generate_password($register_key_length);

        $user = lucid::model('users')->create();
        $user->org_id       = $org_id;
        $user->email        = lucid::request('email');
        $user->password     = password_hash(lucid::request('password'),PASSWORD_DEFAULT);
        $user->first_name   = lucid::request('first_name');
        $user->last_name    = lucid::request('last_name');
        $user->is_enabled   = SQL_TRUE;
        $user->created_on   = time();
        $user->register_key = $register_key;
        $user->save();

        $address = lucid::model('addresses')->create();
        $address->org_id      = $org_id;
        $address->street_1    = lucid::request('street_1');
        $address->street_2    = lucid::request('street_2');
        $address->city        = lucid::request('city');
        $address->postal_code = lucid::request('postal_code');
        $address->region_id   = lucid::request('region_id');
        $address->country_id  = lucid::request('country_id');
        $address->save();

        $enable_link = lucid::index_url('account/enable',['register_key'=>$register_key]);

        # email the temporary password to the user
        $mail = lucid::mailer();
        $mail->addAddress($user->email, $user->first_name.' '.$user->last_name); 
        $mail->isHTML(true);



        $mail->Subject = 'Thank you for registering';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b><br /><a href="'.$enable_link.'">Click here.</a> to enable your account.';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'."\nClick this link to enable your account: ".$enable_link;

        if(!$mail->send())
        {
            lucid::log('account/password_reset: Mailer error during password reset for user '.$user->user_id.' ('.$user->email.'): ' . $mail->ErrorInfo);
        }
        else
        {
            lucid::log('account/password_reset: Password for user '.$user->user_id.' has been reset and emailed to '.$user->email);
        }

        
        lucid::redirect(lucid::index_url('view/register_confirmation'));
    }

    public function register_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('organization_name', 'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-organizations-name'),4,255),['min'=>4,'max'=>255])
            ,new lucid_rule('email',            'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-users-email'),5,255),['min'=>5,'max'=>255])
            ,new lucid_rule('email',            'email',  lucid::i18n('validation-email',lucid::i18n('field-users-email')))
            ,new lucid_rule('password',         'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-users-password'),8,255),['min'=>8,'max'=>255])
            ,new lucid_rule('confirm_password', 'match',  lucid::i18n('validation-match',lucid::i18n('field-users-password'),lucid::i18n('field-users-password-confirm')),['match'=>'password'])
            ,new lucid_rule('first_name',       'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-users-first_name'),2,255),['min'=>2,'max'=>255])
            ,new lucid_rule('last_name',        'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-users-last_name'),2,255),['min'=>2,'max'=>255])
            ,new lucid_rule('street_1',         'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-addresses-street_1'),2,255),['min'=>2,'max'=>255])
            ,new lucid_rule('city',             'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-addresses-city'),2,255),['min'=>2,'max'=>255])
            ,new lucid_rule('postal_code',      'length', lucid::i18n('validation-length-min-max',lucid::i18n('field-addresses-postal_code'),3,12),['min'=>3,'max'=>12])
        );
    }

    public function check_force_password_change()
    {
        lucid::log('checking for force password change: '.(lucid::session()->force_password_change == SQL_TRUE));
        if ((lucid::session()->force_password_change == SQL_TRUE) and lucid::request('action',null) !== 'view/password_change')
        {
            $this->force_password_change();
            lucid::deinit();
        }
    }

    public function login_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('email','length',lucid::i18n('validation-length-min-max',lucid::i18n('field-users-email'),5,255),['min'=>5,'max'=>255]),
            #new lucid_rule('email','email',lucid::i18n('field-users-email-validate-email'),['min'=>5,'max'=>20]),
            new lucid_rule('password','length',lucid::i18n('validation-length-min-max',lucid::i18n('field-users-password'),5,255),['min'=>5,'max'=>20])
        );
    }

    public function login()
    {
        lucid::log('authentication->login called');
        
        $user = lucid::model('users')->where('email',lucid::request('email'))->where('register_key','')->where('is_enabled',SQL_TRUE)->find_one();
        if($user === false)
        {
            lucid_form::send_errors('Incorrect password 1');
            return;
        }

        if (!password_verify(lucid::request('password'),$user->password))
        {
            lucid_form::send_errors('Incorrect password 2');
            return;
        }
    	
        $user->last_login = time();
        $user->save();
        
        $this->set_user_session_properties($user);

        lucid::log('authentication->login: '.lucid::session()->role_name.' successfully authenticated, redirecting to dashboard');
    	
        if(lucid::request('remember') == 1)
        {
            lucid::log('authentication->login: generating auth token');
            $new_token = $this->generate_auto_auth_token($this->token_length);
            
            $token = lucid::model('user_auth_tokens')->create();
            $token->user_id    = $user->user_id;
            $token->token      = $new_token;
            $token->created_on = time();
            $token->save();
            setcookie($this->auth_token_name,$user->user_id . $this->token_separator . $token->token, time() + $this->token_expire_time);
        }        

        lucid::redirect(lucid::index_url('view/dashboard'));

        return;
    }
    
    public function set_user_session_properties($user)
    {
        $org  = $user->organization()->find_one();
        $role = $org->role()->find_one();

        lucid::session()->user_id    = $user->user_id;
        lucid::session()->org_id     = $user->org_id;
        lucid::session()->org_name   = $org->name;
        lucid::session()->role_id    = $role->role_id;
        lucid::session()->role_name  = $role->name;
        lucid::session()->email      = $user->email;
        lucid::session()->first_name = $user->first_name;
        lucid::session()->last_name  = $user->last_name;
        lucid::session()->force_password_change  = $user->force_password_change;
    }

    public function logout()
    {
        #lucid::log('authentication->logout: pre-logout, user id/session/nav_state='.lucid::session()->user_id.'/'.lucid::session()->session_id().'/'.print_r(lucid::request('_nav_state',[]),true));
        lucid::session()->destroy();
        lucid::security()->set_defaults();
        lucid::redirect(lucid::index_url('view/login'));
        lucid::set_nav('nav1','nav1');
        lucid::set_nav('nav2','nav2');
        setcookie($this->auth_token_name);
        $_REQUEST['_nav_state'] = [];
        #lucid::log('authentication->logout: post-logout, user id/session/nav_state='.lucid::session()->user_id.'/'.lucid::session()->session_id().'/'.print_r(lucid::request('_nav_state',[]),true));
    }

    protected function generate_auto_auth_token($length = 16)
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public function check_auth_token()
    {
        if (isset($_COOKIE[$this->auth_token_name]) and $_COOKIE[$this->auth_token_name] !== '')
        {
            lucid::log('authentication->check_auth_token: found an appropriately named cookie.');
            
            # break the stored token into its two parts: the user_id, and the actual token.
            $token_parts = explode($this->token_separator, $_COOKIE[$this->auth_token_name]);
            
            if(count($token_parts) !== 2)
            {
                throw new Exception('authentication->check_auth_token: the auth token found in user\'s cookies was in unexpected format. The expected format is {user_id}|{token}. For example, 1|8b82100f6d337acf6de71c9c9202bf57. The format found was:'.$_COOKIE[$this->auth_token_name] );
            }
            list($user_id, $token) = $token_parts;
            
            # this function is called on every load, but we only actually need to load the data for it once. After the user/org data 
            # is set in the session, we can just move on
            if($user_id !== lucid::session()->user_id)
            {
                lucid::log('authentication->check_auth_token: found token for user '.$user_id.', token='.$token);
                $token = lucid::model('user_auth_tokens')->where('user_id',$user_id)->where('token',$token)->find_one();
                
                if ($token === false)
                {
                    lucid::log('authentication->check_auth_token: user cookie contained an auth token, but it could not be found in the database.');
                    setcookie($this->auth_token_name);     
                }
                else
                {
                    lucid::log('authentication->check_auth_token: user cookie contained a valid auth token for user '.$user_id.'.');
                    $user = $token->user()->find_one();
                    $user->last_login = time();
                    $user->save();
        
                    $this->set_user_session_properties($user);
                }
            }
        }
        else
        {
            lucid::log('authentication->check_auth_token: no user cookie found.');
        }
    }
}

?>