<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

if (!$config['cf_email_use']) {
    alert_close('Please enable "Use email sending" in the environment settings to send emails.\n\nContact the administrator.');
}

if (!$is_member && $config['cf_formmail_is_member']) {
    alert_close('Only members can use this feature.');
}

$mb_id = isset($mb_id) ? get_search_string($mb_id) : '';

if ($is_member && !$member['mb_open'] && $is_admin != "super" && $member['mb_id'] != $mb_id) {
    alert_close('You cannot send emails to others unless you make your information public.\n\nYou can set information disclosure in the member information edit page.');
}

if ($mb_id) {
    $mb = get_member($mb_id);
    if (!$mb['mb_id']) {
        alert_close('Member information does not exist.\n\nIt may be a withdrawn member.');
    }

    if (!$mb['mb_open'] && $is_admin != "super") {
        alert_close('Information is not public.');
    }
}

$sendmail_count = (int)get_session('ss_sendmail_count') + 1;
if ($sendmail_count > 3) {
    alert_close('You can only send a certain number of emails per session.\n\nTo continue sending, please log in or reconnect.');
}

$g5['title'] = 'Write Email';
include_once(G5_PATH . '/head.sub.php');

$email_enc = new str_encrypt();
$email_dec = $email_enc->decrypt($email);

$email = get_email_address($email_dec);
if (!$email) {
    alert_close('The email is incorrect.');
}

$email = $email_enc->encrypt($email);

$name = $name ? get_text(stripslashes($name), true) : $email;

if (!isset($type)) {
    $type = 0;
}

$type_checked[0] = $type_checked[1] = $type_checked[2] = "";
$type_checked[$type] = 'checked';

include_once($member_skin_path . '/formmail.skin.php');

include_once(G5_PATH . '/tail.sub.php');