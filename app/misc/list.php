<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access not allowed

// Category usage
$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
    $is_category = true;
    $category_href = get_pretty_url($bo_table);

    $category_option .= '<li><a href="' . $category_href . '"';
    if ($sca == '') {
        $category_option .= ' id="bo_cate_on"';
    }
    $category_option .= '><span class="sound_only">All</span></a></li>';

    $categories = explode('|', $board['bo_category_list']);
    // The delimiter is ','
    $counter = count($categories); // The delimiter is ','
    for ($i = 0; $i < $counter; $i++) {
        $category = trim($categories[$i]);
        if ($category === '') {
            continue;
        }
        $category_option .= '<li><a href="' . (get_pretty_url($bo_table, '', 'sca=' . urlencode($category))) . '"';
        $category_msg = '';
        if ($category == $sca) { // If this is the currently selected category
            $category_option .= ' id="bo_cate_on"';
            $category_msg = '<span class="sound_only">Opened category </span>';
        }
        $category_option .= '>' . $category_msg . $category . '</a></li>';
    }
}

$sop = strtolower($sop);
if ($sop !== 'and' && $sop !== 'or') {
    $sop = 'and';
}

// If there is a category selection or search term
$stx = trim($stx);
// Initialize variable to distinguish between search and non-search
$is_search_bbs = false;

if ($sca || $stx || $stx === '0') {     // If search
    $is_search_bbs = true;              // Set search distinction variable to true
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop);

    // Get the smallest post number and store in variable (used at the bottom of the page)
    $sql = " select MIN(wr_num) as min_wr_num from {$write_table} ";
    $row = sql_fetch($sql);
    $min_spt = (int)$row['min_wr_num'];

    if (!$spt) {
        $spt = $min_spt;
    }

    $sql_search .= " and (wr_num between {$spt} and ({$spt} + {$config['cf_search_part']})) ";

    // Only get original posts. (To search comment content as well)
    // Code provided by Ral, see http://sir.kr/g5_bug/2922
    $sql = " SELECT COUNT(DISTINCT `wr_parent`) AS `cnt` FROM {$write_table} WHERE {$sql_search} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    /*
    $sql = " select distinct wr_parent from {$write_table} where {$sql_search} ";
    $result = sql_query($sql);
    $total_count = sql_num_rows($result);
    */
} else {
    $sql_search = "";

    $total_count = $board['bo_count_write'];
}

if (G5_IS_MOBILE) {
    $page_rows = $board['bo_mobile_page_rows'];
    $list_page_rows = $board['bo_mobile_page_rows'];
} else {
    $page_rows = $board['bo_page_rows'];
    $list_page_rows = $board['bo_page_rows'];
}

if ($page < 1) {
    $page = 1;
} // If page is not set, set to first page (1)

// Year in 2 digits
$today2 = G5_TIME_YMD;

$list = [];
$i = 0;
$notice_count = 0;
$notice_array = [];

// Notice processing
if (!$is_search_bbs) {
    $arr_notice = explode(',', trim($board['bo_notice']));
    $from_notice_idx = ($page - 1) * $page_rows;
    if ($from_notice_idx < 0) {
        $from_notice_idx = 0;
    }
    $board_notice_count = count($arr_notice);

    for ($k = 0; $k < $board_notice_count; $k++) {
        if (trim($arr_notice[$k]) === '') {
            continue;
        }

        $row = sql_fetch(" select * from {$write_table} where wr_id = '{$arr_notice[$k]}' ");

        if (!isset($row['wr_id']) || !$row['wr_id']) {
            continue;
        }

        $notice_array[] = $row['wr_id'];

        if ($k < $from_notice_idx) {
            continue;
        }

        $list[$i] = get_list($row, $board, $board_skin_url,
            G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);
        $list[$i]['is_notice'] = true;
        $list[$i]['list_content'] = $list[$i]['wr_content'];

        // If post is private, remove content from list
        if (strstr($list[$i]['wr_option'], "secret")) {
            $list[$i]['wr_content'] = '';
        }

        $list[$i]['num'] = 0;
        $i++;
        $notice_count++;

        if ($notice_count >= $list_page_rows) {
            break;
        }
    }
}

$total_page = ceil($total_count / $page_rows);  // Calculate total pages
$from_record = ($page - 1) * $page_rows;        // Calculate starting record

// If there are notices, adjust variables
if (!empty($notice_array)) {
    $from_record -= count($notice_array);

    if ($from_record < 0) {
        $from_record = 0;
    }

    if ($notice_count > 0) {
        $page_rows -= $notice_count;
    }

    if ($page_rows < 0) {
        $page_rows = $list_page_rows;
    }
}

// If user is admin, show checkbox
$is_checkbox = false;
if ($is_member && ($is_admin == 'super' || $group['gr_admin'] == $member['mb_id'] || $board['bo_admin'] == $member['mb_id'])) {
    $is_checkbox = true;
}

// Query string for sorting
$qstr2 = 'bo_table=' . $bo_table . '&amp;sop=' . $sop;

// 0 to prevent division by zero error
$bo_gallery_cols = $board['bo_gallery_cols'] ? $board['bo_gallery_cols'] : 1;
$td_width = (int)(100 / $bo_gallery_cols);

// Sorting
// If index field is not set, do not use for sorting
//if (!$sst || ($sst && !(strstr($sst, 'wr_id') || strstr($sst, "wr_datetime")))) {
if (!$sst) {
    if ($board['bo_sort_field']) {
        $sst = $board['bo_sort_field'];
    } else {
        $sst = "wr_num, wr_reply";
        $sod = "";
    }
} else {
    $board_sort_fields = get_board_sort_fields($board, 1);
    if (!$sod && array_key_exists($sst, $board_sort_fields)) {
        $sst = $board_sort_fields[$sst];
    } else {
        // If field is not a valid sorting field, set to empty string
        // (nasca, 09.06.16)
        // To sort by other fields, add them to the code below
        // $sst = preg_match("/^(wr_subject|wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
        $sst = preg_match("/^(wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
    }
}

if (!$sst) {
    $sst = "wr_num, wr_reply";
}

if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

if ($is_search_bbs) {
    $sql = " select distinct wr_parent from {$write_table} where {$sql_search} {$sql_order} limit {$from_record}, $page_rows ";
} else {
    $sql = " select * from {$write_table} where wr_is_comment = 0 ";
    if (!empty($notice_array)) {
        $sql .= " and wr_id not in (" . implode(', ', $notice_array) . ") ";
    }
    $sql .= " {$sql_order} limit {$from_record}, $page_rows ";
}

// If page has notices and number of notices is less than number of list items
if ($page_rows > 0) {
    $result = sql_query($sql);

    $k = 0;

    while ($row = sql_fetch_array($result)) {
        // If search, get original post
        if ($is_search_bbs) {
            $row = sql_fetch(" select * from {$write_table} where wr_id = '{$row['wr_parent']}' ");
        }

        $list[$i] = get_list($row, $board, $board_skin_url,
            G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);
        if (strstr($sfl, 'subject')) {
            $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
        }
        $list[$i]['is_notice'] = false;
        $list[$i]['list_content'] = $list[$i]['wr_content'];

        // If post is private, remove content from list
        if (strstr($list[$i]['wr_option'], "secret")) {
            $list[$i]['wr_content'] = '';
        }

        $list_num = $total_count - ($page - 1) * $list_page_rows - $notice_count;
        $list[$i]['num'] = $list_num - $k;

        $i++;
        $k++;
    }
}

g5_latest_cache_data($board['bo_table'], $list);

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page,
    get_pretty_url($bo_table, '', $qstr . '&amp;page='));

$list_href = '';
$prev_part_href = '';
$next_part_href = '';
if ($is_search_bbs) {
    $list_href = get_pretty_url($bo_table);

    $patterns = ['#&amp;page=\d*#', '#&amp;spt=[0-9\-]*#'];

    //if ($prev_spt >= $min_spt)
    $prev_spt = $spt - $config['cf_search_part'];
    if (isset($min_spt) && $prev_spt >= $min_spt) {
        $qstr1 = preg_replace($patterns, '', $qstr);
        $prev_part_href = get_pretty_url($bo_table, 0, $qstr1 . '&amp;spt=' . $prev_spt . '&amp;page=1');
        $write_pages = page_insertbefore($write_pages,
            '<a href="' . $prev_part_href . '" class="pg_page pg_search pg_prev">Previous Search</a>');
    }

    $next_spt = $spt + $config['cf_search_part'];
    if ($next_spt < 0) {
        $qstr1 = preg_replace($patterns, '', $qstr);
        $next_part_href = get_pretty_url($bo_table, 0, $qstr1 . '&amp;spt=' . $next_spt . '&amp;page=1');
        $write_pages = page_insertafter($write_pages,
            '<a href="' . $next_part_href . '" class="pg_page pg_search pg_next">Next Search</a>');
    }
}


$write_href = '';
if ($member['mb_level'] >= $board['bo_write_level']) {
    $write_href = short_url_clean(G5_BBS_URL . '/write.php?bo_table=' . $bo_table);
}

$nobr_begin = $nobr_end = "";
if (preg_match("/gecko|firefox/i", $_SERVER['HTTP_USER_AGENT'])) {
    $nobr_begin = '<nobr>';
    $nobr_end = '</nobr>';
}

// RSS view is only available if enabled
$rss_href = '';
if ($board['bo_use_rss_view']) {
    $rss_href = G5_BBS_URL . '/rss.php?bo_table=' . $bo_table;
}

$stx = get_text(stripslashes($stx));
include_once($board_skin_path . '/list.skin.php');