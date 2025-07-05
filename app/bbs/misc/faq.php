<?php

include_once(__DIR__ . '/../common.php');

// Check if $g5['faq_table'] and $g5['faq_master_table'] array variables exist in dbconfig file
if (!isset($g5['faq_table']) || !isset($g5['faq_master_table'])) {
    die('<meta charset="utf-8">Please check Board Management->FAQ Management in admin mode first.');
}

// FAQ MASTER
$faq_master_list = [];
$sql = " select * from {$g5['faq_master_table']} order by fm_order,fm_id ";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {
    $key = $row['fm_id'];
    if (!isset($fm_id)) {
        $fm_id = $key;
    }
    $faq_master_list[$key] = $row;
}

$fm = [];
if (isset($fm_id) && $fm_id) {
    $fm_id = (int)$fm_id;
    $qstr .= '&amp;fm_id='.$fm_id; // masterfaq key_id

    $fm = $faq_master_list[$fm_id];
}

if (!(isset($fm['fm_id']) && $fm['fm_id'])) {
    alert('The registered content does not exist.');
}

$g5['title'] = $fm['fm_subject'];

$skin_file = $faq_skin_path.'/list.skin.php';

include_once(__DIR__ . '/../head.php');
if (is_file($skin_file)) {
    $admin_href = '';
    $himg_src = '';
    $timg_src = '';
    if ($is_admin) {
        $admin_href = G5_ADMIN_URL.'/faqmasterform.php?w=u&amp;fm_id='.$fm_id;
    }

    if (!G5_IS_MOBILE) {
        $himg = G5_DATA_PATH.'/faq/'.$fm_id.'_h';
        if (is_file($himg)) {
            $himg_src = G5_DATA_URL.'/faq/'.$fm_id.'_h';
        }

        $timg = G5_DATA_PATH.'/faq/'.$fm_id.'_t';
        if (is_file($timg)) {
            $timg_src = G5_DATA_URL.'/faq/'.$fm_id.'_t';
        }
    }

    $category_href = G5_BBS_URL.'/faq.php';
    $category_stx = '';
    $faq_list = [];

    $stx = trim($stx);
    $sql_search = '';

    if ($stx !== '' && $stx !== '0') {
        $sql_search = " and ( INSTR(fa_subject, '$stx') > 0 or INSTR(fa_content, '$stx') > 0 ) ";
    }

    if ($page < 1) {
        $page = 1;
    } // If there are no pages, set to first page (page 1)

    $page_rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_page_rows'];

    $sql = " select count(*) as cnt
                from {$g5['faq_table']}
                where fm_id = '$fm_id'
                  $sql_search ";
    $total = sql_fetch($sql);
    $total_count = $total['cnt'];

    $total_page = ceil($total_count / $page_rows);  // Calculate total pages
    $from_record = ($page - 1) * $page_rows;        // Get start row

    $sql = " select *
                from {$g5['faq_table']}
                where fm_id = '$fm_id'
                  $sql_search
                order by fa_order , fa_id
                limit $from_record, $page_rows ";
    $result = sql_query($sql);
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $faq_list[] = $row;
        if ($stx !== '' && $stx !== '0') {
            $faq_list[$i]['fa_subject'] = search_font($stx, conv_content($faq_list[$i]['fa_subject'], 1));
            $faq_list[$i]['fa_content'] = search_font($stx, conv_content($faq_list[$i]['fa_content'], 1));
        }
    }
    include_once($skin_file);
} else {
    echo '<p>'.str_replace(G5_PATH.'/', '', $skin_file).' does not exist.</p>';
}

include_once(__DIR__ . '/tail.php');