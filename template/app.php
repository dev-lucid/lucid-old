<?php

date_default_timezone_set('UTC');

# Include the generated autoloader from composer
$loader = include(__DIR__.'/../vendor/autoload.php');
$loader->add('lucid_model_', __DIR__.'/../database/models/');

# Fire up lucid
lucid::init(__DIR__);

# Include various configs in order. Each config should setup its area, not just define values.
include(__DIR__.'/../config/database.php');  # by default, uses idiorm/paris.
include(__DIR__.'/../config/session.php');   # by default, uses lucid_session class.
include(__DIR__.'/../config/logger.php');    # by default, uses Monolog
include(__DIR__.'/../config/mailer.php');    # by default, uses phpmailer
include(__DIR__.'/../config/security.php');  # by default, uses lucid_session
include(__DIR__.'/../config/bootstrap.php'); # by default, uses php-bootstrap

# i18n must be inited after session as the session may contain a key that specifies a specific language.
include(__DIR__.'/../config/i18n.php');

# specify some default commands to run on every request
#lucid::add_action_to_list('pre-request', 'authentication','check_auth_token');
#lucid::add_action_to_list('pre-request', 'authentication','check_force_password_change');
lucid::add_action_to_list('post-request','navigation','check_state');

lucid::log('app.php: request start. Action passed is: '.$_REQUEST['action']);
try
{
    lucid::process_request();
}
catch(Exception $e)
{
    lucid::clear_response();
    lucid::javascript('lucid.showError(\'An error occured during your request. Our development team has been notified and will be looking into the problem shortly. We apologize for the inconvenience.\');');
    lucid::logger()->error($e->getMessage());
}

lucid::deinit();
