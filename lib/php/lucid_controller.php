<?php

class lucid_controller
{
    function __construct($name,$path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public function get_parameters()
    {
        global $lucid;
        return array_pop($lucid->config['view_params']);
    }

    public function send_return($value=null)
    {
        global $lucid;
        $lucid->config['view_return'][] = $value;
    }

    function __call($method,$parameters)
    {
        global $lucid;
        $pre_params_count = count($lucid->config['view_params']);
        $pre_return_count = count($lucid->config['view_return']);

        # push the latest parameters onto the view_params array
        # this allows a way to pass parameters onto views
        $lucid->config['view_params'][] = $parameters[0];

        $view_path = $this->path.'/views/'.$method.'.php';
        if (file_exists($view_path))
        {
            include($view_path);
        }
        else
        {
            throw new Exception('Could not find view file '.$view_path);
        }

        # if the view didn't use its parameters (which would pop it off), then pop them off for the view
        if (count($lucid->config['view_params']) > $pre_params_count)
        {
            array_pop($lucid->config['view_params']);
        }
        # if the view 
        if (count($lucid->config['view_return']) > $pre_return_count)
        {
            return array_pop($lucid->config['view_return']);
        }
        else
        {
            return true;
        }
    }
}

?>