<?php

class lucid_session implements ArrayAccess
{
    function __construct()
    {
        session_start();
    }

    function __get($name)
    {
        return $_SESSION[$name];
    }

    function __set($name,$value)
    {
        $_SESSION[$name] = $value;
    }

    function __isset($name)
    {
        return isset($_SESSION[$name]);
    }

    function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    public function offsetExists ( $offset )
    {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet ( $offset )
    {
        return $_SESSION[$offset];
    }

    public function offsetSet ( $offset , $value)
    {
        $_SESSION[$offset] = $value;
    }

    public function offsetUnset ( $offset)
    {
        unset($_SESSION[$offset]);

    }
}

?>