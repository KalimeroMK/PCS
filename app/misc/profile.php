<?php

include_once(__DIR__ . '/../common.php');


if (!$member['mb_id']) {
    alert_close('You must be a member to use this feature.');
}

if (!$member['mb_open'] && $is_admin != 'super' && $member['mb_id'] != $mb_id) {
    alert_close('You cannot view other members\' information if you do not make your own information public.\\n\\nYou can set this in the member information modification section.');
}

$mb_id = isset($mb_id) ? $mb_id : '';

$mb = get_member($mb_id);

if (!$mb['mb_id']) {
    alert_close('Member information does not exist.\\n\\nThey may have withdrawn.');
}

if (!$mb['mb_open'] && $is_admin != 'super' && $member['mb_id'] != $mb_id) {
    alert_close('This member has not made their information public.');
}

$g5['title'] = $mb['mb_nick'] . '\'s self-introduction';
include_once(G5_PATH . '/head.sub.php');

$mb_nick = get_sideview($mb['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']);

// How many days since registration? +1 means including the current day
$sql = " select (TO_DAYS('" . G5_TIME_YMDHIS . "') - TO_DAYS('{$mb['mb_datetime']}') + 1) as days ";
$row = sql_fetch($sql);
$mb_reg_after = $row['days'];

$mb_homepage = set_http(get_text(clean_xss_tags($mb['mb_homepage'])));
$mb_profile = $mb['mb_profile'] ? conv_content($mb['mb_profile'], 0) : 'No introduction content.';

include_once($member_skin_path . '/profile.skin.php');

include_once(G5_PATH . '/tail.sub.php');