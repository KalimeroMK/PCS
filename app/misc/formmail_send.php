<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');

if (!$config['cf_email_use']) {
    alert('Please enable "Use email sending" in the environment settings to send emails.\n\nContact the administrator.');
}

if (!$is_member && $config['cf_formmail_is_member']) {
    alert_close('Only members can use this feature.');
}

$email_enc = new str_encrypt();
$to = $email_enc->decrypt($to);

if (!chk_captcha()) {
    alert('The anti-bot number is incorrect.');
}

if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $to)) {
    alert_close('The email address format is invalid, so the email cannot be sent.');
}

$file = [];
for ($i = 1; $i <= $attach; $i++) {
    if ($_FILES['file' . $i]['name']) {
        $file[] = attach_file($_FILES['file' . $i]['name'], $_FILES['file' . $i]['tmp_name']);
    }
}

$content = stripslashes($content);
if ($type == 2) {
    $type = 1;
    $content = str_replace("\n", "<br>", $content);
}

// html 이면
if ($type) {
    $current_url = G5_URL;
    $mail_content = '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Send Email</title><link rel="stylesheet" href="' . $current_url . '/style.css"></head><body>' . $content . '</body></html>';
} else {
    $mail_content = $content;
}

mailer($fnick, $fmail, $to, $subject, $mail_content, $type, $file);

// 임시 첨부파일 삭제
foreach ($file as $f) {
    @unlink($f['path']);
}

$html_title = 'Sending Email';
include_once(G5_PATH . '/head.sub.php');

alert_close('The email has been sent successfully.');

include_once(G5_PATH . '/tail.sub.php');