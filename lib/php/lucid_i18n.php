<?php

class lucid_i18n 
{
    public static function translate()
    {
        global $lucid;
        $params = func_get_args();
        $message = array_shift($params);

        if (isset($lucid->config['dictionary_messages'][$message]))
        {
            $message = $lucid->config['dictionary_messages'][$message];
            foreach($params as $index=>$value)
            {
                $message = str_replace('{{'.$index.'}}',$value,$message);
            }
            return $message;
        }
        else
        {
            lucid::log('could not find translation for message '.$message.' in language '.$lucid->config['language_tag'].'. Params passed: '.str_replace("\n","\t",print_r($params,true)));    
            return $message;
        }
        
    }

    public static function add($message,$translation)
    {
        global $lucid;
        $lucid->config['dictionary_messages'][$message] = $translation;
    }

    public static function init()
    {
        global $lucid;

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            lucid::log('preferred language is: '.$_SERVER['HTTP_ACCEPT_LANGUAGE']);    
        }

        foreach($lucid->config['dictionaries'] as $dict_path)
        {
            $dict_file = $dict_path . $lucid->config['language_tag'].'.php';
            if(file_exists($dict_file))
            {
                include($dict_file);
            }
            else
            {
                lucid::log('Could not find dictionary for language '.$lucid->config['language_tag'].' in '.$dict_path);
            }
        }
    }
}
