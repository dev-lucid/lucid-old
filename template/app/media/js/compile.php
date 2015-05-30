<?php
global $files;
include(__DIR__.'/../../../config/js.php');
    
header('Content-type: text/javascript; charset=UTF-8');
header('Cache-Control: no-cache, must-revalidate'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

function compress($buffer) {

    // mike version
    $buffer = preg_replace("/(?:\/\*(?:[\s\S]*?)\*\/)|(?:([\s;])+\/\/(?:.*)$)/m", "$1",  $buffer);
    //$buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);


    /* remove tabs, spaces, newlines, etc. */
    //$buffer = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $buffer);
    /* remove other spaces before/after ) */
    $buffer = preg_replace(array('(( )+\))','(\)( )+)'), ')', $buffer);
    return $buffer;
}

ob_start("compress");

foreach($files as $file)
{
    include($file);
}
$src = ob_get_clean();
file_put_contents(__DIR__.'/production.js',$src);
echo($src);

exit();
?>