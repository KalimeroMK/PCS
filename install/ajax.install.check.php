<?php
$g5_path['path'] = '..';
include_once('../config.php');
include_once('./install.function.php');    // Collection of installation process functions
include_once('../lib/common.lib.php');    // Common library
include_once('../lib/hook.lib.php');    // Hook function file
include_once('../lib/get_data.lib.php');    // Collection of functions for getting data

$data_path = '../'.G5_DATA_DIR;

// If the file exists, installation is not possible.
$dbconfig_file = $data_path.'/'.G5_DBCONFIG_FILE;
if (file_exists($dbconfig_file)) {
    die(install_json_msg('The program is already installed.'));
    die(install_json_msg('The program is already installed.'));
}

if (isset($_POST['table_prefix']) && preg_match("/[^0-9a-z_]+/i", $_POST['table_prefix'])) {
    die(install_json_msg('Table names can only contain letters, numbers, and underscores.'));
}

$mysql_host  = isset($_POST['mysql_host']) ? safe_install_string_check($_POST['mysql_host'], 'json') : '';
$mysql_user  = isset($_POST['mysql_user']) ? safe_install_string_check($_POST['mysql_user'], 'json') : '';
$mysql_pass  = isset($_POST['mysql_pass']) ? safe_install_string_check($_POST['mysql_pass'], 'json') : '';
$mysql_db    = isset($_POST['mysql_db']) ? safe_install_string_check($_POST['mysql_db'], 'json') : '';
$table_prefix= isset($_POST['table_prefix']) ? safe_install_string_check(preg_replace('/[^a-zA-Z0-9_]/', '_', $_POST['table_prefix'])) : '';

$tmp_str = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
$ajax_token = md5($tmp_str.$_SERVER['REMOTE_ADDR'].dirname(dirname(__FILE__).'/'));

$bool_ajax_token = (isset($_POST['ajax_token']) && ($ajax_token == $_POST['ajax_token'])) ? true : false;

if (!($mysql_host && $mysql_user && $mysql_pass && $mysql_db && $table_prefix && $bool_ajax_token)) {
    die(install_json_msg('Invalid request.'));
}

try {
    $dblink = sql_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
} catch (Exception $e) {
}

if (!isset($dblink)) {
    die(install_json_msg('Please check your MySQL Host, User, and Password.'));
}

try {
    $select_db = sql_select_db($mysql_db, $dblink);
} catch (Exception $e) {
}

if (!isset($select_db)) {
    die(install_json_msg('Please check your MySQL DB.'));
}

if (sql_query("SHOW TABLES LIKE `{$table_prefix}config`", G5_DISPLAY_SQL_ERROR, $dblink)) {
    die(install_json_msg('Warning! This table already exists, so existing DB data may be lost. Continue?', 'exists'));
}

die(install_json_msg('ok', 'success'));
