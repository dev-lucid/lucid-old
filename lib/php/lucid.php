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
        include(__DIR__.'/lucid_model.php');
        include(__DIR__.'/lucid_logger.php');   # only used as a fallback logger, outputs to error_log
        include(__DIR__.'/lucid_security.php'); # need to load this even if the class is not used so that we have the interface definition
        include(__DIR__.'/lucid_session.php');  # need to load this even if the class is not used so that we have the interface definition

        $this->actions = [
            'pre-request'=>[],
            'request'=>[],
            'post-request'=>[],
        ];

        $this->config   = [
            'default_position'=>'body',
            'paths'=>[],
            'view_params' =>[],
            'view_return'=>null,
            'language_tag' => 'en-US',
            'dictionaries'=>[],
            'dictionary_messages' => [],
            'nav_state' => [],
            'mailer'=> [
                'included'=>false,
                'class'=>null,
                'includes'=> [],
                'properties' => [],
            ],

            'security'=>null,
            'session'=>null,
            'logger'=>null,

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
        $lucid->config['paths']['app'] = $www_dir.'/../';
        $lucid->config['paths']['url-dir'] = str_replace('app.php','',$_SERVER['PHP_SELF']);
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

        # 'view' is a magic keyword that causes the request to load a view instead of a controller named 'view'.
        if($controller === 'view'){
            lucid::view($method);
        }else{
            $controller = lucid::controller($controller);
            return $controller->$method($parameters);
        }
    }

    public static function controller($controller)
    {
        global $lucid;
        $controller_path  = $lucid->config['paths']['www'].'controllers/';
        $controller_file  = $lucid->config['paths']['www'].'controllers/'.$controller.'.php';
        $controller_class = 'lucid_controller_'.$controller;
        
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

        return $controller;
    }

    public static function view($view)
    {
        global $lucid;
        $whiteSpace = '\s';
        $pattern    = '/[^a-zA-Z0-9-\_'. $whiteSpace . ']/u';
        $file_name  = preg_replace($pattern, '', (string) $view) . '.php';
        $full_path  = $lucid->config['paths']['www'].'views/'.$file_name;
        if(!file_exists($full_path)){
            throw new Exception('Could not locate file for view: '.$full_path);
        }
        include($full_path);
        if (!is_null($lucid->config['view_return'])){
            $return = $lucid->config['view_return'];
            $lucid->config['view_return'] = null;
            return $return;
        }
    }

    public static function model($name)
    {
        global $lucid;
        $class_name = 'lucid_model_'.$name;
        $file_path  = $lucid->config['paths']['app'].'/database/models/'.$name.'.php';
        if(!class_exists($class_name))
        {
            if (file_exists($file_path))
            {
                include($file_path);
            }
        }
        if(!class_exists($class_name))
        {
            throw new Exception('lucid/lib/php/lucid.php: Could not find model for '.$name.', looked for '.$file_path);
        }
        return Model::factory($name);
    }

    public static function view_return($value=null)
    {
        global $lucid;
        $lucid->config['view_return'] = $value;
    }

    public static function logger()
    {
        global $lucid;
        # use the default logger if one isn't configured
        if (!isset($lucid->config['logger']))
        {   
            $lucid->config['logger'] = new lucid_logger();
        }
        return $lucid->config['logger'];
    }

    public static function log($string,$type='info')
    {
        global $lucid;
        if(is_array($string) or is_object($string))
        {
            $string = str_replace("\n","\t",print_r($string,true));
        }
        lucid::logger()->$type($string);
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
        if (!isset($lucid->config['session']) or is_null($lucid->config['session']) or !is_object($lucid->config['session']))
        {
            throw new Exception('lucid session handler has not been set to an object. You should instantiate an object that implements interface__lucid_session (defined in '.__DIR__.'/lucid_session.php) and save it to $lucid->configp[\'session\'].');
        }
        if (!($lucid->config['session'] instanceof interface__lucid_session))
        {
            throw new Exception('lucid session handler is set, but the object does not implement interface__lucid_session. This interface is defined in '.__DIR__.'/lucid_session.php');
        }
        return $lucid->config['session'];
    }
 
    public static function security()
    {
        global $lucid;
        if (!isset($lucid->config['security']) or is_null($lucid->config['security']) or !is_object($lucid->config['security']))
        {
            throw new Exception('lucid security handler has not been set to an object. You should instantiate an object that implements interface__lucid_security (defined in '.__DIR__.'/lucid_security.php) and save it to $lucid->configp[\'security\']');
        }
        if (!($lucid->config['security'] instanceof interface__lucid_security))
        {
            throw new Exception('lucid security handler is set, but the object does not implement interface__lucid_security. This interface is defined in '.__DIR__.'/lucid_security.php');
        }
        return $lucid->config['security'];
    }

    public static function mailer()
    {
        global $lucid;

        if (is_null($lucid->config['mailer']['class'])){
            throw new Exeption('lucid/lib/php/lucid.php: tried to call mailer, but no class name for mailer was set. Try phpmailer?');
        }

        $class_name = $lucid->config['mailer']['class'];

        if ($lucid->config['mailer']['included'] === false){
            foreach($lucid->config['mailer']['includes'] as $include)
            {
                include($include);
            }
            $lucid->config['mailer']['included'] = true;
        }


        $mail_class = new $class_name();

        foreach($lucid->config['mailer']['properties'] as $key=>$value)
        {
            $mail_class->$key = $value;
        }

        return $mail_class;
    }
       
    public static function request($field = null, $default_value = null)
    {
        if(is_null($field)){
            return $_REQUEST;
        }
        return (isset($_REQUEST[$field]))?$_REQUEST[$field]:$default_value;
    }

    public static function app_url($action,$parameters=[])
    {
        $url = (isset($_SERVER['HTTPS']))?'https://':'http://';
        $url .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $url .= '?action='.$action;
        foreach($parameters as $key=>$value)
        {
            $url .= '&'.urlencode($key).'='.urlencode($value);
        }
        return $url;
    }

    public static function index_url($action,$parameters=[])
    {
        global $lucid;
        $url = (isset($_SERVER['HTTPS']))?'https://':'http://';
        $url .= $_SERVER['HTTP_HOST'] . $lucid->config['paths']['url-dir'].'index.php';
        $url .= '#!'.$action;

        foreach($parameters as $key=>$value)
        {
            $url .= '|'.urlencode($key).'|'.urlencode($value);
        }
        lucid::log('final url: '.$url);
        return $url;
    }

    public static function reload()
    {
        lucid::javascript('location.reload();');
    }

    public static function redirect($new_url)
    {
        lucid::javascript('lucid.redirect(\''.$new_url.'\');');
    }
}   

# register an autoloader for the html functions 
spl_autoload_register(function($name){
    if (strpos($name,'lucid_html_') === 0){
        $final_name = str_replace('lucid_html_','',$name);
        $path = __DIR__.'/html/'.$final_name.'.php';
        if(file_exists($path))
        {
            include($path);
        }
    }
});
