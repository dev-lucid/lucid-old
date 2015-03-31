<?php
include(__DIR__.'/dependencies.php');
global $autoload_paths;
$autoload_paths = [
    'lucid_html_'=>__DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/html/',
    'lucid_model_'=>__DIR__.'/../db/models/',
    '*'=>null,
];

function __autoload($class_name)
{
    global $autoload_paths;
    $found = false;
    $default_path = null;
    foreach($autoload_paths as $prefix=>$path)
    {

        if($prefix === '*')
        {
            $default_path = $path;
        }
        else
        {
            if(substr($class_name, 0, strlen($prefix)) === $prefix)
            {
                $file_name = $path.str_replace($prefix,'',$class_name).'.php';
                if(file_exists($file_name))
                {
                    include($file_name);
                    $found = true;
                }
            }            
        }
    }

    # if we didn't find the class yet, but a default path was specified, look there.
    if($found === false && !is_null($default_path))
    {
        $file_name = $default_path.$class_name.'.php';
        if(file_exists($file_name))
        {
            include($file_name);
        }
    }
}

?>