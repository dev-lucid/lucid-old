<?php

interface interface__lucid_security
{
    public function set_defaults();
    public function is_logged_in();
    public function require_logged_in();
    public function is_role($required_role);
    public function require_role($required_role);
    public function require_any_role($required_roles);
    public function has_permissions($required_permissions);
    public function has_any_permission($required_permissions);
    public function require_permissions($required_permissions);
    public function require_any_permission($required_permissions);
    public function generate_password($length);
}

class lucid_security implements interface__lucid_security
{
    public $session_user_id_property  = 'user_id';
    public $default_user_id           = 0;

    public $session_role_name_property  = 'role_name';
    public $default_role_name           = 'guest';

    public $session_permission_property = 'permissions';
    public $default_permissions         = [];
    
    public function __construct()
    {
        $this->set_defaults();
    }
    
    public function set_defaults()
    {
        if( is_null(lucid::session()->{$this->session_user_id_property}))
        {
            lucid::session()->{$this->session_user_id_property} = $this->default_user_id;
        }
        if( is_null(lucid::session()->{$this->session_role_name_property}))
        {
            lucid::session()->{$this->session_role_name_property} = $this->default_role_name;
        }
        if( is_null(lucid::session()->{$this->session_permission_property}) or !is_array(lucid::session()->{$this->session_permission_property}))
        {
            lucid::session()->{$this->session_permission_property} = $this->default_permissions;
        }   
    }

    public function get_role()
    {
        return lucid::session()->{$this->session_role_name_property};
    }

    public function get_permissions()
    {
        return lucid::session()->{$this->session_permission_property};
    }

    
    public function is_logged_in()
    {
        $user_role = $this->get_role();
        return ($user_role !== $this->default_role_name);
    }

    public function require_logged_in()
    {
        $user_role = $this->get_role();
        if ($user_role === $this->default_role_name)
        {
            throw new Exception('lucid/lib/php/lucid_security: Unauthenticated user attempted to access functionality that required them to be logged in.');   
        }
    }
    
    public function is_role($required_role)
    {
        $user_role = $this->get_role();
        return ($user_role === $required_role);
    }
    
    public function require_role($required_role)
    {
        $user_role = $this->get_role();
        if ($user_role !== $required_role)
        {
            throw new Exception('lucid/lib/php/lucid_security: User was not logged in as correct role. The user\'s role was '.((is_null($user_role))?'unknown':$user_role).', the required role was '.$required_role.'.');
        }
        return true;
    }
    
    public function require_any_role($required_roles)
    {
        $user_role      = $this->get_role();
        if (!is_array($required_roles))
        {
            $required_roles = [$required_roles];
        }
        foreach($required_roles as $required_role)
        {
            if ($required_role === $user_role)
            {
                return true;
            }
        }
        throw new Exception('lucid/lib/php/lucid_security: User was not logged in as any of the correct roles. The user\'s role was '.((is_null($user_role))?'unknown':$user_role).', the required role was '.$required_role.'.');
    }

    public function has_permissions($required_permissions)
    {
        $user_permissions     = $this->get_permissions();
        if (!is_array($required_permissions))
        {
            $required_permissions = [$required_permissions];
        }
        $match_count = 0;
        
        foreach($required_permissions as $required_permission)
        {
            if (in_array($required_permission, $user_permissions))
            {
                $match_count++;
            }
        }
        
        return ($match_count  == count($required_permission));
    }
    
    public function has_any_permission($required_permissions)
    {
        $user_permissions     = $this->get_permissions();
        if (!is_array($required_permissions))
        {
            $required_permissions = [$required_permissions];
        }
        foreach($required_permissions as $required_permission)
        {
            if (in_array($required_permission, $user_permissions))
            {
                return true;
            }
        }
        return false;
    }
    
    public function require_permissions($required_permissions)
    {
        $user_permissions     = $this->get_permissions();
        if (!is_array($required_permissions))
        {
            $required_permissions = [$required_permissions];
        }
        foreach($required_permissions as $required_permission)
        {
            if (!in_array($required_permission, $user_permissions))
            {
                throw new Exception('lucid/lib/php/lucid_security: User did not have all of the necessary permissions to perform an action. The user\'s permissions were: '.str_replace("\n","\t",print_r($user_permissions,true)).', the required permissions were: '.str_replace("\n","\t",print_r($required_permissions,true)) );
            }
        }
        return true;
    }
    
    public function require_any_permission($required_permissions)
    {
        $user_permissions     = $this->get_permissions();
        if (!is_array($required_permissions))
        {
            $required_permissions = [$required_permissions];
        }
        $match_count = 0;
        
        
        foreach($required_permissions as $required_permission)
        {
            if (in_array($required_permission, $user_permissions))
            {
                $match_count++;
            }
        }
        
        if($match_count === 0)
        {
            throw new Exception('lucid/lib/php/lucid_security: User did not have any of the necessary permissions to perform an action. The user\'s permissions were: '.str_replace("\n","\t",print_r($user_permissions,true)).', the required permissions were: '.str_replace("\n","\t",print_r($required_permissions,true)) );
        }
        return true;
    }

    public function generate_password($length = 10)
    {
        $character_set = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789-+=!@#$%";
        $password      = '';
        $set_length    = strlen($character_set) - 1;
        for ($i = 0; $i < $length; $i++)
        {
            $password .= $character_set[rand(0, $set_length)];
        }
        return $password;
    }
}
