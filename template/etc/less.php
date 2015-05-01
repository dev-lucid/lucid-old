<?php
global $less;
include(__DIR__.'/../etc/dependencies.php');

$less = array( 
	'files'=>array(
        __DIR__.'/../www/media/less/customizations.less' => '/',

    ),
	'cache_dir' => __DIR__.'/../var/cache/',
	'import_dirs'=>[
        __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/less/'=>'/',
		__DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/less/'=>'/',
	],
	'compress'=>true 
);
?>