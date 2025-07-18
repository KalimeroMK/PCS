<?php

include_once(__DIR__ . '/../common.php');


$g5['title'] = 'Current Visitors';
include_once(__DIR__ . '/../header.php');
$list = [];

$sql = " select a.mb_id, b.mb_nick, b.mb_name, b.mb_email, b.mb_homepage, b.mb_open, b.mb_point, a.lo_ip, a.lo_location, a.lo_url
            from {$g5['login_table']} a left join {$g5['member_table']} b on (a.mb_id = b.mb_id)
            where a.mb_id <> '{$config['cf_admin']}'
            order by a.lo_datetime desc ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $row['lo_url'] = get_text($row['lo_url']);
    $list[$i] = $row;

    if ($row['mb_id']) {
        $list[$i]['name'] = get_sideview($row['mb_id'], cut_str($row['mb_nick'], $config['cf_cut_name']),
            $row['mb_email'], $row['mb_homepage']);
    } elseif ($is_admin) {
        $list[$i]['name'] = $row['lo_ip'];
    } else {
        $list[$i]['name'] = preg_replace("/(\\d+).(\\d+).(\\d+).(\\d+)/", G5_IP_DISPLAY, $row['lo_ip']);
    }

    $list[$i]['num'] = sprintf('%03d', $i + 1);
}

include_once($connect_skin_path . '/current_connect.skin.php');

include_once(__DIR__ . '/footer.php');