<?php

include_once(__DIR__ . '/../common.php');


if (!$member['mb_id']) {
    alert('Only members can access this.', G5_URL);
}

if ($is_admin == 'super') {
    alert('The super administrator cannot withdraw.', G5_URL);
}

$post_mb_password = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';

if (!($post_mb_password && check_password($post_mb_password, $member['mb_password']))) {
    alert('The password is incorrect.');
}

// Save member withdrawal date
$date = date("Ymd");
$sql = " update {$g5['member_table']} set mb_leave_date = '{$date}', mb_memo = '" . date('Ymd',
        G5_SERVER_TIME) . " Withdrawn\n" . sql_real_escape_string($member['mb_memo']) . "', mb_certify = '', mb_adult = 0, mb_dupinfo = '' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

run_event('member_leave', $member);

// 3.09 modification (logout)
unset($_SESSION['ss_mb_id']);

if (!$url) {
    $url = G5_URL;
}

// Release social login
if (function_exists('social_member_link_delete')) {
    social_member_link_delete($member['mb_id']);
}

alert('' . $member['mb_nick'] . ' withdrew from membership on ' . date("Y-m-d") . '.', $url);