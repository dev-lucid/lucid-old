<?php
ORM::configure('sqlite:'.__DIR__.'/../database/development.sqlite');
ORM::configure('logging', true);
Model::$auto_prefix_models = 'lucid_model_';

# sqlite uses 1/0 for true/false. 
# Redefine these as php's real true/false when you use other databases.
define("SQL_TRUE" ,1);
define("SQL_FALSE",0);

# configure idiorm to log via lucid::log
ORM::configure('logger', function($log_string, $query_time)
{
    lucid::log($log_string . ' in ' . $query_time);
});

# autoloader for models
spl_autoload_register(function($name){
    if (strpos($name,'lucid_model_') === 0){
        $final_name = str_replace('lucid_model_','',$name);
        $path = __DIR__.'/../database/models/'.$final_name.'.php';
        if(file_exists($path))
        {
            include($path);
        }
    }
});
