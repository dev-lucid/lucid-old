<?php

global $lucid;

# construct a formatter 
$ip   = str_pad($_SERVER['REMOTE_ADDR'],15,' ',STR_PAD_LEFT);
$id   = lucid::session()->session_id();
$formatter = new Monolog\Formatter\LineFormatter('[%level_name%][%datetime%]['.$ip.']['.$id.']: %message% %context%'."\n", 'Y-m-d H:i:s');

# construct the log handler
$handler = new Monolog\Handler\StreamHandler(__DIR__.'/../debug.log', Monolog\Logger::INFO);
$handler->setFormatter($formatter);

# construct monolog, add our handler
$monolog = new Monolog\Logger('lucid_log');
$monolog->pushHandler($handler);

# assign it to the lucid logger config so that it can be used via lucid::log or lucid::logger()->alert/debug/error/critical
$lucid->config['logger'] = $monolog;
