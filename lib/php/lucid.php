<?php

global $lucid;

class lucid
{
    function __construct()
    {
        # load all secondary libraries
        include(__DIR__.'/lucid_controller.php');
        include(__DIR__.'/lucid_ruleset.php');
        include(__DIR__.'/lucid_rule.php');
        include(__DIR__.'/lucid_form.php');

        $this->actions = [
            'pre-request'=>[],
            'request'=>[],
            'post-request'=>[],
        ];

        $this->config   = [
            'default_position'=>'body',
            'paths'=>[],
            'view_params' =>[],
            'view_returns'=>[],
        ];
    }

    public static function clear_response()
    {
        global $lucid;

        $lucid->response = [
            'title'=>'',
            'description'=>'',
            'keywords'=>'',
            'prepend'=>'',
            'append'=>'',
            'replace'=>'',
            'special'=>[],
            'javascript'=>'',
            'success'=>true,
        ]; 
    }

    public static function javascript($string_to_send)
    {
        global $lucid;
        $lucid->response['javascript'] .= $string_to_send;
    }

    public static function special($key,$to_send)
    {
        global $lucid;
        $lucid->response['special'][$key] = $to_send;
    }

    public static function init($www_dir)
    {
        global $lucid;
        $lucid = new lucid();
        $lucid->config['paths']['www'] = $www_dir.'/';
        $lucid->config['paths']['app'] = $www_dir.'../';
        lucid::clear_response();
        ob_start();
        lucid::process_action_list($lucid->actions['pre-request']);
    }

    public static function get_buffer()
    {
        $contents = ob_get_clean();
        ob_start();
        return $contents;
    }

    public static function replace($position = null, $content = null)
    {
        return lucid::set_content('replace',$position,$content);
    }

    public static function append($position = null, $content = null)
    {
        return lucid::set_content('append',$position,$content);
    }

    public static function prepend($position = null, $content = null)
    {
        return lucid::set_content('prepend',$position,$content);
    }

    public static function set_title($new_title)
    {
        global $lucid;
        $lucid->response['title'] = $new_title;
        return true;
    }

    public static function set_description($new_description)
    {
        global $lucid;
        $lucid->response['description'] = $new_description;
        return true;
    }

    public static function set_keywords($new_keywords)
    {
        global $lucid;
        $lucid->response['keywords'] = $new_keywords;
        return true;
    }

    public static function set_content($mode,$position=null, $content = null)
    {
        global $lucid; 

        if (is_null($position))
        {
            $position = $lucid->config['default_position'];
        }

        if (is_null($content))
        {
            $content = lucid::get_buffer();
        }

        if (!isset($lucid->response[$mode][$position]))
        {
            $lucid->response[$mode][$position]= '';
        }

        $lucid->response[$mode][$position] .= ''.$content;
        
        return true;
    }

    public static function deinit()
    {
        global $lucid;
        lucid::process_action_list($lucid->actions['post-request']);
        ob_get_clean();
        header('Content-Type: application/json');
        echo json_encode($lucid->response);
        exit();
    }

    public static function process_request()
    {
        global $lucid;

        if(!isset($_REQUEST['action']))
        {
            throw new Exception('No action in request');
        }

        $req_action = split('/',$_REQUEST['action']);

        if(count($req_action) !== 2)
        {
            throw new Exception('Malformed exception. Should be controller_name/controller_method, got '.$_REQUEST['action']);
        }

        $lucid->actions['request'][] = [$req_action[0],$req_action[1],$_REQUEST];
        
        lucid::process_action_list($lucid->actions['request']);
    }

    public static function process_action_list($action_list)
    {
        global $lucid;
        
        for($i = 0;$i<count($action_list); $i++)
        {
            if(!is_array($action_list[$i]))
            {
                list($controller, $method) = $action_list[$i];
                $parameters = [];
            }
            else
            {
                $controller = array_shift($action_list[$i]);
                $method     = array_shift($action_list[$i]);
                $parameters = (count($action_list[$i]) > 0)?array_shift($action_list[$i]):[];
            }
            lucid::process_action($controller, $method, $parameters);
        }
    }

    public static function process_action($controller, $method, $parameters=[])
    {
        global $lucid;
        $controller_path  = $lucid->config['paths']['www'].'controllers/'.$controller;
        $controller_file  = $lucid->config['paths']['www'].'controllers/'.$controller.'/'.$controller.'.php';
        $controller_class = 'lucid_controller_'.$controller;
        $view_file        = $lucid->config['paths']['www'].'controllers/'.$controller.'/views/'.$method.'.php';

        # make sure the controller folder eixsts
        if(!file_exists($controller_path))
        {
            throw new Exception('Could not find folder for controller, looked for '.$controller_path);   
        }

        # create the controller class
        if(file_exists($controller_file))
        {
            # if a root controller class exists, use it. 
            include($controller_file);
            if (!class_exists($controller_class))
            {
                throw new Exception('Included controller file, but after load could not find controller class. Should be called '.$controller_class);;       
            }
            $controller = new $controller_class($controller,$controller_path);
        }
        else
        {
            # Plan B, create a generic one. This can still be used for loading views
            $controller = new lucid_controller($controller,$controller_path);
        }

        # call the appropriate method of the controller
        return $controller->$method($parameters);
    }

    public static function log($string,$type='debug')
    {
        global $lucid;
        if (isset($lucid->logger))
        {
            $lucid->logger->write($string,$type);
        }
        else
        {
            error_log($type.': '.$string);
        }
    }

    public static function log_request()
    {
        lucid::log(str_replace("\n","\t",print_r($_REQUEST,true)));
    }

    public static function log_response()
    {
        global $lucid;
        lucid::log(str_replace("\n","\t",print_r($lucid->response,true)));
    }

    public static function redirect($new_url)
    {
        lucid::javascript('lucid.redirect(\''.$new_url.'\');');
    }
}   

?>