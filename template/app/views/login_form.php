<?php

$login_panel = bs::panel(['heading'=>lucid::i18n('navigation-login')]);
$login_panel->add(bs::div()->add_class('form-errors'));
$login_panel->add(bs::input_text(    ['name'=>'email',    'label'=>lucid::i18n('field-users-email'),    'prefix'=>bs::icon('user')]));
$login_panel->add(bs::input_text(    ['name'=>'password', 'label'=>lucid::i18n('field-users-password'), 'prefix'=>bs::icon('lock')]));
$login_panel->add(bs::input_checkbox(['name'=>'remember', 'label'=>'Remember me', 'value'=>1]));
$login_panel->add(bs::button(['type'=>'submit',           'label'=>'Login', 'block'=>true, 'modifier'=>'primary']));

$login_form  = bs::form(['name'=>'loginForm','action'=>'authentication/login','label_style'=>'placeholder'])->add($login_panel);

lucid::controller('authentication')->login_ruleset()->send_javascript('loginForm');
lucid::view_return($login_form);
