<?php

class lucid_logger 
{
    function __construct($path)
    {
        $this->file_handle = fopen($path,'a');
        fwrite($this->file_handle,lucid_logger::build_prefix('start').'--------------------------------------------------------------------------------------------'."\n");
    }

    public static function build_prefix($type)
    {
        $time = date('Y-m-d H:i:s');
        $ip   = str_pad($_SERVER['REMOTE_ADDR'],15,' ',STR_PAD_LEFT);
        $id   = lucid::session()->session_id();
        $type = str_pad($type,10,' ',STR_PAD_LEFT);
        return $time.'|'.$ip.'|'.$id.'|'.$type.'|';
    }

    function write($string,$type='debug')
    {
        global $lucid;

        if(is_object($string) or is_array($string))
        {
            $string = str_replace("\n","\t",print_r($string,true));
        }

        fwrite($this->file_handle,$this->build_prefix($type).$string."\n");
    }

    function __destruct()
    {
        fwrite($this->file_handle,lucid_logger::build_prefix('end').'============================================================================================'."\n");
        fclose($this->file_handle);
    }
}

?>