<?php
class lucid_model_users extends lucid_model
{
    public static $_table = 'users';
	public static $_id_column = 'user_id';
    
    public function organization()
    {
        return $this->has_one('organizations','org_id','org_id');
    }
    
    public function tokens()
    {
        return $this->has_many('user_auth_tokens','user_id','user_id');
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
                    lucid::log('User '.lucid::session()->user_id.' attempted to perform action '.$action.' on user '.$this->user_id.', which is not in the same organization.','security');
                    lucid::redirect('content/not_authorized');
                    lucid::deinit();
                }
                break;
        }
        return $this;
    }
}
?>