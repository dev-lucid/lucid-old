<?php
lucid::security()->require_logged_in();
$user_id = lucid::request('user_id');
if($user_id !== lucid::session()->user_id)
{
    lucid::security()->require_role('admin');
}
$user = lucid::model('users')->find_one($user_id);
$user->permission_check('select');

$user_panel = bs::panel(['heading'=>lucid::i18n('message-editing',$user->first_name.' '.$user->last_name),'modifier'=>'primary']);
$user_panel->add(bs::div()->add_class('form-errors'));
$user_panel->add(bs::input_text(   ['name'=>'email',     'label'=>lucid::i18n('field-users-email'),      'value'=>$user->email    ]));
$user_panel->add(bs::input_text(   ['name'=>'first_name','label'=>lucid::i18n('field-users-first_name'), 'value'=>$user->first_name]));
$user_panel->add(bs::input_text(   ['name'=>'last_name', 'label'=>lucid::i18n('field-users-last_name'),  'value'=>$user->last_name]));
$user_panel->add(bs::input_hidden( ['name'=>'user_id', 'value'=>$user->user_id, ]));
$user_panel->footer = bs::button(['type'=>'submit','label'=>lucid::i18n('action-save'),'modifier'=>'primary',])->pull_right();
$user_form  = bs::form(['name'=>'userEditForm','action'=>'account/user_save'])->add($user_panel);
lucid::controller('account')->user_save_ruleset()->send_javascript('userEditForm');

if ($user->user_id === lucid::session()->user_id)
{
    $password_panel = bs::panel(['heading'=>'Change Password','modifier'=>'primary']);
    $password_panel->add( bs::input_password(['name'=>'password',         'label'=>lucid::i18n('field-users-password')]));
    $password_panel->add( bs::input_password(['name'=>'confirm_password', 'label'=>lucid::i18n('field-users-password-confirm')]));
    $password_panel->add( bs::input_hidden(  ['name'=>'user_id', 'value'=>$user->user_id, ]));
    $password_panel->footer = bs::button(['type'=>'submit','label'=>lucid::i18n('action-change-password'),'modifier'=>'primary',])->pull_right();
    $password_form = bs::form(['name'=>'changePasswordForm','action'=>'account/password_change'])->add($password_panel);
    lucid::controller('account')->change_password_ruleset()->send_javascript('changePasswordForm');
}
else
{
    $password_panel = bs::panel(['heading'=>'Reset Password','modifier'=>'primary']);
    $password_panel->add(     bs::input_hidden(  ['name'=>'user_id', 'value'=>$user->user_id, ]));
    $password_panel->footer = bs::button(['type'=>'submit','label'=>lucid::i18n('action-reset-password'),'modifier'=>'primary',])->pull_right();
    $password_form = bs::form(['name'=>'resetPasswordForm','action'=>'account/password_reset'])->add($password_panel);
}

$container = bs::container_fluid();
$container->add($user_form->sizes([12,12,6,6]));
$container->add($password_form->sizes([12,12,6,6]));

lucid::replace('#body',$container->render());

lucid::set_title(lucid::i18n('message-editing',$user->first_name.' '.$user->last_name));
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
