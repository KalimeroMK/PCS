<?php

include_once(__DIR__ . '/../common.php');


// Prevent mail bot link crawling
if (function_exists('check_mail_bot')) {
    check_mail_bot($_SERVER['REMOTE_ADDR']);
}

$mb_id = isset($_GET['mb_id']) ? trim($_GET['mb_id']) : '';
$mb_md5 = isset($_GET['mb_md5']) ? trim($_GET['mb_md5']) : '';

$sql = " select mb_id, mb_email_certify2, mb_leave_date, mb_intercept_date from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$row = sql_fetch($sql);
if (!$row['mb_id']) {
    alert('The member does not exist.', G5_URL);
}

if ($row['mb_leave_date'] || $row['mb_intercept_date']) {
    alert('This member has withdrawn or is suspended.', G5_URL);
}

// Certification link can only be processed once
sql_query(" update {$g5['member_table']} set mb_email_certify2 = '' where mb_id = '$mb_id' ");

if ($mb_md5 !== '' && $mb_md5 !== '0') {
    if ($mb_md5 == $row['mb_email_certify2']) {
        sql_query(" update {$g5['member_table']} set mb_email_certify = '".G5_TIME_YMDHIS."' where mb_id = '{$mb_id}' ");

        alert("Email certification completed.\n\nYou can now log in with the {$mb_id} ID.", G5_URL);
    } else {
        alert('The email certification request information is incorrect.', G5_URL);
    }
}

alert('The value was not passed correctly.', G5_URL);