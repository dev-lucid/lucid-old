<?php
include(__DIR__.'/dependencies.php');
require_once __DIR__.'/../../../lib/idiorm/'.$dependencies['lib/idiorm']['branch'].'/idiorm.php';
require_once __DIR__.'/../../../lib/paris/'.$dependencies['lib/paris']['branch'].'/paris.php';
ORM::configure('sqlite:'.__DIR__.'/../db/development.sqlite');
ORM::configure('logging', true);
Model::$auto_prefix_models = 'lucid_model_';
?>