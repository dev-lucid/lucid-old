<?php
lucid::security()->require_logged_in();

$grid = new lucid_html_grid(
    lucid::i18n('navigation-{table}'),
    '{table}-grid',
    'view/{table}_grid',
    lucid::model('{table}'),
    [],
    [
        new lucid_html_grid_column(lucid::i18n('field-{table}-{edit_col}'),'{edit_col}',true,'75%',function($column,$format,$data){
            return bs::anchor(['href'=>'#!view/edit_{table}|{id_col}|'.$data->{id_col},'text'=>$data->{edit_col}]);
        }),
        new lucid_html_grid_column('','',false,'25%',function($column,$format,$data){
            return bs::button_group_edit_delete([
                'edit_url'=>'#!view/edit_{table}|{id_col}|'.$data->{id_col},
                'delete_url'=>'#!{table}/delete|{id_col}|'.$data->{id_col},
            ]);
        })
    ]
);
$grid->html_template_fields['buttons'] = bs::button([
    'modifier'=>'primary',
    'label'=>bs::icon('plus').' Add New',
    'href'=>'#!view/edit_{table}|{id_col}|0',
]);
$grid->add_filter(new lucid_html_grid_filter_search(['{edit_col}']));

$grid->handle_return();
