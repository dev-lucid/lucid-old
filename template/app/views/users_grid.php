<?php
lucid::security()->require_logged_in();
lucid::security()->require_role('admin');

$grid = new lucid_html_grid(
    lucid::i18n('navigation-users'),
    'user-grid',
    'view/users_grid',
    ORM::for_table('vw_users_details'),
    ['testing'=>1],
    [
        new lucid_html_grid_column('Role','role_name',true,'10%',function($column,$format,$data){
            return ucwords($data->role_name);
        }),
        new lucid_html_grid_column('Organization','organization_name',true,'20%',function($column,$format,$data){
            return '<a href="#!view/edit_organization|org_id|'.$data->org_id.'">'.$data->organization_name.'</a>';
        }),
        new lucid_html_grid_column('E-mail','email',true,'20%',function($column,$format,$data){
            return '<a href="mailTo:'.$data->email.'">'.$data->email.'</a>';
        }),
        new lucid_html_grid_column('Name','first_name',true,'30%',function($column,$format,$data){
            return '<a href="#!view/edit_user|user_id|'.$data->user_id.'">'.$data->first_name.' '.$data->last_name.'</a>';
        }),
        new lucid_html_grid_column('Dates','last_login',true,'20%',function($column,$format,$data){
            $html = 'Last Login: '.(($data['last_login'] == '')?'Never':lucid_format::date($data['last_login'],'US-date')).'<br />'.'Created On: '.lucid_format::date($data['created_on'],'US-date');
            return $html;  
        
        })
    ]
);
$grid->add_filter(new lucid_html_grid_filter_search(['first_name','last_name','email','organization_name']));
$grid->add_filter(new lucid_html_grid_filter_select('role_id',[[1,'Admin'],[2,'User']],'Role: All','Role: '));

$grid->handle_return();
