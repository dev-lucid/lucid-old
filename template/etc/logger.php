<?php
include(__DIR__.'/dependencies.php');
require_once __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid_logger.php';
$lucid->logger = new lucid_logger(__DIR__.'/../var/debug.log');
?>