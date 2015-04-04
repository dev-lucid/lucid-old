<?php
global $less;
include(__DIR__.'/../etc/dependencies.php');

$less = array( 
	'files'=>array(
        __DIR__.'/../www/media/less/customizations.less' => '/',
        __DIR__.'/../../../lib/fuelux/3.6.4/dist/css/fuelux.min.css' => '/',

    ),
	'cache_dir' => __DIR__.'/../var/cache/',
	'import_dirs'=>[
		__DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/less/'=>'/',
	],
	'compress'=>true 
);
?>