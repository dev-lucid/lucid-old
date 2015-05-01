<?php

class lucid_error 
{
    public static function handle_error($error_level, $error_message, $error_file, $error_line, $error_context)
    {
        lucid::log($error_file.'['.$error_line.']: '.$error_message.' in '.$error_context,'error');
        lucid::deinit();
    }
}

set_error_handler('lucid_error::handle_error');
set_exception_handler('lucid_error::handle_error');
?>