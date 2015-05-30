<?php

class lucid_model_regions extends lucid_model
{
    public static $_table = 'regions';
    public static $_id_column = 'region_id';

    public function country()
    {
        return $this->has_one('countries','country_id','country_id');
    }
}

?>