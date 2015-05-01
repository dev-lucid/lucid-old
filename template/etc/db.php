<?php
include(__DIR__.'/dependencies.php');

if(!class_exists('lucid'))
{
    require_once __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid.php'; 
}
require_once __DIR__.'/../../../lib/idiorm/'.$dependencies['lib/idiorm']['branch'].'/idiorm.php';
require_once __DIR__.'/../../../lib/paris/'.$dependencies['lib/paris']['branch'].'/paris.php';
require_once __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid_model.php';
ORM::configure('sqlite:'.__DIR__.'/../db/development.sqlite');
ORM::configure('logging', true);
Model::$auto_prefix_models = 'lucid_model_';
ORM::configure('logger', function($log_string, $query_time) {
    lucid::log($log_string . ' in ' . $query_time,'sql');
});
?>