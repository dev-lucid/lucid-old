<?php

class lucid_controller_account extends lucid_controller
{
    public function org_save_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('name','length',lucid::i18n('field-organizations-name-validate-length'),['min'=>5,'max'=>20])
        );
    }

    public function org_save()
    {
        $org = lucid::model('organizations')->find_one(lucid::request('org_id'));
        $org->permission_check('update');
        $org->name = lucid::request('name');
        $org->save();
        lucid::message('info',lucid::i18n('message-changes_saved'));
        lucid::redirect(lucid::index_url('view/organizations'));
    }

    public function user_save_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('email','length',lucid::i18n('validation-length-min-max',lucid::i18n('field-users-email'),5,255),['min'=>5,'max'=>255]),
            new lucid_rule('email','email',lucid::i18n('validation-email',lucid::i18n('field-users-email'),5,20),['min'=>5,'max'=>20]),
            new lucid_rule('first_name','length',lucid::i18n('validation-length-min-max',lucid::i18n('field-users-first_name'),5,50),['min'=>5,'max'=>50]),
            new lucid_rule('last_name','length',lucid::i18n('validation-length-min-max',lucid::i18n('field-users-last_name'),5,20),['min'=>5,'max'=>20])
        );
    }
    
    public function change_password_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('password','length',lucid::i18n('field-users-password-validate-length'),['min'=>8,'max'=>20])
        );
    }

    public function user_save()
    {
        $user_id = lucid::request('user_id');
        if ($user_id !== lucid::session()->user_id)
        {
            throw new Exception('User '.lucid::session()->user_id.' attempted to change the password of user '.$user_id.'. Passwords can only be changed by the same user. A password may be updated to a randomly generated value using authentication/reset_password.');
        }
        $user = lucid::model('users')->find_one($user_id);
        $user->permission_check('update');
        $user->email      = lucid::request('email');
        $user->first_name = lucid::request('first_name');
        $user->last_name  = lucid::request('last_name');
        $user->save();
        lucid::message('info',lucid::i18n('message-changes_saved'));
        lucid::redirect(lucid::index_url('view/users'));
    }

    public function password_change()
    {
        $user_id = lucid::request('user_id');
        if ($user_id !== lucid::session()->user_id)
        {
            lucid::security()->require_role('admin');
        }
        $user = lucid::model('users')->find_one($user_id);
        $user->password = password_hash(lucid::request('password'),PASSWORD_DEFAULT);
        $user->force_password_change = SQL_FALSE;
        $user->save();

        if ($user->user_id === lucid::session()->user_id )
        {
            lucid::session()->force_password_change = SQL_FALSE;
        }

        lucid::message('info',lucid::i18n('message-password_changed'));

        if(lucid::request('forced','no') === 'yes')
        {
            lucid::reload();
        }
    }

    public function password_reset()
    {
        lucid::security()->require_role('admin');
        $user_id = lucid::request('user_id');
        
        $user = lucid::model('users')->find_one($user_id);
        $new_password = lucid::security()->generate_password();
        $user->password = password_hash($new_password,PASSWORD_DEFAULT);
        $user->force_password_change = SQL_TRUE;
        $user->save();

        # email the temporary password to the user
        $mail = lucid::mailer();
        $mail->addAddress($user->email, $user->first_name.' '.$user->last_name); 
        $mail->isHTML(true);

        $mail->Subject = 'Password changed';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b><br />New password is: '.$new_password;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'."\nNew password is: ".$new_password;

        if(!$mail->send())
        {
            lucid::log('account/password_reset: Mailer error during password reset for user '.$user->user_id.' ('.$user->email.'): ' . $mail->ErrorInfo);
        }
        else
        {
            lucid::log('account/password_reset: Password for user '.$user->user_id.' has been reset and emailed to '.$user->email);
        }
        lucid::message('info',lucid::i18n('message-password_reset'));
    }

    public function register_rules()
    {
        $rules = new lucid_ruleset();
        $rules->add(
            new lucid_rule(),
            new lucid_rule()
        );
        return $rules;
    }

    public function register()
    {
        $this->register_rules()->validate();
    }

    public function enable()
    {
        lucid::log('checking register key:');
        lucid::log_request();
        $user = lucid::model('users')->where('register_key',lucid::request('register_key'))->find_one();
        if ($user === false)
        {
            lucid::log('no user found ');
            $user->register_key = '';
            $user->save();

        }
        else
        {
            lucid::log('enabling user');
            lucid::controller('authentication')->set_user_session_properties($user);
            lucid::message('info','Account Enabled');
            lucid::view('dashboard');
        }
    }
}
?>