<?php

include_once(__DIR__ . '/../common.php');


if ($is_guest) {
    alert_close('Only members can view this page.');
}

$g5['title'] = get_text($member['mb_nick']) . "'s Point History";
include_once(G5_PATH . '/head.sub.php');

$list = [];

$sql_common = " from {$g5['point_table']} where mb_id = '" . escape_trim($member['mb_id']) . "' ";
$sql_order = " order by po_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page = ceil($total_count / $rows);  // Calculate total pages
if ($page < 1) {
    $page = 1;
}                                          // If no page, set to first page (page 1)
$from_record = ($page - 1) * $rows;        // Calculate starting record

$sql = " select *
            {$sql_common}
            {$sql_order}
            limit {$from_record}, {$rows} ";

$result = sql_query($sql);

for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list[] = $row;
}

include_once($member_skin_path . '/point.skin.php');

include_once(G5_PATH . '/tail.sub.php');