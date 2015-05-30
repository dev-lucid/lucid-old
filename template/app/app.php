<?php
date_default_timezone_set('UTC');
include(__DIR__.'/../etc/dependencies.php');
include(__DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid.php');
include(__DIR__.'/../etc/db.php');
include(__DIR__.'/../etc/autoload.php');


lucid::init(__DIR__);

# the session, logger, security libs must be included *after* lucid is inited, as they set the properties $lucid->session, $lucid->logger, $lucid->security.
# Also, the order is important. Session must be started first as the logger uses session id. Security may be included, but won't work
# until $lucid->session is available.
include(__DIR__.'/../etc/session.php'); 
include(__DIR__.'/../etc/logger.php');
include(__DIR__.'/../etc/security.php'); 

# i18n must be inited after session as the session may contain a key that specifies a specific language.
include(__DIR__.'/../etc/i18n.php');

lucid::add_action_to_list('pre-request', 'authentication','check_auth_token');
lucid::add_action_to_list('post-request','navigation',    'check_state');

lucid::log('request start. Action passed is: '.$_REQUEST['action']);
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

lucid::deinit();
?>