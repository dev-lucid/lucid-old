<?php
lucid::security()->require_logged_in();
lucid::security()->require_role('admin');

$grid = new lucid_html_grid(
    lucid::i18n('navigation-organizations'),
    'organization-grid',
    'view/organizations_grid',
    ORM::for_table('vw_organizations_details'),
    ['testing'=>1],
    [
        new lucid_html_grid_column('Name','name',true,'70%',function($column,$format,$data){
            return '<a href="#!view/edit_organization|org_id|'.$data->org_id.'">'.$data->name.'</a>';
        }),
        new lucid_html_grid_column('Number of Users','nbr_of_users',true,'30%',function($column,$format,$data){
            return '<a href="#!view/edit_organization|org_id|'.$data->org_id.'">'.$data->nbr_of_users.'</a>';
        })
    ]
);
$grid->add_filter(new lucid_html_grid_filter_search(['name']));

$grid->handle_return();
?>