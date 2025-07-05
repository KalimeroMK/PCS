<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');

$mb_id = isset($_POST['mb_id']) ? substr(clean_xss_tags($_POST['mb_id']), 0, 20) : '';
$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';

if (!$mb_id || !$mb_email) {
    alert('Please use the correct method.', G5_URL);
}

$sql = " select mb_name from {$g5['member_table']} where mb_id = '{$mb_id}' and substring(mb_email_certify, 1, 1) = '0' ";
$mb = sql_fetch($sql);
if (!$mb) {
    alert("This member has already been email verified.", G5_URL);
}

if (!chk_captcha()) {
    alert('The anti-spam number is incorrect.');
}

$sql = " select count(*) as cnt from {$g5['member_table']} where mb_id <> '{$mb_id}' and mb_email = '$mb_email' ";
$row = sql_fetch($sql);
if ($row['cnt']) {
    alert("The email address {$mb_email} already exists.\n\nPlease enter a different email address.");
}

// Send verification email
$subject = '[' . $config['cf_title'] . '] Email Verification';

$mb_name = $mb['mb_name'];

// Generate a one-time random number that does not include any member information and use it for authentication
$mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));

sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");

$certify_href = G5_BBS_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;

ob_start();
include_once(__DIR__ . '/register_form_update_mail3.php');
$content = ob_get_contents();
ob_end_clean();

mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

$sql = " update {$g5['member_table']} set mb_email = '$mb_email' where mb_id = '$mb_id' ";
sql_query($sql);

alert("A verification email has been sent to {$mb_email}.\n\nPlease check your email at {$mb_email} shortly.", G5_URL);