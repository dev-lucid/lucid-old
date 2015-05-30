<?php

/*
This acts as a default logger if you don't want to use Monolog. It simply passed through any messages to error_log.
*/
class lucid_logger 
{
    function __call($method,$parameters)
    {
        error_log($method.': '.$parameters[0]);
    }
}