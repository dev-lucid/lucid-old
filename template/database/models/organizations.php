<?php
class lucid_model_organizations extends lucid_model
{
	public static $_table = 'organizations';
    public static $_id_column = 'org_id';
    
    public function users()
    {
        return $this->has_many('users','org_id','org_id');
    }

    public function role()
    {
        return $this->has_one('roles','role_id','role_id');
    }

    public function permission_check($action='select')
    {
        switch($action)
        {
            case 'select':
            case 'update':
            case 'insert':
            case 'delete':
                if($this->org_id != lucid::session()->org_id and lucid::session()->role_name != 'admin')
                {
                    lucid::log('User '.lucid::session()->user_id.' attempted to perform action '.$action.' on organization '.$this->org_id.', which is not their organization.','security');
                    lucid::redirect('content/not_authorized');
                    lucid::deinit();
                }
                break;
        }
        return $this;
    }
}
?>