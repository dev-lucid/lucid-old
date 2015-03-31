<?php
class lucid_model_users extends Model
{
    public static $_table = 'users';
	public static $_id_column = 'user_id';
    
    public function organization()
    {
        return $this->has_one('organizations','org_id','org_id')->find_one();
    }
}
?>