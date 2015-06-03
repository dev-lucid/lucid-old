<?php
$id = lucid::request('{id_col}',0);
if ($id == 0)
{
    $obj = lucid::model('{table}')->create();
    $title = lucid::i18n('message-creating','{table}');
}
else
{
    $obj = lucid::model('{table}')->find_one($id);
    $title = lucid::i18n('message-editing',$obj->name);
}

$form = bs::form(['name'=>'{table}_edit_form','action'=>'{table}/save'])->add(
    bs::panel(['heading'=>$title,'modifier'=>'primary']
    )->add(bs::input_text(  ['name'=>'{edit_col}',  'value'=>$obj->{edit_col}, 'label'=>lucid::i18n('field-{table}-{edit_col}')]  )
    )->add(bs::input_hidden(['name'=>'{id_col}','value'=>$obj->{id_col}])
    )->footer(bs::button_group_cancel_save())
);
lucid::controller('{table}')->save_ruleset()->send_javascript('{table}_edit_form');

lucid::replace('#body',$form);

lucid::set_title($title);
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
