<?php
require_once __DIR__.'/../vendor/Dev-Lucid/php-bootstrap/lib/php/bootstrap.php';
bootstrap::init([
    'form'=>['onsubmit'=>'return lucid.form.submit(this);']
]);
