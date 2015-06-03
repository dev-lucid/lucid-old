<?php
define('__LUCID_LOAD_ONLY__',true);
$app_dir = __DIR__.'/../../../../'.'app/';
include($app_dir . 'app.php');

if(!isset($argv[1]))
{
    exit("Usage: php -f scripts/make_controller.php [table_name] [edit_field]\nThis script will construct a basic controller, grid and edit views for a table. By default, only one field will be displayed in the grid or editable. You may specify this field's name with the 2nd parameter, but if you leave it empty the script will by default use the string 'name' for the column.");
}

$table = $argv[1];
$class_name = 'lucid_model_'.$table;
$id_col  = $class_name::$_id_column;
$edit_col = (isset($argv[2]))?$argv[2]:'name';

$controller = file_get_contents(__DIR__.'/../template/make_controller/controller.php');
$controller = str_replace('{table}', $table, $controller);
$controller = str_replace('{id_col}',$id_col,$controller);
$controller = str_replace('{edit_col}',$edit_col,$controller);
file_put_contents($app_dir.'controllers/'.$table.'.php',$controller);


$grid = file_get_contents(__DIR__.'/../template/make_controller/grid.php');
$grid = str_replace('{table}', $table, $grid);
$grid = str_replace('{id_col}',$id_col,$grid);
$grid = str_replace('{edit_col}',$edit_col,$grid);
file_put_contents($app_dir.'views/'.$table.'_grid.php',$grid);

$edit = file_get_contents(__DIR__.'/../template/make_controller/edit.php');
$edit = str_replace('{table}', $table, $edit);
$edit = str_replace('{id_col}',$id_col,$edit);
$edit = str_replace('{edit_col}',$edit_col,$edit);
file_put_contents($app_dir.'views/'.$table.'_edit.php',$edit);

$view = file_get_contents(__DIR__.'/../template/make_controller/view.php');
$view = str_replace('{table}', $table, $view);
$view = str_replace('{id_col}',$id_col,$view);
$view = str_replace('{edit_col}',$edit_col,$view);
file_put_contents($app_dir.'views/'.$table.'.php',$view);

exit("Complete.\n");