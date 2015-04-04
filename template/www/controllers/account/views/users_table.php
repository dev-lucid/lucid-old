<?php

$repeater = new lucid_html_repeater('My test repeater','mytestid','account/users_table',ORM::for_table('vw_users_details'),[
    new lucid_html_repeater_column('Role','role_name',true),
    new lucid_html_repeater_column('Organization','organization_name',true,null,function($column,$data){
        return '<a href="#!account/organization|org_id|'.$data->org_id.'">'.$data->organization_name.'</a>';
    }),
    new lucid_html_repeater_column('E-mail','email',true,'20%',function($column,$data){
        return '<a href="mailTo:'.$data->email.'">'.$data->email.'</a>';
    }),
    new lucid_html_repeater_column('Name','first_name',true,null,function($column,$data){
        return '<a href="#!account/user|user_id|'.$data->user_id.'">'.$data->first_name.' '.$data->last_name.'</a>';
    })
]);

$repeater->handle_return($this);
?>