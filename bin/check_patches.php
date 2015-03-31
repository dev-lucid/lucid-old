<?php 
$app_name   = $argv[1];
$app_dir    = $argv[2];
$model_path = $app_dir.'/db/models/';

global $patch_path;
$patch_path = $app_dir.'/db/patches';

# include necessary configuration files
include($app_dir.'/etc/db.php');
include($app_dir.'/etc/autoload.php');

function make_patch_identifier($path, $file)
{
    $path = str_replace('/','___',$path);
    if($path !== '')
    {
        $path .= '___';
    }
    $identifier = $path . str_replace('.sql','',$file);
    return $identifier;
}

function check_directory_for_patches($relative_path)
{
    global $patch_path;
    $patches = [];
    $files = scandir($patch_path.$relative_path);
    foreach($files as $file)
    {
        if($file !== '.' and $file !== '..')
        {
            $name = $patch_path.$relative_path.'/'.$file;
            $file_info = pathinfo($name);
            if(is_file($name) and $file_info['extension'] == 'sql')
            {
                $patches[make_patch_identifier($relative_path,$file)] = $name;
            }
            else if(is_dir($name))
            {

                $patches = array_merge($patches,check_directory_for_patches($relative_path.'/'.$file));
            }
        }
    }
    return $patches;
}

if($argv[3] == '--report-patches')
{
    fwrite(STDOUT, "Checking patches...\n");
}

$applied_patches = [];
$patches = ORM::for_table('patches')->find_many();
foreach($patches as $patch)
{
    $applied_patches[$patch['identifier']] = true;
    if($argv[3] === '--report-patches')
    {
        #fwrite(STDOUT, 'Applied patches: '.print_r($applied_patches,true)."\n");
    }
}


$patches = check_directory_for_patches('');
if($argv[3] == '--report-patches')
{
    fwrite(STDOUT, "Searching patch folder for all patches\n");
    #print_r($patches);
}


if($argv[3] == '--report-patches')
{
    fwrite(STDOUT, "Excluding already applied patches...\n");
}
$new_patches = array();
foreach($patches as $identifier=>$path)
{
    if ($applied_patches[$identifier] === true)
    {
        # do not apply this one
    }
    else
    {
        $new_patches[$identifier] = $path;
    }
}
if($argv[3] == '--report-patches')
{
    #print_r($new_patches);
}

if(count($new_patches) == 0)
{
    fwrite(STDOUT, "No new patches to apply\n");
    exit(1);
}

if($argv[3] == '--report-patches')
{
    fwrite(STDOUT, "The following patches need to be applied: \n");
    foreach($new_patches as $identifier=>$path)
    {
        fwrite(STDOUT, "\t".$identifier."\n");
    }
    fwrite(STDOUT, "-----------------------------------------\n");
}


if($argv[3] !== '--do-patches')
{
    
    exit(0);
}


echo "\n"; 
foreach ($new_patches as $identifier => $file)
{
    $sql = file_get_contents($file);
    ORM::configure('error_mode', PDO::ERRMODE_EXCEPTION);
    ORM::get_db()->beginTransaction();
    try
    {
        fwrite(STDOUT, "-----------------------------------------\n");
        fwrite(STDOUT, "Applying ".$file."\n");
        ORM::raw_execute($sql);
        ORM::raw_execute('insert into patches (identifier) values (\''.addslashes($identifier).'\');');
        ORM::get_db()->commit();
        fwrite(STDOUT, "Success.\n");
    
    }
    catch(Exception $e)
    {
        ORM::get_db()->rollBack();
        fwrite(STDOUT, "Failure.\n");
        
        exit("An error occured in patch $identifier: ".$e->getMessage()."\n");
    }
    
}
fwrite(STDOUT, "-----------------------------------------\n");
exit(0);

?>