<?php
class lucid_model_organizations extends Model
{
	public static $_table = 'organizations';
    public static $_id_column = 'org_id';
    
    public function users()
    {
        return $this->has_many('users','org_id','org_id');
    }
}
?>