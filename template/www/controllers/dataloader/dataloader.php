<?php

class lucid_controller_dataloader extends lucid_controller 
{
    function get_option_list()
    {
        $table        = lucid::request('table');
        $change_field = lucid::request('change_field');
        $filter_value = lucid::request('filter_value');

        switch($table)
        {
            case 'regions':
                $regions = Model::factory('regions')->where('country_id',$filter_value)->where_null('parent')->order_by_asc('name')->find_many();
                $html = '';
                foreach($regions as $region)
                {
                    $html .= '<option value="'.$region->region_id.'">'.$region->name.'</option>';
                }
                if ($html != '')
                {
                    lucid::special('new_options','<option></option>'.$html);
                }
                break;
            default:
                lucid::log('User '.lucid::session()->user_id.' attempted to call dataloader/get_option_list on table '.$table.', which is not setup to be queryable. If this table should be queryable via the dataloader controller, then add a switch case to '.__FILE__.'.','security');
                lucid::redirect('content/not_authorized');
                lucid::deinit();
                break;
        }
    }
}

?>