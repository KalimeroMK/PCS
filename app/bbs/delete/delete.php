<?php

include_once(__DIR__ . '/../common.php');


$delete_token = get_session('ss_delete_token');
set_session('ss_delete_token', '');

if (!($token && $delete_token == $token)) {
    alert('Token error, deletion is not possible.');
}

//$wr = sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");

$count_write = $count_comment = 0;

@include_once($board_skin_path.'/delete.head.skin.php');

if ($is_admin != 'super') {
    if ($is_admin == 'group') {
        // Group administrator
        $mb = get_member($write['mb_id']);
        if ($member['mb_id'] != $group['gr_admin']) {
            alert('This is not a board managed by the group administrator, so it cannot be deleted.');
        } elseif ($member['mb_level'] < $mb['mb_level']) {
            alert('This is a post written by a member with higher authority than you, so it cannot be deleted.');
        }
    } elseif ($is_admin == 'board') {
        // Board administrator
        $mb = get_member($write['mb_id']);
        if ($member['mb_id'] != $board['bo_admin']) {
            alert('This is not a board managed by the board administrator, so it cannot be deleted.');
        } elseif ($member['mb_level'] < $mb['mb_level']) {
            alert('This is a post written by a member with higher authority than you, so it cannot be deleted.');
        }
    } elseif ($member['mb_id']) {
        if ($member['mb_id'] !== $write['mb_id']) {
            alert('This is not your post, so it cannot be deleted.');
        }
    } elseif ($write['mb_id']) {
        alert('Please log in before deleting.', G5_BBS_URL.'/login.php?url='.urlencode(get_pretty_url($bo_table, $wr_id)));
    } elseif (!check_password($wr_password, $write['wr_password'])) {
        alert('The password is incorrect, so it cannot be deleted.');
    }
}

$len = strlen($write['wr_reply']);
if ($len < 0) {
    $len = 0;
}
$reply = substr($write['wr_reply'], 0, $len);

// Only get original post.
$sql = " select count(*) as cnt from $write_table
            where wr_reply like '$reply%'
            and wr_id <> '{$write['wr_id']}'
            and wr_num = '{$write['wr_num']}'
            and wr_is_comment = 0 ";
$row = sql_fetch($sql);
if ($row['cnt'] && !$is_admin) {
    alert('There is a related reply post, so it cannot be deleted.\n\nPlease delete the reply post first.');
}

// Check if the original post with different member has comments
$sql = " select count(*) as cnt from $write_table
            where wr_parent = '$wr_id'
            and mb_id <> '{$member['mb_id']}'
            and wr_is_comment = 1 ";
$row = sql_fetch($sql);
if ($row['cnt'] >= $board['bo_count_delete'] && !$is_admin) {
    alert('There is a related comment, so it cannot be deleted.\n\nIf there are more than '.$board['bo_count_delete'].' related comments, the original post cannot be deleted.');
}


// Execute user code
@include_once($board_skin_path.'/delete.skin.php');


// Fixed by Naraoreum: Fixed the bug where the number of original posts and comments were not updated correctly.
//$sql = " select wr_id, mb_id, wr_comment from $write_table where wr_parent = '$write[wr_id]' order by wr_id ";
$sql = " select wr_id, mb_id, wr_is_comment, wr_content from $write_table where wr_parent = '{$write['wr_id']}' order by wr_id ";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {
    // Original post
    if (!$row['wr_is_comment']) {
        // Delete original post points
        if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], 'Write')) {
            insert_point($row['mb_id'], $board['bo_write_point'] * (-1), "{$board['bo_subject']} {$row['wr_id']} post deleted");
        }

        // Delete uploaded files
        $sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ";
        $result2 = sql_query($sql2);
        while ($row2 = sql_fetch_array($result2)) {
            $delete_file = run_replace('delete_file_path',
                G5_DATA_PATH.'/file/'.$bo_table.'/'.str_replace('../', '', $row2['bf_file']), $row2);
            if (file_exists($delete_file)) {
                @unlink($delete_file);
            }
            // Delete thumbnail
            if (preg_match("/\.({$config['cf_image_extension']})$/i", $row2['bf_file'])) {
                delete_board_thumbnail($bo_table, $row2['bf_file']);
            }
        }

        // Delete editor thumbnail
        delete_editor_thumbnail($row['wr_content']);

        // Delete file table row
        sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ");

        $count_write++;
    } else {
        // Delete comment points
        if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], 'Comment')) {
            insert_point($row['mb_id'], $board['bo_comment_point'] * (-1),
                "{$board['bo_subject']} {$write['wr_id']}-{$row['wr_id']} comment deleted");
        }

        $count_comment++;
    }
}

// Delete post and comments
sql_query(" delete from $write_table where wr_parent = '{$write['wr_id']}' ");

// Delete recent posts
sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");

// Delete scrap
sql_query(" delete from {$g5['scrap_table']} where bo_table = '$bo_table' and wr_id = '{$write['wr_id']}' ");

/*
// Delete notice
$notice_array = explode("\n", trim($board['bo_notice']));
$bo_notice = "";
for ($k=0; $k<count($notice_array); $k++)
    if ((int)$write[wr_id] != (int)$notice_array[$k])
        $bo_notice .= $notice_array[$k] . "\n";
$bo_notice = trim($bo_notice);
*/
$bo_notice = board_notice($board['bo_notice'], $write['wr_id']);
sql_query(" update {$g5['board_table']} set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");

// Decrease post count
if ($count_write > 0 || $count_comment > 0) {
    sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '$count_write', bo_count_comment = bo_count_comment - '$count_comment' where bo_table = '$bo_table' ");
}

@include_once($board_skin_path.'/delete.tail.skin.php');

delete_cache_latest($bo_table);

run_event('bbs_delete', $write, $board);

goto_url(short_url_clean(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;page='.$page.$qstr));