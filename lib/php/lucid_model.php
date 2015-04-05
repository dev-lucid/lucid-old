<?php

class lucid_model extends Model
{
    public function permission_check($action='select')
    {
        lucid::log(get_class($this).'->permission_check() called with action '.$action.'. This model did not implement a ->permission_check() method, so the version its super class was caled instead. Consider adding one to centralize your permission checking in your model layer.');
        return $this;
    }   
}

?>