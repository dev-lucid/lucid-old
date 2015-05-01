<?php
include(__DIR__.'/dependencies.php');
require_once __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid_security.php';
$lucid->security = new lucid_security();
?>