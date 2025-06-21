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
    die(install_json_msg('프로그램이 이미 설치되어 있습니다.'));
}

if (isset($_POST['table_prefix']) && preg_match("/[^0-9a-z_]+/i", $_POST['table_prefix'])) {
    die(install_json_msg('TABLE명 접두사는 영문자, 숫자, _ 만 입력하세요.'));
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
    die(install_json_msg('잘못된 요청입니다.'));
}

try {
    $dblink = sql_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
} catch (Exception $e) {
}

if (!isset($dblink)) {
    die(install_json_msg('MySQL Host, User, Password 를 확인해 주십시오.'));
}

try {
    $select_db = sql_select_db($mysql_db, $dblink);
} catch (Exception $e) {
}

if (!isset($select_db)) {
    die(install_json_msg('MySQL DB 를 확인해 주십시오.'));
}

if (sql_query("SHOW TABLES LIKE `{$table_prefix}config`", G5_DISPLAY_SQL_ERROR, $dblink)) {
    die(install_json_msg('주의! 이미 테이블이 존재하므로, 기존 DB 자료가 망실됩니다. 계속 진행하겠습니까?', 'exists'));
}

die(install_json_msg('ok', 'success'));
