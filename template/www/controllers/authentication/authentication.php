<?php

class lucid_controller_authentication extends lucid_controller
{
    public $auth_token_name   = '_lucid_auth_token';
    public $token_expire_time = 3600000;
    public $token_separator   = '|';
    public $token_length      = 16;
    
    public function login()
    {
        lucid::log('authentication->login called');
        
        $user = Model::factory('users')->where('email',lucid::request('email'))->find_one();
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
            
            $token = Model::factory('user_auth_tokens')->create();
            $token->user_id    = $user->user_id;
            $token->token      = $new_token;
            $token->created_on = time();
            $token->save();
            setcookie($this->auth_token_name,$user->user_id . $this->token_separator . $token->token, time() + $this->token_expire_time);
        }        

        
        lucid::redirect('account/dashboard');
        
        return;
    }
    
    protected function set_user_session_properties($user)
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
    }

    public function logout()
    {
        #lucid::log('authentication->logout: pre-logout, user id/session/nav_state='.lucid::session()->user_id.'/'.lucid::session()->session_id().'/'.print_r(lucid::request('_nav_state',[]),true));
        lucid::session()->destroy();
        lucid::security()->set_defaults();
        lucid::redirect('content/login');
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
                $token = Model::factory('user_auth_tokens')->where('user_id',$user_id)->where('token',$token)->find_one();
                
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