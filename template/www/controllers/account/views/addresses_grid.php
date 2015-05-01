<?php
$org_id = lucid::request('org_id',$this->parameter(0)); 

$grid = new lucid_html_grid(
    lucid::i18n('navigation-addresses'),
    'address-grid',
    'account/addresses_grid',
    Model::factory('addresses')->where('org_id',$org_id),
    [ 'org_id'=> $org_id ],
    [
        new lucid_html_grid_column(lucid::i18n('field-addresses-name'),'name',true,'30%'),
        new lucid_html_grid_column(lucid::i18n('field-addresses-street_1'),'street_1',true,'45%',function($column,$format,$data){
            $html = $data->render();
            return '<a onclick="alert(\'testing\');">'.$html.'</a>';
        }),
        new lucid_html_grid_column(lucid::i18n('field-addresses-phone_number_1'),'phone_number_1',true,'25%',function($column,$format,$data){
            $html  = $data->phone_number_1.'<br />';
            $html .= $data->phone_number_2;
            return $html;
        })
    ]
);

$grid->add_filter(new lucid_html_grid_filter_search(['name','street_1','street_2','city','postal_code','phone_number_1','phone_number_2']));

$grid->handle_return($this);
?>