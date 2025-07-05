<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access not allowed

// Fix for error when leaving a comment on a searched post after searching in the board
$sop = strtolower($sop);
if ($sop !== 'and' && $sop !== 'or') {
    $sop = 'and';
}

@include_once($board_skin_path . '/view.head.skin.php');

$sql_search = "";
// If searching
if ($sca || $stx || $stx === '0') {
    // Get where clause
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop);
    $search_href = get_pretty_url($bo_table, '', '&amp;page=' . $page . $qstr);
    $list_href = get_pretty_url($bo_table);
} else {
    $search_href = '';
    $list_href = get_pretty_url($bo_table, '', $qstr);
}

if (!$board['bo_use_list_view']) {
    if ($sql_search) {
        $sql_search = " and " . $sql_search;
    }

    // Get previous post
    $sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num = '{$write['wr_num']}' and wr_reply < '{$write['wr_reply']}' {$sql_search} order by wr_num desc, wr_reply desc limit 1 ";
    $prev = sql_fetch($sql);
    // If value cannot be obtained with the above query
    if (!(isset($prev['wr_id']) && $prev['wr_id'])) {
        $sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num < '{$write['wr_num']}' {$sql_search} order by wr_num desc, wr_reply desc limit 1 ";
        $prev = sql_fetch($sql);
    }

    // Get next post
    $sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num = '{$write['wr_num']}' and wr_reply > '{$write['wr_reply']}' {$sql_search} order by wr_num, wr_reply limit 1 ";
    $next = sql_fetch($sql);
    // If value cannot be obtained with the above query
    if (!(isset($next['wr_id']) && $next['wr_id'])) {
        $sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num > '{$write['wr_num']}' {$sql_search} order by wr_num, wr_reply limit 1 ";
        $next = sql_fetch($sql);
    }
}

// Previous post link
$prev_href = '';
if (isset($prev['wr_id']) && $prev['wr_id']) {
    $prev_wr_subject = get_text(cut_str($prev['wr_subject'], 255));
    $prev_href = get_pretty_url($bo_table, $prev['wr_id'], $qstr);
    $prev_wr_date = $prev['wr_datetime'];
}

// Next post link
$next_href = '';
if (isset($next['wr_id']) && $next['wr_id']) {
    $next_wr_subject = get_text(cut_str($next['wr_subject'], 255));
    $next_href = get_pretty_url($bo_table, $next['wr_id'], $qstr);
    $next_wr_date = $next['wr_datetime'];
}

// Write link
$write_href = '';
if ($member['mb_level'] >= $board['bo_write_level']) {
    $write_href = short_url_clean(G5_BBS_URL . '/write.php?bo_table=' . $bo_table);
}

// Reply link
$reply_href = '';
if ($member['mb_level'] >= $board['bo_reply_level']) {
    $reply_href = short_url_clean(G5_BBS_URL . '/write.php?w=r&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . $qstr);
}

// Edit, delete link
$update_href = $delete_href = '';
// If logged in and own post, or if admin, can edit and delete without password
if (($member['mb_id'] && ($member['mb_id'] === $write['mb_id'])) || $is_admin) {
    $update_href = short_url_clean(G5_BBS_URL . '/write.php?w=u&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;page=' . $page . $qstr);
    set_session('ss_delete_token', $token = uniqid(time()));
    $delete_href = G5_BBS_URL . '/delete.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;token=' . $token . '&amp;page=' . $page . urldecode($qstr);
} elseif (!$write['mb_id']) {
    // If not a member's post
    $update_href = G5_BBS_URL . '/password.php?w=u&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;page=' . $page . $qstr;
    $delete_href = G5_BBS_URL . '/password.php?w=d&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;page=' . $page . $qstr;
}

// If super admin or group admin, can copy and move posts
$copy_href = $move_href = '';
if ($write['wr_reply'] == '' && ($is_admin == 'super' || $is_admin == 'group')) {
    $copy_href = G5_BBS_URL . '/move.php?sw=copy&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;page=' . $page . $qstr;
    $move_href = G5_BBS_URL . '/move.php?sw=move&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;page=' . $page . $qstr;
}

$scrap_href = '';
$good_href = '';
$nogood_href = '';
if ($is_member) {
    // Scrap link
    $scrap_href = G5_BBS_URL . '/scrap_popin.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id;

    // Recommend link
    if ($board['bo_use_good']) {
        $good_href = G5_BBS_URL . '/good.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;good=good';
    }

    // Not recommend link
    if ($board['bo_use_nogood']) {
        $nogood_href = G5_BBS_URL . '/good.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . '&amp;good=nogood';
    }
}

$view = get_view($write, $board, $board_skin_path);

if (strstr($sfl, 'subject')) {
    $view['subject'] = search_font($stx, $view['subject']);
}

$html = 0;
if (strstr($view['wr_option'], 'html1')) {
    $html = 1;
} elseif (strstr($view['wr_option'], 'html2')) {
    $html = 2;
}

$view['content'] = conv_content($view['wr_content'], $html);
if (strstr($sfl, 'content')) {
    $view['content'] = search_font($stx, $view['content']);
}

//$view['rich_content'] = preg_replace("/{이미지\:([0-9]+)[:]?([^}]*)}/ie", "view_image(\$view, '\\1', '\\2')", $view['content']);
function conv_rich_content($matches)
{
    global $view;
    return view_image($view, $matches[1], $matches[2]);
}

$view['rich_content'] = preg_replace_callback("/{이미지\:([0-9]+)[:]?([^}]*)}/i", "conv_rich_content", $view['content']);

$is_signature = false;
$signature = '';
if ($board['bo_use_signature'] && $view['mb_id']) {
    $is_signature = true;
    $mb = get_member($view['mb_id']);
    $signature = $mb['mb_signature'];

    $signature = conv_content($signature, 1);
}

include_once($board_skin_path . '/view.skin.php');

@include_once($board_skin_path . '/view.tail.skin.php');