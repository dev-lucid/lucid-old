<?php

class lucid_form extends Model
{
    function send_errors($error_list,$deinit = true)
    {
        if(is_string($error_list))
        {
            $error_list = [$error_list];
        }

        lucid::javascript("lucid.form.showErrors('".$_REQUEST['_formIdentifier']."',".json_encode($error_list).");");

        if($deinit === true)
        {
            lucid::deinit();
        }
    }
}

?>