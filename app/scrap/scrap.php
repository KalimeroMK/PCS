<?php

include_once(__DIR__ . '/../common.php');


if (!$is_member) {
    alert_close('Only members can view.');
}

$g5['title'] = get_text($member['mb_nick']) . '\'s Scrap';
include_once(G5_PATH . '/head.sub.php');

$sql_common = " from {$g5['scrap_table']} where mb_id = '{$member['mb_id']}' ";
$sql_order = " order by ms_id desc ";

$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page = ceil($total_count / $rows);  // Calculate total pages
if ($page < 1) {
    $page = 1;
}                                          // If there are no pages, set to first page (page 1)
$from_record = ($page - 1) * $rows;        // Get the starting record

$list = [];

$sql = " select *
            $sql_common
            $sql_order
            limit $from_record, $rows ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list[$i] = $row;

    // Sequential number (order)
    $num = $total_count - ($page - 1) * $rows - $i;

    // Board title
    $sql2 = " select bo_subject from {$g5['board_table']} where bo_table = '{$row['bo_table']}' ";
    $row2 = sql_fetch($sql2);
    if (!$row2['bo_subject']) {
        $row2['bo_subject'] = '[Board not found]';
    }

    // Post title
    $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];
    $sql3 = " select wr_subject from $tmp_write_table where wr_id = '{$row['wr_id']}' ";
    $row3 = sql_fetch($sql3, false);
    $subject = get_text(cut_str($row3['wr_subject'], 100));
    if (!$row3['wr_subject']) {
        $row3['wr_subject'] = '[Post not found]';
    }

    $list[$i]['num'] = $num;
    $list[$i]['opener_href'] = get_pretty_url($row['bo_table']);
    $list[$i]['opener_href_wr_id'] = get_pretty_url($row['bo_table'], $row['wr_id']);
    $list[$i]['bo_subject'] = $row2['bo_subject'];
    $list[$i]['subject'] = $subject;
    $list[$i]['del_href'] = './scrap_delete.php?ms_id=' . $row['ms_id'] . '&amp;page=' . $page;
}

include_once($member_skin_path . '/scrap.skin.php');

include_once(G5_PATH . '/tail.sub.php');