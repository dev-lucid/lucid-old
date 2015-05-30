<?php

class lucid_model_addresses extends lucid_model
{
    public static $_table = 'addresses';
    public static $_id_column = 'address_id';

    public function region()
    {
        return $this->has_one('regions','region_id','region_id');
    }

    function render()
    {
        $html = $this->street_1.'<br />';
        if(!is_null($this->street_2) and $this->street_2 != '')
        {
            $html .= $this->street_2.'<br />';
        }
        $html .= $this->city.', '.$this->region()->find_one()->name;
        $html .= ' '.$this->postal_code;
        return $html;
    }
}

?>