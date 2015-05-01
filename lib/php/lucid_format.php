<?php

class lucid_format
{
    public $date_format_aliases = [
        
    ];
    
    public static function date($input=null,$output_format='ISO8601')
    {
        global $lucid;
        
        if(is_null($input))
        {
            $input = time();
        }
        
        if (isset($lucid->config['formats']['dates'][$output_format]))
        {
            $output_format = $lucid->config['formats']['dates'][$output_format];
        }
        
        return date($output_format,$input);
    }
}

?>