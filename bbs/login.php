<?php

include_once(__DIR__ . '/../common.php');


if (function_exists('social_check_login_before')) {
    $social_login_html = social_check_login_before();
}

$g5['title'] = 'Login';
include_once(__DIR__ . '/_head.sub.php');

$url = isset($_GET['url']) ? strip_tags($_GET['url']) : '';
$od_id = isset($_POST['od_id']) ? safe_replace_regex($_POST['od_id'], 'od_id') : '';

// url check
check_url_host($url);

// If already logged in
if ($is_member) {
    if ($url !== '' && $url !== '0') {
        goto_url($url);
    } else {
        goto_url(G5_URL);
    }
}

$login_url = login_url($url);
$login_action_url = G5_HTTPS_BBS_URL."/login_check.php";

// If login skin does not exist, use the default skin to prevent admin page access issues
$login_file = $member_skin_path.'/login.skin.php';
if (!file_exists($login_file)) {
    $member_skin_path = G5_SKIN_PATH.'/member/basic';
}

include_once($member_skin_path.'/login.skin.php');

run_event('member_login_tail', $login_url, $login_action_url, $member_skin_path, $url);

include_once(__DIR__ . '/_tail.sub.php');