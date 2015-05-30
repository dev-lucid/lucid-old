<?php
global $less;

$less = array( 
	'files'=>array(
        __DIR__.'/../app/media/less/customizations.less' => '/',

    ),
	'cache_dir' => __DIR__.'/../cache/',
	'import_dirs'=>[
        __DIR__.'/../vendor/twbs/bootstrap/less/'=>'/',
		__DIR__.'/../vendor/Dev-Lucid/lucid/lib/less/'=>'/',
	],
	'compress'=>true 
);
