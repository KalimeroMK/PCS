<?php

include_once(__DIR__ . '/../common.php');

// If the user is a guest, display a login message
if ($is_guest) {
    alert('If you are a member, please log in to use this service.', './login.php?url=' . urlencode(G5_BBS_URL . '/qalist.php'));
}

$qaconfig = get_qa_config();

$token = '';
if ($is_admin) {
    $token = _token();
    set_session('ss_qa_delete_token', $token);
}

$g5['title'] = $qaconfig['qa_title'];
include_once(__DIR__ . '/qahead.php');

$skin_file = $qa_skin_path . '/list.skin.php';
$is_auth = (bool)$is_admin;

$category_option = '';

// If category is enabled
if ($qaconfig['qa_category']) {
    $category_href = G5_BBS_URL . '/qalist.php';

    $category_option .= '<li><a href="' . $category_href . '"';
    if ($sca == '') {
        $category_option .= ' id="bo_cate_on"';
    }
    $category_option .= '>All</a></li>';

    $categories = explode('|', $qaconfig['qa_category']);
    // The delimiter is |
    $counter = count($categories); // The delimiter is |
    for ($i = 0; $i < $counter; $i++) {
        $category = trim($categories[$i]);
        if ($category === '') {
            continue;
        }
        $category_msg = '';
        $category_option .= '<li><a href="' . ($category_href . "?sca=" . urlencode($category)) . '"';
        if ($category == $sca) { // If it is the currently selected category
            $category_option .= ' id="bo_cate_on"';
            $category_msg = '<span class="sound_only">Open category </span>';
        }
        $category_option .= '>' . $category_msg . $category . '</a></li>';
    }
}

if (is_file($skin_file)) {
    $sql_common = " from {$g5['qa_content_table']} ";
    $sql_search = " where qa_type = '0' ";

    if (!$is_admin) {
        $sql_search .= " and mb_id = '{$member['mb_id']}' ";
    }

    if ($sca) {
        if (preg_match("/[a-zA-Z]/", $sca)) {
            $sql_search .= " and INSTR(LOWER(qa_category), LOWER('$sca')) > 0 ";
        } else {
            $sql_search .= " and INSTR(qa_category, '$sca') > 0 ";
        }
    }

    $stx = trim($stx);
    if ($stx !== '' && $stx !== '0') {
        $sfl = trim($sfl);
        if ($sfl !== '' && $sfl !== '0') {
            switch ($sfl) {
                case "qa_subject" :
                case "qa_content" :
                case "qa_name" :
                case "mb_id" :
                    break;
                default :
                    $sfl = "qa_subject";
            }
        } else {
            $sfl = "qa_subject";
        }
        $sql_search .= " and (`{$sfl}` like '%{$stx}%') ";
    }

    $sql_order = " order by qa_num ";

    $sql = " select count(*) as cnt 
                $sql_common
                $sql_search ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $page_rows = G5_IS_MOBILE ? $qaconfig['qa_mobile_page_rows'] : $qaconfig['qa_page_rows'];
    $total_page = ceil($total_count / $page_rows);  // Calculate total pages
    if ($page < 1) {
        $page = 1;
    }                                               // If there is no page, it is the first page (page 1)
    $from_record = ($page - 1) * $page_rows;        // Get the starting row

    $sql = " select *
                $sql_common
                $sql_search
                $sql_order
                limit $from_record, $page_rows ";
    $result = sql_query($sql);

    $list = [];
    $num = $total_count - ($page - 1) * $page_rows;
    $subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $list[$i] = $row;

        $list[$i]['category'] = get_text($row['qa_category']);
        $list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
        if ($stx !== '' && $stx !== '0') {
            $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
        }

        $list[$i]['view_href'] = G5_BBS_URL . '/qaview.php?qa_id=' . $row['qa_id'] . $qstr;

        $list[$i]['icon_file'] = '';
        if (trim($row['qa_file1']) || trim($row['qa_file2'])) {
            $list[$i]['icon_file'] = '<img src="' . $qa_skin_url . '/img/icon_file.gif">';
        }

        $list[$i]['name'] = get_text($row['qa_name']);
        // When applying side view
        //$list[$i]['name'] = get_sideview($row['mb_id'], $row['qa_name']);
        $list[$i]['date'] = substr($row['qa_datetime'], 2, 8);

        $list[$i]['num'] = $num - $i;
    }

    $is_checkbox = false;
    $admin_href = '';
    if ($is_admin) {
        $is_checkbox = true;
        $admin_href = G5_ADMIN_URL . '/qa_config.php';
    }

    $list_href = G5_BBS_URL . '/qalist.php';
    $write_href = G5_BBS_URL . '/qawrite.php';

    $list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?',
        get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page,
            './qalist.php' . $qstr . '&amp;page='));

    $stx = get_text(stripslashes($stx));
    include_once($skin_file);
} else {
    echo '<div>' . str_replace(G5_PATH . '/', '', $skin_file) . ' does not exist.</div>';
}

include_once(__DIR__ . '/qatail.php');