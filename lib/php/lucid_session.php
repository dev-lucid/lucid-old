<?php

class lucid_session implements ArrayAccess
{
    function __construct()
    {
        session_start();
    }

    function __get($name)
    {
        if($name == 'session_id')
        {
            return session_id();
        }
        return $_SESSION[$name];
    }

    function __set($name,$value)
    {
        if($name != 'session_id')
        {
            $_SESSION[$name] = $value;
        }
    }

    function __isset($name)
    {
        if($name == 'session_id')
        {
            return true;
        }
        return isset($_SESSION[$name]);
    }

    function __unset($name)
    {
        if($name != 'session_id')
        {
            unset($_SESSION[$name]);
        }
    }

    public function offsetExists ( $offset )
    {
        if($offset == 'session_id')
        {
            return true;
        }
        return isset($_SESSION[$offset]);
    }

    public function offsetGet ( $offset )
    {
        if($offset == 'session_id')
        {
            return session_id();
        }
        return $_SESSION[$offset];
    }

    public function offsetSet ( $offset , $value)
    {
        if($offset != 'session_id')
        {
            $_SESSION[$offset] = $value;
        }
    }

    public function offsetUnset ( $offset)
    {
        if($offset != 'session_id')
        {
            unset($_SESSION[$offset]);
        }
    }
}

?>