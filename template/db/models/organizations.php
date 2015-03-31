<?php
class lucid_model_organizations extends lucid_model
{
	public static $_table = 'organizations';
    public static $_id_column = 'org_id';
    
    public function users()
    {
        return $this->has_many('users','org_id','org_id');
    }
}
?>