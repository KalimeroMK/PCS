<?php

include_once(__DIR__ . '/../common.php');


$g5['title'] = 'Enter Password';

$comment_id = isset($_REQUEST['comment_id']) ? preg_replace('/[^0-9]/', '', $_REQUEST['comment_id']) : 0;

switch ($w) {
    case 'u' :
        $action = G5_HTTP_BBS_URL . '/write.php';
        $return_url = short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id);
        break;
    case 'd' :
        set_session('ss_delete_token', $token = uniqid(time()));
        $action = https_url(G5_BBS_DIR) . '/delete.php?token=' . $token;
        $return_url = short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id);
        break;
    case 'x' :
        set_session('ss_delete_comment_' . $comment_id . '_token', $token = uniqid(time()));
        $action = https_url(G5_BBS_DIR) . '/delete_comment.php?token=' . $token;
        $row = sql_fetch(" select wr_parent from $write_table where wr_id = '$comment_id' ");
        $return_url = short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $row['wr_parent']);
        break;
    case 's' :
        // If logging in from password window, go directly to view if admin or post owner
        if ($is_admin || ($member['mb_id'] == $write['mb_id'] && $write['mb_id'])) {
            goto_url(short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id));
        } else {
            $action = https_url(G5_BBS_DIR) . '/password_check.php';
            $return_url = short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table);
        }
        break;
    case 'sc' :
        // If logging in from password window, go directly to view if admin or post owner
        if ($is_admin || ($member['mb_id'] == $write['mb_id'] && $write['mb_id'])) {
            goto_url(short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id));
        } else {
            $action = https_url(G5_BBS_DIR) . '/password_check.php';
            $return_url = short_url_clean(G5_HTTP_BBS_URL . '/board.php?bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id);
        }
        break;
    default :
        alert('Invalid w value.');
}

include_once(G5_PATH . '/head.sub.php');

//if ($board['bo_include_head'] && is_include_path_check($board['bo_content_head'])) { @include ($board['bo_include_head']); }
//if ($board['bo_content_head']) { echo html_purifier(stripslashes($board['bo_content_head'])); }

/* Get the subject of the secret post - Jieun's dad 2013-01-29 */
if (isset($write['wr_num'])) {
    $sql = " select wr_subject from {$write_table}
                        where wr_num = '{$write['wr_num']}'
                        and wr_reply = ''
                        and wr_is_comment = 0 ";
    $row = sql_fetch($sql);

    $g5['title'] = get_text((string)$row['wr_subject']);
}

include_once($member_skin_path . '/password.skin.php');

//if ($board['bo_content_tail']) { echo html_purifier(stripslashes($board['bo_content_tail'])); }
//if ($board['bo_include_tail'] && is_include_path_check($board['bo_content_tail'])) { @include ($board['bo_include_tail']); }

include_once(G5_PATH . '/tail.sub.php');