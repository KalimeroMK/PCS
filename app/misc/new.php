<?php

include_once(__DIR__ . '/../common.php');

// Set page title
$g5['title'] = 'New Posts';
include_once(__DIR__ . '/../header.php');
$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b, {$g5['group_table']} c where a.bo_table = b.bo_table and b.gr_id = c.gr_id and b.bo_use_search = 1 ";

$gr_id = isset($_GET['gr_id']) ? substr(preg_replace('#[^a-z0-9_]#i', '', $_GET['gr_id']), 0, 10) : '';
if ($gr_id !== '' && $gr_id !== '0') {
    $sql_common .= " and b.gr_id = '$gr_id' ";
}

$view = isset($_GET['view']) ? $_GET['view'] : "";

if ($view == "w") {
    $sql_common .= " and a.wr_id = a.wr_parent ";
} elseif ($view == "c") {
    $sql_common .= " and a.wr_id <> a.wr_parent ";
} else {
    $view = '';
}

$mb_id = isset($_GET['mb_id']) ? ($_GET['mb_id']) : '';
$mb_id = substr(preg_replace('#[^a-z0-9_]#i', '', $mb_id), 0, 20);

if ($mb_id !== '' && $mb_id !== '0') {
    $sql_common .= " and a.mb_id = '{$mb_id}' ";
}
$sql_order = " order by a.bn_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_new_rows'];
$total_page = ceil($total_count / $rows);  // Calculate total pages
if ($page < 1) {
    $page = 1;
}                                          // If page not set, set to first page (1)
$from_record = ($page - 1) * $rows;        // Calculate starting record

$group_select = '<label for="gr_id" class="sound_only">Group</label><select name="gr_id" id="gr_id"><option value="">All Groups';
$sql = " select gr_id, gr_subject from {$g5['group_table']} order by gr_id ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $group_select .= "<option value=\"" . $row['gr_id'] . "\">" . $row['gr_subject'];
}
$group_select .= '</select>';

$list = [];
$sql = " select a.*, b.bo_subject, b.bo_mobile_subject, c.gr_subject, c.gr_id {$sql_common} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];

    if ($row['wr_id'] == $row['wr_parent']) {
        // Original post
        $comment = "";
        $comment_link = "";
        $row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");
        $list[$i] = $row2;

        $name = get_sideview($row2['mb_id'], get_text(cut_str($row2['wr_name'], $config['cf_cut_name'])),
            $row2['wr_email'], $row2['wr_homepage']);
        // If today, show as time
        $datetime = substr($row2['wr_datetime'], 0, 10);
        $datetime2 = $row2['wr_datetime'];
        $datetime2 = $datetime == G5_TIME_YMD ? substr($datetime2, 11, 5) : substr($datetime2, 5, 5);
    } else {
        // Comment
        $comment = '[Comment] ';
        $comment_link = '#c_' . $row['wr_id'];
        $row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ");
        $row3 = sql_fetch(" select mb_id, wr_name, wr_email, wr_homepage, wr_datetime from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");
        $list[$i] = $row2;
        $list[$i]['wr_id'] = $row['wr_id'];
        $list[$i]['mb_id'] = $row3['mb_id'];
        $list[$i]['wr_name'] = $row3['wr_name'];
        $list[$i]['wr_email'] = $row3['wr_email'];
        $list[$i]['wr_homepage'] = $row3['wr_homepage'];

        $name = get_sideview($row3['mb_id'], get_text(cut_str($row3['wr_name'], $config['cf_cut_name'])),
            $row3['wr_email'], $row3['wr_homepage']);
        // If today, show as time
        $datetime = substr($row3['wr_datetime'], 0, 10);
        $datetime2 = $row3['wr_datetime'];
        $datetime2 = $datetime == G5_TIME_YMD ? substr($datetime2, 11, 5) : substr($datetime2, 5, 5);
    }

    $list[$i]['gr_id'] = $row['gr_id'];
    $list[$i]['bo_table'] = $row['bo_table'];
    $list[$i]['name'] = $name;
    $list[$i]['comment'] = $comment;
    $list[$i]['href'] = get_pretty_url($row['bo_table'], $row2['wr_id'], $comment_link);
    $list[$i]['datetime'] = $datetime;
    $list[$i]['datetime2'] = $datetime2;

    $list[$i]['gr_subject'] = $row['gr_subject'];
    $list[$i]['bo_subject'] = ((G5_IS_MOBILE && $row['bo_mobile_subject']) ? $row['bo_mobile_subject'] : $row['bo_subject']);
    $list[$i]['wr_subject'] = $row2['wr_subject'];
}

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page,
    "?gr_id=$gr_id&amp;view=$view&amp;mb_id=$mb_id&amp;page=");

include_once($new_skin_path . '/new.skin.php');

include_once(__DIR__ . '/footer.php');