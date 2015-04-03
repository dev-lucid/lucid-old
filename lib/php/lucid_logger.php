<?php

class lucid_logger 
{
    function __construct($path)
    {
        $this->file_handle = fopen($path,'a');
    }

    function write($string,$type='debug')
    {
        global $lucid;
        $time = date('Y-m-d H:i:s');
        $ip   = str_pad($_SERVER['REMOTE_ADDR'],15,' ',STR_PAD_LEFT);
        $id   = $lucid->session->session_id();
        $type   = str_pad($type,10,' ',STR_PAD_LEFT);
        $final_string = $time.'|'.$ip.'|'.$id.'|'.$type.'|'.$string."\n";
        fwrite($this->file_handle,$final_string);
    }

    function __destruct()
    {
        fclose($this->file_handle);
    }
}

?>