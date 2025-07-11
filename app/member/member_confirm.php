<?php

include_once(__DIR__ . '/../common.php');


if ($is_guest) {
    alert('Only logged-in members can access this page.', G5_BBS_URL . '/login.php');
}

$url = isset($_GET['url']) ? clean_xss_tags($_GET['url']) : '';

while (1) {
    $tmp = preg_replace('/&#[^;]+;/', '', $url);
    if ($tmp == $url) {
        break;
    }
    $url = $tmp;
}

//Social login case
if (function_exists('social_member_comfirm_redirect') && (!$url || $url === 'register_form.php' || (function_exists('social_is_edit_page') && social_is_edit_page($url)))) {
    social_member_comfirm_redirect();
}

$url = run_replace('member_confirm_next_url', $url);

$g5['title'] = 'Member Password Confirmation';
include_once(__DIR__ . '/_head.sub.php');

// url check
check_url_host($url, '', G5_URL, true);

if ($url) {
    $url = preg_replace('#^/\\\{1,}#', '/', $url);

    if (preg_match('#^/{3,}#', $url)) {
        $url = preg_replace('#^/{3,}#', '/', $url);
    }
}

$url = get_text($url);

include_once($member_skin_path . '/member_confirm.skin.php');

include_once(__DIR__ . '/_tail.sub.php');