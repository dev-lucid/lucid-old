<?php

class lucid_controller_authentication extends lucid_controller
{
    public function login()
    {
        global $lucid;

        lucid::log('authentication/login called');
        lucid::log_request();
        $user = Model::factory('users')->where('email',$_REQUEST['email'])->find_one();
        if($user === false)
        {
            lucid_form::send_errors(['Incorrect password 1']);
            return;
        }

        if (!password_verify($_REQUEST['password'],$user->password))
        {
            lucid_form::send_errors(['Incorrect password 2']);
            return;
        }

        $org  = $user->organization()->find_one();
        $role = $org->role()->find_one();

        $lucid->session->user_id    = $user->user_id;
        $lucid->session->org_id     = $user->org_id;
        $lucid->session->org_name   = $org->name;
        $lucid->session->role_id    = $role->role_id;
        $lucid->session->role_name  = $role->name;
        $lucid->session->email      = $user->email;
        $lucid->session->first_name = $user->first_name;
        $lucid->session->last_name  = $user->last_name;

        lucid::log($lucid->session->role_name.' successfully authenticated, redirecting to dashboard');
        if($lucid->session->role_name === 'admin')
        {
            lucid::redirect('account/admin');
        }
        else
        {
            lucid::redirect('account/dashboard');
        }
        return;
    }
}

?>