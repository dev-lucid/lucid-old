<?php

class lucid_model_countries extends lucid_model
{
    public static $_table = 'countries';
    public static $_id_column = 'country_id';

    public function regions()
    {
        return $this->has_many('regions','country_id','country_id')->order_by_asc('name');
    }
}

?>