<?php

class lucid_session implements ArrayAccess
{
    function __construct()
    {
        session_start();
    }

    function session_id()
    {
        return session_id();
    }

    function destroy($create_new = true)
    {
        session_destroy();
        if ($create_new === true)
        {
            session_start();
        }
    }

    function __get($offset)
    {
        return (isset($_SESSION[$offset]))?$_SESSION[$offset]:null;
    }

    function __set($offset,$value)
    {
        $_SESSION[$offset] = $value;
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
        return (isset($_SESSION[$offset]))?$_SESSION[$offset]:null;
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