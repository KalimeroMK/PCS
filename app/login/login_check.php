<?php

include_once(__DIR__ . '/../common.php');

// Login Check
$g5['title'] = 'Login Check';

$mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
$mb_password = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';

run_event('member_login_check_before', $mb_id);

if (!$mb_id || run_replace('check_empty_member_login_password', !$mb_password, $mb_id)) {
    alert('Member ID or password cannot be blank.');
}

$mb = get_member($mb_id);

// Check if social login is enabled
$is_social_login = false;
$is_social_password_check = false;

// Check if social login is enabled and if the password needs to be checked
if (function_exists('social_is_login_check')) {
    $is_social_login = social_is_login_check();

    // Decide whether to check the password
    // Do not check the password for social login, but check for account linking
    $is_social_password_check = social_is_login_password_check($mb_id);
}

$is_need_not_password = run_replace('login_check_need_not_password', $is_social_password_check, $mb_id, $mb_password,
    $mb, $is_social_login);

// If $is_need_not_password is true, do not check the password
// Do not show the message "The entered member ID does not exist or the password is incorrect."
// to prevent brute-force attacks
if (!$is_need_not_password && (!(isset($mb['mb_id']) && $mb['mb_id']) || !login_password_check($mb, $mb_password,
            $mb['mb_password']))) {
    run_event('password_is_wrong', 'login', $mb);

    alert('The entered member ID does not exist or the password is incorrect.\nPasswords are case sensitive.');
}

// Check if the member ID is blocked
if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
    $date = preg_replace("/(\\d{4})(\\d{2})(\\d{2})/", "\\1 year \\2 month \\3 day", $mb['mb_intercept_date']);
    alert('The member ID is blocked.\nBlocked date: ' . $date);
}

// Check if the member ID is withdrawn
if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
    $date = preg_replace("/(\\d{4})(\\d{2})(\\d{2})/", "\\1 year \\2 month \\3 day", $mb['mb_leave_date']);
    alert('This is a withdrawn ID, so access is not allowed.\nWithdrawal date: ' . $date);
}

// Check if email verification is required
if (is_use_email_certify() && !preg_match("/[1-9]/", $mb['mb_email_certify'])) {
    $ckey = md5($mb['mb_ip'] . $mb['mb_datetime']);
    confirm("You must verify your email to log in with {$mb['mb_email']}.", G5_URL,
        G5_BBS_URL . '/register_email.php?mb_id=' . $mb_id . '&ckey=' . $ckey);
}

run_event('login_session_before', $mb, $is_social_login);

@include_once($member_skin_path . '/login_check.skin.php');

if (!(defined('SKIP_SESSION_REGENERATE_ID') && SKIP_SESSION_REGENERATE_ID)) {
    session_regenerate_id(false);
    if (function_exists('session_start_samesite')) {
        session_start_samesite();
    }
}

// Create a session for the member ID
set_session('ss_mb_id', $mb['mb_id']);
// Create a unique key for the member to prevent FLASH XSS attacks
generate_mb_key($mb);

// Store the member's token key in the session
if (function_exists('update_auth_session_token')) {
    update_auth_session_token($mb['mb_datetime']);
}

// Check the member's points
if ($config['cf_use_point']) {
    $sum_point = get_point_sum($mb['mb_id']);

    $sql = " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '{$mb['mb_id']}' ";
    sql_query($sql);
}

// Store the member ID in a cookie for a month
if (isset($auto_login) && $auto_login) {
    // Store the member ID and password in cookies for a month
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
    set_cookie('ck_mb_id', $mb['mb_id'], 86400 * 31);
    set_cookie('ck_auto', $key, 86400 * 31);
} else {
    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

if ($url) {
    // Check the URL
    check_url_host($url, '', G5_URL, true);

    $link = urldecode($url);
    // Add variables to the URL
    $split = preg_match("/\?/", $link) ? "&amp;" : "?";

    // Check the POST variables
    $post_check_keys = ['mb_id', 'mb_password', 'x', 'y', 'url'];

    // Add social login variables
    if ($is_social_login) {
        $post_check_keys[] = 'provider';
    }

    $post_check_keys = run_replace('login_check_post_check_keys', $post_check_keys, $link, $is_social_login);

    foreach ($_POST as $key => $value) {
        if ($key && !in_array($key, $post_check_keys)) {
            $link .= "$split$key=$value";
            $split = "&amp;";
        }
    }
} else {
    $link = G5_URL;
}

// Call the social login success function
if (function_exists('social_login_success_after')) {
    // Update the social login data
    $link = social_login_success_after($mb, $link);
    social_login_session_clear(1);
}

// Call the cart ID function
if (function_exists('set_cart_id')) {
    $member = $mb;

    // Clean up the cart
    cart_item_clean();
    set_cart_id('');
    $s_cart_id = get_session('ss_cart_id');
    // Initialize the cart
    $sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
    sql_query($sql);
}

run_event('member_login_check', $mb, $link, $is_social_login);

// Check the administrator's permissions
if (is_admin($mb['mb_id']) && is_dir(G5_DATA_PATH . '/tmp/')) {
    $tmp_data_file = G5_DATA_PATH . '/tmp/tmp-write-test-' . time();
    $tmp_data_check = @fopen($tmp_data_file, 'w');
    if ($tmp_data_check && !@fwrite($tmp_data_check, G5_URL)) {
        $tmp_data_check = false;
    }
    if (is_resource($tmp_data_check)) {
        @fclose($tmp_data_check);
    }
    @unlink($tmp_data_file);

    if (!$tmp_data_check) {
        alert("The data folder does not have write permissions or the web hard disk is full.\nPlease check the permissions and disk space.", $link);
    }
}

goto_url($link);