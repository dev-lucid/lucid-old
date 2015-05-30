<?php

class lucid_model_user_auth_tokens extends lucid_model
{
    public static $_table = 'user_auth_tokens';
    public static $_id_column = 'token_id';
    
    public function user()
    {
        return $this->has_one('users','user_id','user_id');
    }
}

?>