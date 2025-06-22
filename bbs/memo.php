<?php

include_once(__DIR__ . '/../common.php');

// Only members can use this feature.
if ($is_guest) {
    alert_close('Only members can use this feature.');
}

set_session('ss_memo_delete_token', $token = uniqid(time()));

$g5['title'] = 'My Memo Box';
include_once(G5_PATH.'/head.sub.php');

$kind = isset($_GET['kind']) ? clean_xss_tags($_GET['kind'], 0, 1) : 'recv';

if ($kind == 'recv') {
    $unkind = 'send';
} elseif ($kind == 'send') {
    $unkind = 'recv';
} else {
    alert("The value of kind variable is invalid.");
}

if ($page < 1) {
    $page = 1;
} // If page is not set, set to first page (1)

run_event('memo_list', $kind, $unkind, $page);

$sql = " select count(*) as cnt from {$g5['memo_table']} where me_{$kind}_mb_id = '{$member['mb_id']}' and me_type = '$kind' ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$total_page = ceil($total_count / $config['cf_page_rows']);  // Calculate total pages
$from_record = ((int)$page - 1) * $config['cf_page_rows'];   // Calculate starting record

if ($kind == 'recv') {
    $kind_title = 'Received';
    $recv_img = 'on';
    $send_img = 'off';
} else {
    $kind_title = 'Sent';
    $recv_img = 'off';
    $send_img = 'on';
}

$list = [];

$sql = " select a.*, b.mb_id, b.mb_nick, b.mb_email, b.mb_homepage
            from {$g5['memo_table']} a
            left join {$g5['member_table']} b on (a.me_{$unkind}_mb_id = b.mb_id)
            where a.me_{$kind}_mb_id = '{$member['mb_id']}' and a.me_type = '$kind'
            order by a.me_id desc limit $from_record, {$config['cf_page_rows']} ";

$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list[$i] = $row;

    $mb_id = $row["me_{$unkind}_mb_id"];

    $mb_nick = $row['mb_nick'] ? $row['mb_nick'] : 'No information';

    $name = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

    if (substr($row['me_read_datetime'], 0, 1) == 0) {
        $read_datetime = 'Not read yet';
    } else {
        $read_datetime = substr($row['me_read_datetime'], 2, 14);
    }

    $send_datetime = substr($row['me_send_datetime'], 2, 14);

    $list[$i]['mb_id'] = $mb_id;
    $list[$i]['name'] = $name;
    $list[$i]['send_datetime'] = $send_datetime;
    $list[$i]['read_datetime'] = $read_datetime;
    $list[$i]['view_href'] = './memo_view.php?me_id='.$row['me_id'].'&amp;kind='.$kind.'&amp;page='.$page;
    $list[$i]['del_href'] = './memo_delete.php?me_id='.$row['me_id'].'&amp;token='.$token.'&amp;kind='.$kind;
}

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page,
    "./memo.php?kind=$kind".$qstr."&amp;page=");

include_once($member_skin_path.'/memo.skin.php');

include_once(G5_PATH.'/tail.sub.php');