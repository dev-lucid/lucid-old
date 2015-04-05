<?php

class lucid_controller_account extends lucid_controller
{
    public function org_save()
    {
        $org = Model::factory('organizations')->find_one($_REQUEST['org_id']);
        $org->permission_check('update');
        $org->name = $_REQUEST['name'];
        $org->save();
        lucid::redirect('account/admin');
    }

    public function user_save()
    {
        $user = Model::factory('users')->find_one($_REQUEST['user_id']);
        $user->permission_check('update');
        $user->email      = $_REQUEST['email'];
        $user->first_name = $_REQUEST['first_name'];
        $user->last_name  = $_REQUEST['last_name'];
        $user->save();
        lucid::redirect('account/admin');
    }
}
?>