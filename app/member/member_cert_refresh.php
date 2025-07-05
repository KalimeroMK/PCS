<?php

define('G5_CERT_IN_PROG', true);
include_once(__DIR__ . '/../common.php');


if (!$is_member) {
    alert("Invalid access.", G5_URL);
}

if (!empty($member['mb_certify']) && strlen($member['mb_dupinfo']) != 64) { // Account without identity verification or account verified by ci
    alert("Invalid access.", G5_URL);
}

if ($config['cf_cert_use'] == 0) {
    alert("Cannot use identity verification. Please contact the administrator.", G5_URL);
}

$g5['title'] = 'Please re-verify your identity.';
include_once(__DIR__ . '/../header.php');

$action_url = G5_HTTPS_BBS_URL . "/member_cert_refresh_update.php";
include_once($member_skin_path . '/member_cert_refresh.skin.php');

include_once(__DIR__ . '/footer.php');