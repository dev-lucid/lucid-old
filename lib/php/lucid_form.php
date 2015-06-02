<?php

class lucid_form
{
    public static function send_errors($error_list,$deinit = true)
    {
        if ($error_list === true)
        {
            return;
        }
        
        if(is_string($error_list))
        {
            $error_list = [''=>[$error_list]];
        }

        lucid::javascript("lucid.form.showErrors('".$_REQUEST['_form_identifier']."',".json_encode($error_list).");");

        if($deinit === true)
        {
            lucid::deinit();
        }
    }
}
