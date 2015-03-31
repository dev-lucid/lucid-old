<?php
include(__DIR__.'/../etc/dependencies.php');
include(__DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid.php');
include(__DIR__.'/../etc/db.php');
include(__DIR__.'/../etc/autoload.php');

lucid::init(__DIR__);
try
{
    lucid::process_request();
}
catch(Exception $e)
{
    lucid::clear_response();
    lucid::javascript('lucid.showError(\'An error occured during your request. Our development team has been notified and will be looking into the problem shortly. We apologize for the inconvenience.\');');
    error_log($e->getMessage());
}

lucid::deinit();
?>