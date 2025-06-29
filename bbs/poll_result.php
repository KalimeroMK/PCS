<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$po_id = isset($_REQUEST['po_id']) ? (int)$_REQUEST['po_id'] : '';

$po = sql_fetch(" select * from {$g5['poll_table']} where po_id = '{$po_id}' ");
if (!$po['po_id']) {
    alert('Poll information does not exist.');
}

if ($member['mb_level'] < $po['po_level']) {
    alert('Only members with level '.$po['po_level'].' or higher can view the results.');
}

$g5['title'] = 'Poll Results';

$po_subject = $po['po_subject'];

$max = 1;
$total_po_cnt = 0;
$poll_max_count = 9;

for ($i = 1; $i <= $poll_max_count; $i++) {
    $poll = $po['po_poll'.$i];
    if (!$poll) {
        break;
    }

    $count = $po['po_cnt'.$i];
    $total_po_cnt += $count;

    if ($count > $max) {
        $max = $count;
    }
}
$nf_total_po_cnt = number_format($total_po_cnt);

$list = [];

for ($i = 1; $i <= $poll_max_count; $i++) {
    $poll = $po['po_poll'.$i];
    if (!$poll) {
        break;
    }

    $list[$i]['content'] = $poll;
    $list[$i]['cnt'] = $po['po_cnt'.$i];
    $list[$i]['rate'] = 0;

    if ($total_po_cnt > 0) {
        $list[$i]['rate'] = ($list[$i]['cnt'] / $total_po_cnt) * 100;
    }

    $bar = (int)($list[$i]['cnt'] / $max * 100);

    $list[$i]['bar'] = $bar;
    $list[$i]['num'] = $i;
}

$list2 = [];

// List of other opinions
$sql = " select a.*, b.mb_open
           from {$g5['poll_etc_table']} a
           left join {$g5['member_table']} b on (a.mb_id = b.mb_id)
          where po_id = '{$po_id}' order by pc_id desc ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list2[$i]['pc_name'] = get_text($row['pc_name']);
    $list2[$i]['name'] = get_sideview($row['mb_id'], get_text(cut_str($row['pc_name'], 10)), '', '');
    $list2[$i]['idea'] = get_text(cut_str($row['pc_idea'], 255));
    $list2[$i]['datetime'] = $row['pc_datetime'];

    $list2[$i]['del'] = '';
    if ($is_admin == 'super' || ($row['mb_id'] == $member['mb_id'] && $row['mb_id'])) {
        $list2[$i]['del'] = '<a href="'.G5_BBS_URL.'/poll_etc_update.php?w=d&amp;pc_id='.$row['pc_id'].'&amp;po_id='.$po_id.'&amp;skin_dir='.$skin_dir.'" class="poll_delete">';
    }
}

// Other opinions input
$is_etc = false;
if ($po['po_etc']) {
    $is_etc = true;
    $po_etc = $po['po_etc'];
    if ($member['mb_id']) {
        $name = '<b>'.$member['mb_nick'].'</b> <input type="hidden" name="pc_name" value="'.$member['mb_nick'].'">';
    } else {
        $name = '<input type="text" name="pc_name" size="10" class="input" required>';
    }
}

$list3 = [];

// Other polls
$sql = " select po_id, po_subject, po_date from {$g5['poll_table']} order by po_id desc ";
$result = sql_query($sql);
for ($i = 0; $row2 = sql_fetch_array($result); $i++) {
    $list3[$i]['po_id'] = $row2['po_id'];
    $list3[$i]['date'] = substr($row2['po_date'], 2, 8);
    $list3[$i]['subject'] = cut_str($row2['po_subject'], 60, "…");
}

if (preg_match('#^theme/(.+)$#', $skin_dir, $match)) {
    if (G5_IS_MOBILE) {
        $poll_skin_path = G5_THEME_MOBILE_PATH.'/'.G5_SKIN_DIR.'/poll/'.$match[1];
        if (!is_dir($poll_skin_path)) {
            $poll_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/poll/'.$match[1];
        }
        $poll_skin_url = str_replace(G5_PATH, G5_URL, $poll_skin_path);
    } else {
        $poll_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/poll/'.$match[1];
        $poll_skin_url = str_replace(G5_PATH, G5_URL, $poll_skin_path);
    }
    //$skin_dir = $match[1];
} elseif (G5_IS_MOBILE) {
    $poll_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/poll/'.$skin_dir;
    $poll_skin_url = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/poll/'.$skin_dir;
} else {
    $poll_skin_path = G5_SKIN_PATH.'/poll/'.$skin_dir;
    $poll_skin_url = G5_SKIN_URL.'/poll/'.$skin_dir;
}

include_once(G5_PATH.'/head.sub.php');

if (!file_exists($poll_skin_path.'/poll_result.skin.php')) {
    die('skin error');
}
include_once($poll_skin_path.'/poll_result.skin.php');

include_once(G5_PATH.'/tail.sub.php');