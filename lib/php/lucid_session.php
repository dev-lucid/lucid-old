<?php

interface interface__lucid_session extends ArrayAccess
{
    public function session_id();
    public function destroy($create_new);
    public function __get($offset);
    public function __set($offset,$value);
    public function __isset($offset);
    public function __unset($offset);
}

class lucid_session implements interface__lucid_session
{
    function __construct()
    {
        session_start();
    }

    public function session_id()
    {
        return session_id();
    }

    public function destroy($create_new = true)
    {
        session_destroy();
        if ($create_new === true)
        {
            session_start();
        }
    }

    public function &__get($offset)
    {
        if (!isset($_SESSION[$offset]))
        {
            $_SESSION[$offset] = null;
        }
        return $_SESSION[$offset];
    }

    public function __set($offset,$value)
    {
        $_SESSION[$offset] = $value;
    }

    public function __isset($offset)
    {
        return isset($_SESSION[$offset]);
    }

    public function __unset($offset)
    {
        unset($_SESSION[$offset]);
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
