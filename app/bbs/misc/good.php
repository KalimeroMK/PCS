<?php

include_once(__DIR__ . '/../common.php');


run_event('bbs_good_before', $bo_table, $wr_id, $good);

@include_once($board_skin_path.'/good.head.skin.php');

// When JavaScript is enabled
if (isset($_POST['js']) && $_POST['js'] === "on") {
    $error = $count = "";

    function print_result(string $error, string $count): void
    {
        echo '{ "error": "'.$error.'", "count": "'.$count.'" }';
        if ($error !== '' && $error !== '0') {
            exit;
        }
    }

    if (!$is_member) {
        $error = 'Members only.';
        print_result($error, $count);
    }

    if (!($bo_table && $wr_id)) {
        $error = 'The value was not passed correctly.';
        print_result($error, $count);
    }

    $ss_name = 'ss_view_'.$bo_table.'_'.$wr_id;
    if (!get_session($ss_name)) {
        $error = 'You can only recommend or not recommend from within the relevant post.';
        print_result($error, $count);
    }

    $row = sql_fetch(" select count(*) as cnt from {$g5['write_prefix']}{$bo_table} ", false);
    if (!$row['cnt']) {
        $error = 'The post does not exist.';
        print_result($error, $count);
    }

    if ($good == 'good' || $good == 'nogood') {
        if ($write['mb_id'] == $member['mb_id']) {
            $error = 'You cannot recommend or not recommend your own post.';
            print_result($error, $count);
        }

        if (!$board['bo_use_good'] && $good == 'good') {
            $error = 'This board does not allow recommendations.';
            print_result($error, $count);
        }

        if (!$board['bo_use_nogood'] && $good == 'nogood') {
            $error = 'This board does not allow not recommendations.';
            print_result($error, $count);
        }

        $sql = " select bg_flag from {$g5['board_good_table']}
                    where bo_table = '{$bo_table}'
                    and wr_id = '{$wr_id}'
                    and mb_id = '{$member['mb_id']}'
                    and bg_flag in ('good', 'nogood') ";
        $row = sql_fetch($sql);
        if (isset($row['bg_flag']) && $row['bg_flag']) {
            $status = $row['bg_flag'] == 'good' ? 'Recommend' : 'Not Recommend';

            $error = "Already $status on this post.";
            print_result($error, $count);
        } else {
            // Increase recommend (like), not recommend (dislike) count
            sql_query(" update {$g5['write_prefix']}{$bo_table} set wr_{$good} = wr_{$good} + 1 where wr_id = '{$wr_id}' ");
            // Insert record
            sql_query(" insert {$g5['board_good_table']} set bo_table = '{$bo_table}', wr_id = '{$wr_id}', mb_id = '{$member['mb_id']}', bg_flag = '{$good}', bg_datetime = '".G5_TIME_YMDHIS."' ");

            $sql = " select wr_{$good} as count from {$g5['write_prefix']}{$bo_table} where wr_id = '$wr_id' ";
            $row = sql_fetch($sql);

            $count = $row['count'];

            run_event('bbs_increase_good_json', $bo_table, $wr_id, $good);

            print_result($error, $count);
        }
    }
} else {
    include_once(G5_PATH.'/head.sub.php');

    if (!$is_member) {
        $href = G5_BBS_URL.'/login.php?'.$qstr.'&amp;url='.urlencode(get_pretty_url($bo_table, $wr_id));

        alert('Members only.', $href);
    }

    if (!($bo_table && $wr_id)) {
        alert('The value was not passed correctly.');
    }

    $ss_name = 'ss_view_'.$bo_table.'_'.$wr_id;
    if (!get_session($ss_name)) {
        alert('You can only recommend or not recommend from within the relevant post.');
    }

    $row = sql_fetch(" select count(*) as cnt from {$g5['write_prefix']}{$bo_table} ", false);
    if (!$row['cnt']) {
        alert('The post does not exist.');
    }

    if ($good == 'good' || $good == 'nogood') {
        if ($write['mb_id'] == $member['mb_id']) {
            alert('You cannot recommend or not recommend your own post.');
        }

        if (!$board['bo_use_good'] && $good == 'good') {
            alert('This board does not allow recommendations.');
        }

        if (!$board['bo_use_nogood'] && $good == 'nogood') {
            alert('This board does not allow not recommendations.');
        }

        $sql = " select bg_flag from {$g5['board_good_table']}
                    where bo_table = '{$bo_table}'
                    and wr_id = '{$wr_id}'
                    and mb_id = '{$member['mb_id']}'
                    and bg_flag in ('good', 'nogood') ";
        $row = sql_fetch($sql);
        if (isset($row['bg_flag']) && $row['bg_flag']) {
            $status = $row['bg_flag'] == 'good' ? 'Recommend' : 'Not Recommend';

            alert("Already $status on this post.");
        } else {
            // Increase recommend (like), not recommend (dislike) count
            sql_query(" update {$g5['write_prefix']}{$bo_table} set wr_{$good} = wr_{$good} + 1 where wr_id = '{$wr_id}' ");
            // Insert record
            sql_query(" insert {$g5['board_good_table']} set bo_table = '{$bo_table}', wr_id = '{$wr_id}', mb_id = '{$member['mb_id']}', bg_flag = '{$good}', bg_datetime = '".G5_TIME_YMDHIS."' ");

            $status = $good == 'good' ? 'Recommend' : 'Not Recommend';

            $href = get_pretty_url($bo_table, $wr_id);

            run_event('bbs_increase_good_html', $bo_table, $wr_id, $good, $href);

            alert("You have $status this post.", '', false);
        }
    }
}

run_event('bbs_good_after', $bo_table, $wr_id, $good);

@include_once($board_skin_path.'/good.tail.skin.php');