<?php

$countries = lucid::model('countries')->where_not_equal('country_id','US')->find_many();
$regions   = lucid::model('regions')->where('country_id','US')->find_many();

$register_panel = bs::panel(['heading'=>lucid::i18n('navigation-register')]);
$register_panel->add(bs::div()->add_class('form-errors'));
$register_panel->add(bs::input_text(['name'=>'organization_name', 'label'=>lucid::i18n('field-organizations-name')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'email',             'label'=>lucid::i18n('field-users-email')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'password',          'label'=>lucid::i18n('field-users-password')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'password-confirm',  'label'=>lucid::i18n('field-users-password-confirm')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'first_name',        'label'=>lucid::i18n('field-users-first_name')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'last_name',         'label'=>lucid::i18n('field-users-last_name')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'street_1',          'label'=>lucid::i18n('field-addresses-street_1')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'street_2',          'label'=>lucid::i18n('field-addresses-street_2')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'city',              'label'=>lucid::i18n('field-addresses-city')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_select(['name'=>'region_id',       'label'=>lucid::i18n('field-addresses-region_id'),'data'=>$regions,'value_field'=>'region_id','label_field'=>'name'])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_text(['name'=>'postal_code',       'label'=>lucid::i18n('field-addresses-postal_code')])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::input_select(['name'=>'country_id',      'label'=>lucid::i18n('field-addresses-country_id'),'data'=>$countries,'value_field'=>'country_id','label_field'=>'name'])->div_add_class('col-xs-12 col-sm-12 col-md-6 col-lg-6'));
$register_panel->add(bs::button(['type'=>'submit','label'=>'Register','modifier'=>'primary'])->pull_right());

lucid::controller('authentication')->register_ruleset()->send_javascript('registerForm');

$form = bs::form(['name'=>'registerForm','action'=>'authentication/login'])->add($register_panel);

lucid::view_return($form);