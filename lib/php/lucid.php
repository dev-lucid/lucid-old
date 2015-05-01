<?php

global $lucid;

class lucid
{
    function __construct()
    {
        # load all secondary libraries
        include(__DIR__.'/lucid_error.php');
        include(__DIR__.'/lucid_controller.php');
        include(__DIR__.'/lucid_i18n.php');
        include(__DIR__.'/lucid_ruleset.php');
        include(__DIR__.'/lucid_rule.php');
        include(__DIR__.'/lucid_form.php');
        include(__DIR__.'/lucid_format.php');

        $this->actions = [
            'pre-request'=>[],
            'request'=>[],
            'post-request'=>[],
        ];

        $this->config   = [
            'default_position'=>'body',
            'paths'=>[],
            'view_params' =>[],
            'view_return'=>[],
            'language_tag' => 'en-US',
            'dictionaries'=>[__DIR__.'/../dicts/'],
            'dictionary_messages' => [],
            'nav_state' => [],
            'formats'=> [
                'dates'=> [
                    // the key can be passed into lucid_format::date as the second parameter. The value will be passed to php's date function as the 1st parameter
                    'ISO8601' => 'c',
                    'RFC2822' => 'r',
                    'US-date' => 'F j, Y',
                    'US-datetime' => 'F j, Y H:i',
                
                ]
                
            ]
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

    public static function message($type='info', $title='', $body='', $closeable=true, $auto_close = true, $auto_close_delay=3000)
    {
        global $lucid;

        # generate an id. Needed for the auto_close flag.
        $id = 'lucid-message-'.time();

        $html = '<div class="alert alert-'.$type.' alert-dismissible fade in" role="alert" id="'.$id.'">';
        if ($closeable === true)
        {
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        }
        $html .= '<strong>'.$title.'</strong>';
        $html .= $body;
        $html .= '</div>';

        lucid::replace('#messages',$html);

        if ($auto_close === true)
        {
            lucid::javascript('window.setTimeout(function() { $(\'#'.$id.'\').alert(\'close\'); }, '.$auto_close_delay.');');
        }
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
        if(isset($_REQUEST['_form_identifier']))
        {
            echo('<html><body><script language="JavaScript" type="text/javascript">parent.lucid.handleResponse('.json_encode($lucid->response).');</script></body></html>');
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode($lucid->response);        
        }
        exit();
    }

    public static function process_request()
    {
        global $lucid;
        lucid::process_action_list($lucid->actions['pre-request']);

        if(!isset($_REQUEST['action']))
        {
            throw new Exception('No action in request');
        }

        $req_action = explode('/',$_REQUEST['action']);

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

    public static function add_action_to_list($list,$controller,$method,$parameters=array())
    {
        global $lucid;
        $lucid->actions[$list][] = [$controller,$method,$parameters];
    }

    public static function process_action($controller, $method, $parameters=[])
    {
        global $lucid;
        $controller_path  = $lucid->config['paths']['www'].'controllers/'.$controller;
        $controller_file  = $lucid->config['paths']['www'].'controllers/'.$controller.'/'.$controller.'.php';
        $controller_class = 'lucid_controller_'.$controller;
        $view_file        = $lucid->config['paths']['www'].'controllers/'.$controller.'/views/'.$method.'.php';

        if (!class_exists($controller_class))
        {
            # make sure the controller folder eixsts
            if(!file_exists($controller_path))
            {
                throw new Exception('lucid/lib/php/lucid.php: Could not find folder for controller, looked for '.$controller_path);   
            }

             # create the controller class
            if(file_exists($controller_file))
            {
                include($controller_file);
                if (!class_exists($controller_class))
                {
                    throw new Exception('lucid/lib/php/lucid.php: Included controller file '.$controller_file.', but after load could not find controller class. Should be called '.$controller_class);;       
                }
            }
        }

        # at this point, the code has tried to load the controller file if it exists. 
        # if the class does exist now, instantiate it. If not, create a generic controller
        if (class_exists($controller_class))
        {
            $controller = new $controller_class($controller,$controller_path);
        }
        else
        {
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

    public static function i18n($phrase,$keys=array())
    {
        return call_user_func_array('lucid_i18n::translate', func_get_args());
    }

    public static function set_nav($nav_area, $nav_view)
    {
        global $lucid;
        $lucid->config['nav_state'][$nav_area] = $nav_view;
    }

    public static function session()
    {
        global $lucid;
        return $lucid->session;
    }
 
    public static function security()
    {
        global $lucid;
        return $lucid->security;
    }
       
    public static function request($field = null, $default_value = null)
    {
        if(is_null($field))
        {
            return $_REQUEST;
        }
        return (isset($_REQUEST[$field]))?$_REQUEST[$field]:$default_value;
    }

    public static function redirect($new_url)
    {
        lucid::javascript('lucid.redirect(\''.$new_url.'\');');
    }
}   

?>