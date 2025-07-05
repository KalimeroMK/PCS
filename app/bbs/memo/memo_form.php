<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_guest) {
    alert_close('Only members can use this feature.');
}

$mb_id = isset($mb_id) ? get_search_string($mb_id) : '';

if (!$member['mb_open'] && $is_admin != 'super' && $member['mb_id'] != $mb_id) {
    alert_close('You cannot send memos to others unless you make your information public. You can set information disclosure in the member information edit page.');
}

$content = "";
$me_recv_mb_id = isset($_REQUEST['me_recv_mb_id']) ? clean_xss_tags($_REQUEST['me_recv_mb_id'], 1, 1) : '';
$me_id = isset($_REQUEST['me_id']) ? clean_xss_tags($_REQUEST['me_id'], 1, 1) : '';

// Member information does not exist when sending a memo
if ($me_recv_mb_id) {
    $mb = get_member($me_recv_mb_id);
    if (!$mb['mb_id']) {
        alert_close('Member information does not exist.\n\nIt may be a withdrawn member.');
    }

    if (!$mb['mb_open'] && $is_admin != 'super') {
        alert_close('Information is not public.');
    }

    // 4.00.15
    $row = sql_fetch(" select me_memo from {$g5['memo_table']} where me_id = '{$me_id}' and (me_recv_mb_id = '{$member['mb_id']}' or me_send_mb_id = '{$member['mb_id']}') ");
    if (isset($row['me_memo']) && $row['me_memo']) {
        $content = "\n\n\n".' >'
            ."\n".' >'
            ."\n".' >'.str_replace("\n", "\n> ", get_text($row['me_memo'], 0))
            ."\n".' >'
            .' >';
    }
}

$g5['title'] = 'Send Memo';
include_once(G5_PATH.'/head.sub.php');

$memo_action_url = G5_HTTPS_BBS_URL."/memo_form_update.php";
include_once($member_skin_path.'/memo_form.skin.php');

include_once(G5_PATH.'/tail.sub.php');