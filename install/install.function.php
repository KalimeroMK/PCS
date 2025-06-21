<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

if( ! function_exists('array_map_deep') ){
    // Apply user-defined function to a multi-dimensional array
    function array_map_deep($fn, $array)
    {
        if(is_array($array)) {
            foreach($array as $key => $value) {
                if(is_array($value)) {
                    $array[$key] = array_map_deep($fn, $value);
                } else {
                    $array[$key] = call_user_func($fn, $value);
                }
            }
        } else {
            $array = call_user_func($fn, $array);
        }

        return $array;
    }
}

if( ! function_exists('safe_install_string_check') ){
    function safe_install_string_check( $str, $is_json=false ) {
        $is_check = false;

        // Check for use of dangerous functions
        if(preg_match('#\);(passthru|eval|pcntl_exec|exec|system|popen|fopen|fsockopen|file|file_get_contents|readfile|unlink|include|include_once|require|require_once)\s?#i', $str)) {
            $is_check = true;
        }

        // Check for use of superglobals in input
        if(preg_match('#\$_(get|post|request)\s?\[.*?\]\s?\)#i', $str)){
            $is_check = true;
        }

        if($is_check){
            $msg = "The entered value contains unsafe characters. Installation aborted.";

            if($is_json){
                die(install_json_msg($msg));
            }

            die($msg);
        }

        return array_map_deep('stripslashes', $str);
    }
}

if( ! function_exists('install_json_msg') ){
    // Returns a JSON-encoded message for installation process feedback
    function install_json_msg($msg, $type='error'){

        $error_msg = ($type==='error') ? $msg : '';
        $success_msg = ($type==='success') ? $msg : '';
        $exists_msg = ($type==='exists') ? $msg : '';

        return json_encode(array('error'=>$error_msg, 'success'=>$success_msg, 'exists'=>$exists_msg));
    }
}