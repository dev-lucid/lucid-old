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
        $org = Model::factory('organizations')->find_one(lucid::request('org_id'));
        $org->permission_check('update');
        $org->name = lucid::request('name');
        $org->save();
        lucid::message('info',lucid::i18n('message-changes_saved'));
        lucid::redirect('account/organizations');
    }

    public function user_save_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('email','length',lucid::i18n('field-users-email-validate-length'),['min'=>5,'max'=>20]),
            new lucid_rule('email','email',lucid::i18n('field-users-email-validate-email'),['min'=>5,'max'=>20]),
            new lucid_rule('first_name','length',lucid::i18n('field-users-first_name-validate-length'),['min'=>5,'max'=>20]),
            new lucid_rule('last_name','length',lucid::i18n('field-users-last_name-validate-length'),['min'=>5,'max'=>20])
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
        $user = Model::factory('users')->find_one(lucid::request('user_id'));
        $user->permission_check('update');
        $user->email      = lucid::request('email');
        $user->first_name = lucid::request('first_name');
        $user->last_name  = lucid::request('last_name');
        $user->save();
        lucid::message('info',lucid::i18n('message-changes_saved'));
        lucid::redirect('account/users');
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
}
?>