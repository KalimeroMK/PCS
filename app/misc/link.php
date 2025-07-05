<?php

include_once(__DIR__ . '/../common.php');

/**
 * Set the HTML title
 */
$html_title = 'Link &gt; ' . conv_subject($write['wr_subject'], 255);

$no = isset($_REQUEST['no']) ? preg_replace('/[^0-9]/i', '', $_REQUEST['no']) : '';

if (!($bo_table && $wr_id && $no)) {
    alert_close('The value was not passed correctly.');
}

// Prevent SQL Injection
$row = sql_fetch(" select count(*) as cnt from {$g5['write_prefix']}{$bo_table} ", false);
if (!$row['cnt']) {
    alert_close('The post does not exist.');
}

if (!$write['wr_link' . $no]) {
    alert_close('Link does not exist.');
}

$ss_name = 'ss_link_' . $bo_table . '_' . $wr_id . '_' . $no;
if (empty($_SESSION[$ss_name])) {
    $sql = " update {$g5['write_prefix']}{$bo_table} set wr_link{$no}_hit = wr_link{$no}_hit + 1 where wr_id = '{$wr_id}' ";
    sql_query($sql);

    set_session($ss_name, true);
}

goto_url(set_http($write['wr_link' . $no]));