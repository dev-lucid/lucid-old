<?php
include(__DIR__.'/dependencies.php');
require_once __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/php/lucid_session.php';
$lucid->session = new lucid_session();
?>