<?php

class lucid_controller
{
    function __construct($name,$path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public function parameter($index, $default_value=null)
    {
        global $lucid;
        return (isset($lucid->config['view_params'][$this->_params_index][$index]))?$lucid->config['view_params'][$this->_params_index][$index]:$default_value;
    }


    function __call($method,$parameters)
    {
        global $lucid;
        $this->_params_index = count($lucid->config['view_params']);
        $this->_return_index = count($lucid->config['view_return']);
        
        $lucid->config['view_params'][] = $parameters;
        $lucid->config['view_return'][] = null;
        
        $view_path = $this->path.'/views/'.$method.'.php';
        if (file_exists($view_path))
        {
            include($view_path);
        }
        else
        {
            throw new Exception('lucid/lib/php/lucid_controller.php: Could not find view file '.$view_path);
        }

        # remove the view params that were sent
        array_pop($lucid->config['view_params']);
        
        return array_pop($lucid->config['view_return']);
    }
}
