<?php
global $files;
include(__DIR__.'/dependencies.php');

$files = array(
    __DIR__.'/../www/media/js/jquery.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/tooltip.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/affix.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/alert.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/button.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/carousel.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/collapse.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/dropdown.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/modal.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/popover.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/scrollspy.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/tab.js',
    __DIR__.'/../../../lib/bootstrap/'.$dependencies['lib/bootstrap']['branch'].'/js/transition.js',
    __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/js/lucid.js', 
    __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/js/lucid.form.js', 
    __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/js/lucid.ruleset.js', 
    __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/js/lucid.rule.js', 
    __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/js/lucid.html.js', 
    __DIR__.'/../../../lib/lucid/'.$dependencies['lib/lucid']['branch'].'/lib/js/lucid.html.grid.js', 
    //__DIR__.'/../../../lib/fuelux/3.6.4/dist/js/fuelux.min.js', 
    __DIR__.'/../www/media/js/customizations.js',
);
?>