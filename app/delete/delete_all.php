<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access not allowed

if (!$is_admin) {
    alert('You do not have access permission.', G5_URL);
}

// 4.11
@include_once($board_skin_path . '/delete_all.head.skin.php');

$count_write = 0;
$count_comment = 0;

$tmp_array = [];
if ($wr_id) // Delete by item
{
    $tmp_array[0] = $wr_id;
} else // Bulk delete
{
    $tmp_array = (isset($_POST['chk_wr_id']) && is_array($_POST['chk_wr_id'])) ? $_POST['chk_wr_id'] : [];
}

$chk_count = count($tmp_array);

if ($chk_count > (G5_IS_MOBILE ? $board['bo_mobile_page_rows'] : $board['bo_page_rows'])) {
    alert('Please use the correct method.');
}

// Execute user code
@include_once($board_skin_path . '/delete_all.skin.php');

// The reason for reading in reverse is that replies must be deleted first
for ($i = $chk_count - 1; $i >= 0; $i--) {
    $write = sql_fetch(" select * from $write_table where wr_id = '$tmp_array[$i]' ");

    if ($is_admin != 'super') {
        if ($is_admin == 'group') {
            $mb = get_member($write['mb_id']);
            if ($member['mb_id'] == $group['gr_admin']) // Is this the group you manage?
            {
                if ($member['mb_level'] < $mb['mb_level']) {
                    continue;
                }
            } else {
                continue;
            }
        } elseif ($is_admin == 'board') {
            $mb = get_member($write['mb_id']);
            if ($member['mb_id'] == $board['bo_admin']) // Is this the board you manage?
            {
                if ($member['mb_level'] < $mb['mb_level']) {
                    continue;
                }
            } else {
                continue;
            }
        } elseif ($member['mb_id'] && $member['mb_id'] == $write['mb_id']) {

        } elseif ($wr_password && !$write['mb_id'] && check_password($wr_password,
                $write['wr_password'])) {

        } else {
            continue;
        }
    }   // Others cannot delete

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
    if ($row['cnt']) {
        continue;
    }

    // Fixed by Naraoreum: Fixed the bug where the number of original posts and comments were not updated correctly.
    //$sql = " select wr_id, mb_id, wr_comment from {$write_table} where wr_parent = '{$write[wr_id]}' order by wr_id ";
    $sql = " select wr_id, mb_id, wr_is_comment, wr_content from $write_table where wr_parent = '{$write['wr_id']}' order by wr_id ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) {
        // If original post
        if (!$row['wr_is_comment']) {
            // Delete original post points
            if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], 'writing')) {
                insert_point($row['mb_id'], $board['bo_write_point'] * (-1),
                    "{$board['bo_subject']} {$row['wr_id']} post deleted");
            }

            // Delete uploaded files
            $sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ";
            $result2 = sql_query($sql2);
            while ($row2 = sql_fetch_array($result2)) {
                // Delete file
                $delete_file = run_replace('delete_file_path',
                    G5_DATA_PATH . '/file/' . $bo_table . '/' . str_replace('../', '', $row2['bf_file']), $row2);
                if (file_exists($delete_file)) {
                    @unlink($delete_file);
                }

                // Delete thumbnail
                if (preg_match("/\.({$config['cf_image_extension']})$/i", $row2['bf_file'])) {
                    delete_board_thumbnail($bo_table, $row2['bf_file']);
                }
            }

            // Delete editor thumbnails
            delete_editor_thumbnail($row['wr_content']);

            // Delete file table row
            sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ");

            $count_write++;
        } else {
            // Delete comment points
            if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], 'comment')) {
                insert_point($row['mb_id'], $board['bo_comment_point'] * (-1),
                    "{$board['bo_subject']} {$write['wr_id']}-{$row['wr_id']} comment deleted");
            }

            $count_comment++;
        }
    }

    // Delete post
    sql_query(" delete from $write_table where wr_parent = '{$write['wr_id']}' ");

    // Delete recent posts
    sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");

    // Delete scrap
    sql_query(" delete from {$g5['scrap_table']} where bo_table = '$bo_table' and wr_id = '{$write['wr_id']}' ");

    /*
    // Delete notice
    $notice_array = explode(',', trim($board['bo_notice']));
    $bo_notice = "";
    for ($k=0; $k<count($notice_array); $k++)
        if ((int)$write['wr_id'] != (int)$notice_array[$k])
            $bo_notice .= $notice_array[$k].',';
    $bo_notice = trim($bo_notice);
    */
    $bo_notice = board_notice($board['bo_notice'], $write['wr_id']);
    sql_query(" update {$g5['board_table']} set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");
    $board['bo_notice'] = $bo_notice;
}

// Decrease post count
if ($count_write > 0 || $count_comment > 0) {
    sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '$count_write', bo_count_comment = bo_count_comment - '$count_comment' where bo_table = '$bo_table' ");
}

// 4.11
@include_once($board_skin_path . '/delete_all.tail.skin.php');

delete_cache_latest($bo_table);

run_event('bbs_delete_all', $tmp_array, $board);

goto_url(short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;page=' . $page . $qstr));