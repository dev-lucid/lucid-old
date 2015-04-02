<?php
date_default_timezone_set('UTC');
include(__DIR__.'/../etc/dependencies.php');
include(__DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid.php');
include(__DIR__.'/../etc/db.php');
include(__DIR__.'/../etc/autoload.php');


lucid::init(__DIR__);

# the session and logger libs must be included *after* lucid is inited, as they set the properties $lucid->session and $lucid->logger
include(__DIR__.'/../etc/session.php'); 
include(__DIR__.'/../etc/logger.php'); 

lucid::log('----------------------------------------------');
lucid::log('request start');
try
{
    lucid::process_request();
}
catch(Exception $e)
{
    lucid::clear_response();
    lucid::javascript('lucid.showError(\'An error occured during your request. Our development team has been notified and will be looking into the problem shortly. We apologize for the inconvenience.\');');
    lucid::log($e->getMessage(),'exception');
}
lucid::log('request end');
lucid::log('==============================================');
lucid::deinit();
?>