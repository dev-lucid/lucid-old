<?php 
$app_name   = $argv[1];
$app_dir    = $argv[2];
$model_path = $app_dir.'/db/models/';

# include necessary configuration files
include($app_dir.'/etc/db.php');
include($app_dir.'/etc/autoload.php');


function get_columns($table_name)
{
    $db_type = ORM::get_db()->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($db_type == 'sqlite')
    {
        $stmt = ORM::get_db()->prepare("PRAGMA table_info('".$table_name."');") ;
        $stmt->execute() ;
        $columns = $stmt->fetchAll();

        $colnames = array();
        foreach($columns as $column)
        {
            $colnames[] = $column['name'];
        }
        return $colnames;
    }
    else
    {
        # WARNING: Untested D:
        ORM::raw_execute('SELECT * FROM '.$table_name.' limit 0;');
        $statement = ORM::get_last_statement();
        $colnames = [];
        for($i=0;$i<$statement->columnCount();$i++)
        {
            $col = $statement->getColumnMeta($i);
            $colnames[] = $col['name'];
        }
        return $colnames;
    }
}

function get_tables()
{
    $db_type = ORM::get_db()->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($db_type == 'sqlite')
    {
        ORM::raw_execute('SELECT name FROM sqlite_master WHERE type in  (\'table\',\'view\');');
        $tables = ORM::get_last_statement()->fetchAll();
    }
    else
    {
        ORM::raw_execute('select table_name as name from information_schema.tables where table_type = \'BASE TABLE\';');
        $tables = ORM::get_last_statement()->fetchAll();

    }
    return $tables;
}


$tables = get_tables();
foreach($tables as $table)
{
    # this name is reserved by sqlite
    if($table['name'] !== 'sqlite_sequence')
    {
        echo("Checking for model file for table ".$table['name']."\n");
        if(file_exists($model_path.$table['name'].'.php'))
        {
            echo("\t".$model_path.$table['name'].".php exists, NOT generating\n");
        }
        else
        {
            # try to get column information
            $columns = get_columns($table['name']);
            $id_column = $columns[0];
            $id_column = explode('.',$id_column);
            $id_column = array_pop($id_column);

            echo("\t".$model_path.$table['name'].".php did not exist, generating\n");
            $model_text  = "<"."?php\nclass lucid_model_".$table['name']." extends Model\n{\n";
            $model_text .= "\tpublic static $"."_table = '".$table['name']."';\n";
            $model_text .= "\tpublic static $"."_id_column = '".$id_column."';\n";
            $model_text .= "}\n";
            $model_text .= "?".">";
            file_put_contents($model_path.$table['name'].'.php',$model_text);
        }
    }
}

?>