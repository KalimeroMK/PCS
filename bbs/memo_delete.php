<?php

include_once(__DIR__ . '/../common.php');


if (!$is_member) {
    alert('Only members can use this feature.');
}

$delete_token = get_session('ss_memo_delete_token');
set_session('ss_memo_delete_token', '');

if (!($token && $delete_token == $token)) {
    alert('Token error, deletion failed.');
}

$me_id = isset($_REQUEST['me_id']) ? (int)$_REQUEST['me_id'] : 0;

$sql = " select * from {$g5['memo_table']} where me_id = '{$me_id}' ";
$row = sql_fetch($sql);

$sql = " delete from {$g5['memo_table']}
            where me_id = '{$me_id}'
            and (me_recv_mb_id = '{$member['mb_id']}' or me_send_mb_id = '{$member['mb_id']}') ";
sql_query($sql);

if (!$row['me_read_datetime'][0]) // If memo was not read yet
{
    $sql = " update {$g5['member_table']}
                set mb_memo_call = ''
                where mb_id = '{$row['me_recv_mb_id']}'
                and mb_memo_call = '{$row['me_send_mb_id']}' ";
    sql_query($sql);

    $sql = " update `{$g5['member_table']}` set mb_memo_cnt = '".get_memo_not_read($member['mb_id'])."' where mb_id = '{$member['mb_id']}' ";
    sql_query($sql);
}

run_event('memo_delete', $me_id, $row);

goto_url('./memo.php?kind='.$kind);