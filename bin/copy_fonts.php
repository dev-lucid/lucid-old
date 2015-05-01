<?php 
$app_name  = $argv[1];
$app_dir   = $argv[2];
$view_path = $app_dir.'/db/views/';

# include necessary configuration files
include($app_dir.'/etc/db.php');
include($app_dir.'/etc/autoload.php');
include($app_dir.'/etc/dependencies.php');

$font_path = $app_dir.'/../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/fonts/';
$files = scandir($font_path);
foreach($files as $file)
{
    if($file !== '.' and $file !== '..')
    {
        copy($font_path.$file , $app_dir.'/www/media/less/fonts/'.$file);
    }
}

#print_r($dependencies);
?>