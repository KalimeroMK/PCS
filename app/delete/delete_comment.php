<?php

// Delete comment
include_once(__DIR__ . '/../common.php');


$comment_id = isset($_REQUEST['comment_id']) ? (int)$_REQUEST['comment_id'] : 0;

$delete_comment_token = get_session('ss_delete_comment_' . $comment_id . '_token');
set_session('ss_delete_comment_' . $comment_id . '_token', '');

if (!($token && $delete_comment_token == $token)) {
    alert('Token error, deletion is not possible.');
}

// 4.1
@include_once($board_skin_path . '/delete_comment.head.skin.php');

$write = sql_fetch(" select * from {$write_table} where wr_id = '{$comment_id}' ");

if (!$write['wr_id'] || !$write['wr_is_comment']) {
    alert('There is no registered comment or it is not a comment post.');
}

if ($is_admin != 'super') {
    if ($is_admin == 'group') {
        // Group administrator
        $mb = get_member($write['mb_id']);
        if ($member['mb_id'] === $group['gr_admin']) { // Is this the group you manage?
            if ($member['mb_level'] < $mb['mb_level']) {
                alert('This is a comment from a member with higher authority than the group administrator, so it cannot be deleted.');
            }
        } else {
            alert('This is not a board managed by the group administrator, so comments cannot be deleted.');
        }
    } elseif ($is_admin === 'board') {
        // Board administrator
        $mb = get_member($write['mb_id']);
        if ($member['mb_id'] === $board['bo_admin']) { // Is this the board you manage?
            if ($member['mb_level'] < $mb['mb_level']) {
                alert('This is a comment from a member with higher authority than the board administrator, so it cannot be deleted.');
            }
        } else {
            alert('This is not a board managed by the board administrator, so comments cannot be deleted.');
        }
    } elseif ($member['mb_id']) {
        if ($member['mb_id'] !== $write['mb_id']) {
            alert('This is not your post, so it cannot be deleted.');
        }
    } elseif (!check_password($wr_password, $write['wr_password'])) {
        alert('The password is incorrect.');
    }
}

$len = strlen($write['wr_comment_reply']);
if ($len < 0) {
    $len = 0;
}
$comment_reply = substr($write['wr_comment_reply'], 0, $len);

$sql = " select count(*) as cnt from {$write_table}
            where wr_comment_reply like '{$comment_reply}%'
            and wr_id <> '{$comment_id}'
            and wr_parent = '{$write['wr_parent']}'
            and wr_comment = '{$write['wr_comment']}'
            and wr_is_comment = 1 ";
$row = sql_fetch($sql);
if ($row['cnt'] && !$is_admin) {
    alert('There is a related reply comment, so it cannot be deleted.');
}

// Delete comment points
if (!delete_point($write['mb_id'], $bo_table, $comment_id, 'Comment')) {
    insert_point($write['mb_id'], $board['bo_comment_point'] * (-1),
        "{$board['bo_subject']} {$write['wr_parent']}-{$comment_id} comment deletion");
}

// Delete comment
sql_query(" delete from {$write_table} where wr_id = '{$comment_id}' ");

// Since the comment is deleted, get the latest time for the related post again.
$sql = " select max(wr_datetime) as wr_last from {$write_table} where wr_parent = '{$write['wr_parent']}' ";
$row = sql_fetch($sql);

// Decrease the number of comments for the original post
sql_query(" update {$write_table} set wr_comment = wr_comment - 1, wr_last = '{$row['wr_last']}' where wr_id = '{$write['wr_parent']}' ");

// Decrease the comment count
sql_query(" update {$g5['board_table']} set bo_count_comment = bo_count_comment - 1 where bo_table = '{$bo_table}' ");

// Delete new post
sql_query(" delete from {$g5['board_new_table']} where bo_table = '{$bo_table}' and wr_id = '{$comment_id}' ");

// Execute user code
@include_once($board_skin_path . '/delete_comment.skin.php');
@include_once($board_skin_path . '/delete_comment.tail.skin.php');

delete_cache_latest($bo_table);

run_event('bbs_delete_comment', $comment_id, $board);

goto_url(short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $write['wr_parent'] . '&amp;page=' . $page . $qstr));