<?php

include(__DIR__.'/../../../etc/dependencies.php');

header('Content-type: text/css', true); 
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

global $less;
include(__DIR__.'/../../../../../lib/less.php/'.$dependencies['lib/bootstrap']['branch'].'/lessc.inc.php');
include(__DIR__.'/../../../etc/less.php');

$css_file_name = Less_Cache::Get( $less['files'], $less );
$compiled = file_get_contents($less['cache_dir'].$css_file_name );

echo($compiled);
?>
