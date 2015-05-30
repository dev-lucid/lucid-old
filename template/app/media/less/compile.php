<?php

include(__DIR__.'/../../../vendor/autoload.php');
include(__DIR__.'/../../../config/less.php');

header('Content-type: text/css', true); 
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');


$css_file_name = Less_Cache::Get( $less['files'], $less );
$compiled = file_get_contents($less['cache_dir'].$css_file_name );

copy($less['cache_dir'].$css_file_name,__DIR__.'/production.css');
echo($compiled);
