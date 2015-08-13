<?php

class lucid_error 
{
    public static function handle_error($error_level, $error_message, $error_file, $error_line, $error_context)
    {
        print_r(func_get_args());
        #lucid::log($error_file.'['.$error_line.']: '.$error_message.' in '.$error_context,'error');
        #lucid::deinit();
    }

    public static function FriendlyErrorType($type) 
    { 
        switch($type) 
        { 
            case E_ERROR: // 1 // 
                return 'E_ERROR'; 
            case E_WARNING: // 2 // 
                return 'E_WARNING'; 
            case E_PARSE: // 4 // 
                return 'E_PARSE'; 
            case E_NOTICE: // 8 // 
                return 'E_NOTICE'; 
            case E_CORE_ERROR: // 16 // 
                return 'E_CORE_ERROR'; 
            case E_CORE_WARNING: // 32 // 
                return 'E_CORE_WARNING'; 
            case E_COMPILE_ERROR: // 64 // 
                return 'E_COMPILE_ERROR'; 
            case E_COMPILE_WARNING: // 128 // 
                return 'E_COMPILE_WARNING'; 
            case E_USER_ERROR: // 256 // 
                return 'E_USER_ERROR'; 
            case E_USER_WARNING: // 512 // 
                return 'E_USER_WARNING'; 
            case E_USER_NOTICE: // 1024 // 
                return 'E_USER_NOTICE'; 
            case E_STRICT: // 2048 // 
                return 'E_STRICT'; 
            case E_RECOVERABLE_ERROR: // 4096 // 
                return 'E_RECOVERABLE_ERROR'; 
            case E_DEPRECATED: // 8192 // 
                return 'E_DEPRECATED'; 
            case E_USER_DEPRECATED: // 16384 // 
                return 'E_USER_DEPRECATED'; 
        } 
        return ""; 
    } 

    public static function shutdown()
    {
        if ($error = error_get_last())
        {
            $error_string = lucid_error::FriendlyErrorType($error['type']).' on line '.$error['line'] .' in file '.$error['file'];
            lucid::logger()->error($error_string);
        } 
    }
}

set_error_handler('lucid_error::handle_error');
set_exception_handler('lucid_error::handle_error');
register_shutdown_function('lucid_error::shutdown');
