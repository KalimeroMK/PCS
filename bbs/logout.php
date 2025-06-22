<?php

define('G5_CERT_IN_PROG', true);
include_once(__DIR__ . '/../common.php');


if (function_exists('social_provider_logout')) {
    social_provider_logout();
}

// Suggested code by Lee Ho-kyung
session_unset();   // Unset all session variables
session_destroy(); // Destroy session

// Disable auto-login --------------------------------
set_cookie('ck_mb_id', '', 0); // Remove auto-login
set_cookie('ck_auto', '', 0); // Remove auto-login end
// Disable auto-login end --------------------------------

if ($url) {
    if (substr($url, 0, 2) === '//') {
        $url = 'http:'.$url;
    }
    $p = @parse_url(urldecode($url));
    /*
        // OpenRedirect mitigation, PHP 5.3 and below parse_url bug (Safflower's suggestion) Example below
        // http://localhost/bbs/logout.php?url=http://sir.kr%23@/
    */
    if (preg_match('/^https?:\/\//i', $url) || $p['scheme'] || $p['host']) {
        alert('Cannot specify domain in url.', G5_URL);
    }
    $link = $url == 'shop' ? G5_SHOP_URL : $url;
} elseif ($bo_table) {
    $link = get_pretty_url($bo_table);
} else {
    $link = G5_URL;
}

run_event('member_logout', $link);

goto_url($link);