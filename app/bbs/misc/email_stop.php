<?php

include_once(__DIR__ . '/../common.php');


$mb_id = isset($_REQUEST['mb_id']) ? clean_xss_tags($_REQUEST['mb_id'], 1, 1) : '';

$sql = " select mb_id, mb_email, mb_datetime from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$row = sql_fetch($sql);
if (!$row['mb_id']) {
    alert('The member does not exist.', G5_URL);
}

if ($mb_md5) {
    $tmp_md5 = md5($row['mb_id'].$row['mb_email'].$row['mb_datetime']);
    if ($mb_md5 == $tmp_md5) {
        sql_query(" update {$g5['member_table']} set mb_mailling  = 0 where mb_id = '{$mb_id}' ");

        alert('You have successfully unsubscribed from information emails.', G5_URL);
    }
}

alert('The value was not passed correctly.', G5_URL);