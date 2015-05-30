<?php
$org_id = lucid::request('org_id');
if($org_id !== lucid::session()->org_id)
{
    lucid::security()->require_role('admin');
}
$org = lucid::model('organizations')->find_one($org_id);

$org_panel = bs::panel(['heading'=>'Organization Info','modifier'=>'primary']);
$org_panel->add( bs::input_text(['name'=>'name','value'=>$org->name]));
$org_panel->add( bs::input_hidden(['name'=>'org_id','value'=>$org->org_id]));
$org_panel->footer = bs::button(['type'=>'submit','label'=>lucid::i18n('action-save')])->pull_right();
$org_form = bs::form(['name'=>'orgEditForm','action'=>'account/org_save'])->add($org_panel);
lucid::controller('account')->org_save_ruleset()->send_javascript('orgEditForm');

$container = bs::container_fluid()->add([
    bs::h1(lucid::i18n('message-editing',$org->name))->sizes([12,12,12,12]),
    $org_form->sizes([12,12,5,4]),
    bs::div()->add(lucid::view('addresses_grid',$org->org_id))->sizes([12,12,7,8])
]);

lucid::replace('#body',$container->render());

lucid::set_title(lucid::i18n('message-editing',$org->name));
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
