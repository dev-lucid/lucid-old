<?php 
$app_name  = $argv[1];
$app_dir   = $argv[2];
$view_path = $app_dir.'/db/views/';

# include necessary configuration files
include($app_dir.'/etc/db.php');
include($app_dir.'/etc/autoload.php');


$view_files = array();
$files = scandir($view_path);
foreach($files as $file)
{
    if($file !== '.' and $file !== '..')
    {

        $name = $view_path.'/'.$file;
        $file_info = pathinfo($name);
        if(is_file($name) and $file_info['extension'] == 'sql')
        {
            $view_files[] = $file;
        }
        
    }
}

if(count($view_files) == 0)
{
    exit("No views found.\n");
}

foreach($view_files as $file)
{
    ORM::configure('error_mode', PDO::ERRMODE_EXCEPTION);
    ORM::get_db()->beginTransaction();
    
    $sql = file_get_contents($view_path.$file);
    
    $view_name = str_replace('.sql','',$file);
    ORM::raw_execute('DROP VIEW IF EXISTS '.$view_name);


    try
    {
        fwrite(STDOUT, "-----------------------------------------\n");
        fwrite(STDOUT, "Applying ".$file."\n");

        ORM::raw_execute($sql);
        ORM::get_db()->commit();
        fwrite(STDOUT, "Success.\n");
    }
    catch(Exception $e)
    {
        ORM::get_db()->rollBack();
        fwrite(STDOUT, "Failure.\n");
        exit("An error occured in view $file: ".$e->getMessage()."\n");
    }
}
fwrite(STDOUT, "-----------------------------------------\n");
exit("All views have been successfully rebuilt.");
?>