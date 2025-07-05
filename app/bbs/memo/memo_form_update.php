<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_guest) {
    alert('Only members can use this feature.');
}

if (!chk_captcha()) {
    alert('The anti-bot number is incorrect.');
}

$recv_list = isset($_POST['me_recv_mb_id']) ? explode(',', trim($_POST['me_recv_mb_id'])) : [];
$str_nick_list = '';
$msg = '';
$error_list = [];
$member_list = ['id' => [], 'nick' => []];

run_event('memo_form_update_before', $recv_list);
$counter = count($recv_list);

for ($i = 0; $i < $counter; $i++) {
    $row = sql_fetch(" select mb_id, mb_nick, mb_open, mb_leave_date, mb_intercept_date from {$g5['member_table']} where mb_id = '{$recv_list[$i]}' ");
    if ($row) {
        if ($is_admin || ($row['mb_open'] && (!$row['mb_leave_date'] && !$row['mb_intercept_date']))) {
            $member_list['id'][] = $row['mb_id'];
            $member_list['nick'][] = $row['mb_nick'];
        } else {
            $error_list[] = $recv_list[$i];
        }
    }
    /*
    // If the user is not an administrator and
    // the member is not registered or does not disclose information or has withdrawn or is blocked, sending a memo is an error
    if ((!$row['mb_id'] || !$row['mb_open'] || $row['mb_leave_date'] || $row['mb_intercept_date']) && !$is_admin) {
        $error_list[]   = $recv_list[$i];
    } else {
        $member_list['id'][]   = $row['mb_id'];
        $member_list['nick'][] = $row['mb_nick'];
    }
    */
}

$error_msg = implode(",", $error_list);

if ($error_msg && !$is_admin) {
    alert("The member ID '{$error_msg}' does not exist (or is not public), is withdrawn, or is blocked.\nMemo was not sent.");
}

if (!count($member_list['id'])) {
    alert('The specified member does not exist.');
}

if (!$is_admin && count($member_list['id'])) {
    $point = (int)$config['cf_memo_send_point'] * count($member_list['id']);
    if ($point !== 0 && $member['mb_point'] - $point < 0) {
        alert('You do not have enough points ('.number_format($member['mb_point']).' points) to send a memo.');
    }
}
$counter = count($member_list['id']);

for ($i = 0; $i < $counter; $i++) {
    $tmp_row = sql_fetch(" select max(me_id) as max_me_id from {$g5['memo_table']} ");
    $me_id = $tmp_row['max_me_id'] + 1;

    $recv_mb_id = $member_list['id'][$i];
    $recv_mb_nick = get_text($member_list['nick'][$i]);

    // Insert memo for recipient
    $sql = " insert into {$g5['memo_table']} ( me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_read_datetime, me_type, me_send_ip ) values ( '$recv_mb_id', '{$member['mb_id']}', '".G5_TIME_YMDHIS."', '{$_POST['me_memo']}', '0000-00-00 00:00:00' , 'recv', '{$_SERVER['REMOTE_ADDR']}' ) ";

    sql_query($sql);

    if ($me_id = sql_insert_id()) {
        // Insert memo for sender
        $sql = " insert into {$g5['memo_table']} ( me_recv_mb_id, me_send_mb_id, me_send_datetime, me_memo, me_read_datetime, me_send_id, me_type , me_send_ip ) values ( '$recv_mb_id', '{$member['mb_id']}', '".G5_TIME_YMDHIS."', '{$_POST['me_memo']}', '0000-00-00 00:00:00', '$me_id', 'send', '{$_SERVER['REMOTE_ADDR']}' ) ";
        sql_query($sql);

        $member_list['me_id'][$i] = $me_id;
    }

    // Real-time memo notification
    $sql = " update {$g5['member_table']} set mb_memo_call = '{$member['mb_id']}', mb_memo_cnt = '".get_memo_not_read($recv_mb_id)."' where mb_id = '$recv_mb_id' ";
    sql_query($sql);

    if (!$is_admin) {
        insert_point($member['mb_id'], (int)$config['cf_memo_send_point'] * (-1),
            $recv_mb_nick.'('.$recv_mb_id.') sent a memo', '@memo', $recv_mb_id, $me_id);
    }
}

if ($member_list !== []) {
    $redirect_url = G5_HTTP_BBS_URL."/memo.php?kind=send";
    $str_nick_list = implode(',', $member_list['nick']);

    run_event('memo_form_update_after', $member_list, $str_nick_list, $redirect_url, $_POST['me_memo']);

    alert($str_nick_list." sent a memo successfully.", $redirect_url, false);
} else {
    $redirect_url = G5_HTTP_BBS_URL."/memo_form.php";

    run_event('memo_form_update_failed', $member_list, $redirect_url, $_POST['me_memo']);

    alert("Member ID error occurred.", $redirect_url, false);
}
