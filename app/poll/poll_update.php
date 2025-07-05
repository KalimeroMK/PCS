<?php

include_once(__DIR__ . '/../common.php');


$po_id = isset($_POST['po_id']) ? preg_replace('/[^0-9]/', '', $_POST['po_id']) : 0;

$po = sql_fetch(" select * from {$g5['poll_table']} where po_id = '{$_POST['po_id']}' ");
if (!(isset($po['po_id']) && $po['po_id'])) {
    alert('The value of po_id was not passed correctly.');
}

if ($member['mb_level'] < $po['po_level']) {
    alert_close('Only members with level ' . $po['po_level'] . ' or higher can participate in the poll.');
}

$gb_poll = isset($_POST['gb_poll']) ? preg_replace('/[^0-9]/', '', $_POST['gb_poll']) : 0;
if (!$gb_poll) {
    alert_close('Please select an item.');
}

$search_mb_id = false;
$search_ip = false;

if ($is_member) {
    // Search through member IDs who have already voted
    $ids = explode(',', trim($po['mb_ids']));
    $counter = count($ids);
    for ($i = 0; $i < $counter; $i++) {
        if ($member['mb_id'] == trim($ids[$i])) {
            $search_mb_id = true;
            break;
        }
    }
} else {
    // Search through IPs that have already voted
    $ips = explode(',', trim($po['po_ips']));
    $counter = count($ips);
    for ($i = 0; $i < $counter; $i++) {
        if ($_SERVER['REMOTE_ADDR'] == trim($ips[$i])) {
            $search_ip = true;
            break;
        }
    }
}

$post_skin_dir = isset($_POST['skin_dir']) ? clean_xss_tags($_POST['skin_dir'], 1, 1) : '';
$result_url = G5_BBS_URL . "/poll_result.php?po_id=$po_id&skin_dir={$post_skin_dir}";

// If not, increment the selected poll item by 1 and save ip, id
if (!$search_ip && !$search_mb_id) {
    $po_ips = $po['po_ips'] . $_SERVER['REMOTE_ADDR'] . ",";
    $mb_ids = $po['mb_ids'];
    if ($is_member) { // If member, add only id
        $mb_ids .= $member['mb_id'] . ',';
        $sql = " update {$g5['poll_table']} set po_cnt{$gb_poll} = po_cnt{$gb_poll} + 1, mb_ids = '$mb_ids' where po_id = '$po_id' ";
    } else {
        $sql = " update {$g5['poll_table']} set po_cnt{$gb_poll} = po_cnt{$gb_poll} + 1, po_ips = '$po_ips' where po_id = '$po_id' ";
    }

    sql_query($sql);
} else {
    alert(addcslashes($po['po_subject'], '"\\/') . ' has already participated.', $result_url);
}

if (!$search_mb_id) {
    insert_point($member['mb_id'], $po['po_point'], $po['po_id'] . '. ' . cut_str($po['po_subject'], 20) . ' poll participation ', '@poll',
        $po['po_id'], 'poll');
}

//goto_url($g5['bbs_url'].'/poll_result.php?po_id='.$po_id.'&amp;skin_dir='.$skin_dir);
goto_url($result_url);