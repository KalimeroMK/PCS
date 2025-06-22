<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access not allowed

// Only super admin can execute
if ($config['cf_admin'] != $member['mb_id'] || $is_admin != 'super') {
    return;
}

// Compare with execution date
if (isset($config['cf_optimize_date']) && $config['cf_optimize_date'] >= G5_TIME_YMD) {
    return;
}

// Delete old access log entries based on settings
if ($config['cf_visit_del'] > 0) {
    $tmp_before_date = date("Y-m-d", G5_SERVER_TIME - ($config['cf_visit_del'] * 86400));
    $sql = " delete from {$g5['visit_table']} where vi_date < '$tmp_before_date' ";
    sql_query($sql);
    sql_query(" OPTIMIZE TABLE `{$g5['visit_table']}`, `{$g5['visit_sum_table']}` ");
}

// Delete old popular search terms based on settings
if ($config['cf_popular_del'] > 0) {
    $tmp_before_date = date("Y-m-d", G5_SERVER_TIME - ($config['cf_popular_del'] * 86400));
    $sql = " delete from {$g5['popular_table']} where pp_date < '$tmp_before_date' ";
    sql_query($sql);
    sql_query(" OPTIMIZE TABLE `{$g5['popular_table']}` ");
}

// Delete old recent posts based on settings
if ($config['cf_new_del'] > 0) {
    $sql = " delete from {$g5['board_new_table']} where (TO_DAYS('".G5_TIME_YMDHIS."') - TO_DAYS(bn_datetime)) > '{$config['cf_new_del']}' ";
    sql_query($sql);
    sql_query(" OPTIMIZE TABLE `{$g5['board_new_table']}` ");
}

// Delete old memos based on settings
if ($config['cf_memo_del'] > 0) {
    $sql = " delete from {$g5['memo_table']} where (TO_DAYS('".G5_TIME_YMDHIS."') - TO_DAYS(me_send_datetime)) > '{$config['cf_memo_del']}' ";
    sql_query($sql);
    sql_query(" OPTIMIZE TABLE `{$g5['memo_table']}` ");
}

// Automatically delete withdrawn members
if ($config['cf_leave_day'] > 0) {
    $sql = " select mb_id from {$g5['member_table']}
                where (TO_DAYS('".G5_TIME_YMDHIS."') - TO_DAYS(mb_leave_date)) > '{$config['cf_leave_day']}'
                  and mb_memo not regexp '^[0-9]{8}.*deleted' ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) {
        // Delete member data
        member_delete($row['mb_id']);
    }
}

// Delete audio captcha files
$captcha_mp3 = glob(G5_DATA_PATH.'/cache/kcaptcha-*.mp3');
if ($captcha_mp3 && is_array($captcha_mp3)) {
    foreach ($captcha_mp3 as $file) {
        if (filemtime($file) + 86400 < G5_SERVER_TIME) {
            @unlink($file);
        }
    }
}

// Log execution date
if (isset($config['cf_optimize_date'])) {
    sql_query(" update {$g5['config_table']} set cf_optimize_date = '".G5_TIME_YMD."' ");
}