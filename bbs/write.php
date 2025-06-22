<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_EDITOR_LIB);
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if (!$board['bo_table']) {
    alert('The specified board does not exist.', G5_URL);
}

if (!$bo_table) {
    alert("The board table value was not passed.\nPlease pass the value in the format write.php?bo_table=code.", G5_URL);
}

check_device($board['bo_device']);

$notice_array = explode(',', trim($board['bo_notice']));

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('The value of w was not passed correctly.');
}

if ($w == 'u' || $w == 'r') {
    if ($write['wr_id']) {
        // Create variables $wr_1 .. $wr_10 as temporary variables
        for ($i = 1; $i <= 10; $i++) {
            $vvar = "wr_".$i;
            $$vvar = $write['wr_'.$i];
        }
    } else {
        alert("The post does not exist.\nIt may have been deleted or moved.", G5_URL);
    }
} elseif ($w == '') {
    // When entering a post, also create variables $wr_1 ~ $wr_10 to prevent errors (DaonTema, 210806)
    for ($i = 1; $i <= 10; $i++) {
        $vvar = "wr_".$i;
        $$vvar = '';
    }
}

run_event('bbs_write', $board, $wr_id, $w);

if ($w == '') {
    if ($wr_id) {
        alert('Do not use $wr_id value when writing a post.', G5_BBS_URL.'/board.php?bo_table='.$bo_table);
    }
    if ($member['mb_level'] < $board['bo_write_level']) {
        if ($member['mb_id']) {
            alert('You do not have permission to write a post.');
        } else {
            alert('You do not have permission to write a post.\nIf you are a member, please log in and try again.',
                G5_BBS_URL.'/login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
        }
    }
    // Negative numbers are also true
    if ($is_member) {
        $tmp_point = ($member['mb_point'] > 0) ? $member['mb_point'] : 0;
        if ($tmp_point + $board['bo_write_point'] < 0 && !$is_admin) {
            alert('You do not have enough points ('.number_format($member['mb_point']).') to write a post ('.number_format($board['bo_write_point']).').\n\nPlease accumulate more points and try again.');
        }
    }
    $title_msg = 'Write Post';
} elseif ($w == 'u') {
    // Kim Sunyong 1.00 : Writing and editing permissions must be handled separately
    //if ($member['mb_level'] < $board['bo_write_level']) {
    if ($member['mb_id'] && $write['mb_id'] === $member['mb_id']) {
    } elseif ($member['mb_level'] < $board['bo_write_level']) {
        if ($member['mb_id']) {
            alert('You do not have permission to edit this post.');
        } else {
            alert('You do not have permission to edit this post.\n\nIf you are a member, please log in and try again.',
                G5_BBS_URL.'/login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
        }
    }
    $len = strlen($write['wr_reply']);
    if ($len < 0) {
        $len = 0;
    }
    $reply = substr($write['wr_reply'], 0, $len);
    // Get the original post
    $sql = " select count(*) as cnt from {$write_table}
                where wr_reply like '{$reply}%'
                and wr_id <> '{$write['wr_id']}'
                and wr_num = '{$write['wr_num']}'
                and wr_is_comment = 0 ";
    $row = sql_fetch($sql);
    if ($row['cnt'] && !$is_admin) {
        alert('This post has related answers, so it cannot be edited.\n\nAnswers cannot be edited.');
    }
    // Check if the original post has comments
    $sql = " select count(*) as cnt from {$write_table}
                where wr_parent = '{$wr_id}'
                and mb_id <> '{$member['mb_id']}'
                and wr_is_comment = 1 ";
    $row = sql_fetch($sql);
    if ($board['bo_count_modify'] && $row['cnt'] >= $board['bo_count_modify'] && !$is_admin) {
        alert('This post has related comments, so it cannot be edited.\n\nComments cannot be edited.');
    }
    $title_msg = 'Edit Post';
} elseif ($w == 'r') {
    if ($member['mb_level'] < $board['bo_reply_level']) {
        if ($member['mb_id']) {
            alert('You do not have permission to reply to this post.');
        } else {
            alert('You do not have permission to reply to this post.\n\nIf you are a member, please log in and try again.',
                G5_BBS_URL.'/login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
        }
    }
    $tmp_point = isset($member['mb_point']) ? $member['mb_point'] : 0;
    if ($tmp_point + $board['bo_write_point'] < 0 && !$is_admin) {
        alert('You do not have enough points ('.number_format($member['mb_point']).') to reply to this post ('.number_format($board['bo_comment_point']).').\n\nPlease accumulate more points and try again.');
    }
    //if (preg_match("/[^0-9]{0,1}{$wr_id}[\r]{0,1}/",$board['bo_notice']))
    if (in_array((int)$wr_id, $notice_array)) {
        alert('You cannot reply to a notice.');
    }
    //----------
    // 4.06.13 : Fixed bug where private posts could be viewed by others (reported by Hulrang and Flok)
    // Comments cannot be replied to
    if ($write['wr_is_comment']) {
        alert('Invalid access.');
    }
    // Check if the post is private
    if (strstr($write['wr_option'], 'secret')) {
        if ($write['mb_id']) {
            // Members can only reply to their own posts or posts by administrators
            if ($write['mb_id'] !== $member['mb_id'] && !$is_admin) {
                alert('You can only reply to your own posts or posts by administrators.');
            }
        } elseif (!$is_admin) {
            // Non-members cannot reply to private posts
            alert('Non-members cannot reply to private posts.');
        }
    }
    //----------
    // Get the post array
    $reply_array = &$write;
    // Maximum number of replies is limited by the wr_reply field size
    if (strlen($reply_array['wr_reply']) == 10) {
        alert('You cannot reply to this post.\n\nReplies are limited to 10 levels.');
    }
    $reply_len = strlen($reply_array['wr_reply']) + 1;
    if ($board['bo_reply_order']) {
        $begin_reply_char = 'A';
        $end_reply_char = 'Z';
        $reply_number = +1;
        $sql = " select MAX(SUBSTRING(wr_reply, {$reply_len}, 1)) as reply from {$write_table} where wr_num = '{$reply_array['wr_num']}' and SUBSTRING(wr_reply, {$reply_len}, 1) <> '' ";
    } else {
        $begin_reply_char = 'Z';
        $end_reply_char = 'A';
        $reply_number = -1;
        $sql = " select MIN(SUBSTRING(wr_reply, {$reply_len}, 1)) as reply from {$write_table} where wr_num = '{$reply_array['wr_num']}' and SUBSTRING(wr_reply, {$reply_len}, 1) <> '' ";
    }
    if ($reply_array['wr_reply']) {
        $sql .= " and wr_reply like '{$reply_array['wr_reply']}%' ";
    }
    $row = sql_fetch($sql);
    if (!$row['reply']) {
        $reply_char = $begin_reply_char;
    } elseif ($row['reply'] == $end_reply_char) {
        alert('You cannot reply to this post.\n\nReplies are limited to 26 levels.');
    } else {
        $reply_char = chr(ord($row['reply']) + $reply_number);
    }
    $reply = $reply_array['wr_reply'].$reply_char;
    $title_msg = 'Reply to Post';
    $write['wr_subject'] = 'Re: '.$write['wr_subject'];
}

// Group access
if (!empty($group['gr_use_access'])) {
    if ($is_guest) {
        alert("You do not have access to this board.\n\nIf you are a member, please log in and try again.",
            'login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
    }

    if ($is_admin == 'super' || $group['gr_admin'] === $member['mb_id'] || $board['bo_admin'] === $member['mb_id']) {
        // Pass
    } else {
        // Group access
        $sql = " select gr_id from {$g5['group_member_table']} where gr_id = '{$board['gr_id']}' and mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['gr_id']) {
            alert('You do not have access to this board.\n\nPlease contact the administrator for more information.');
        }
    }
}

// Use certification
if ($board['bo_use_cert'] != '' && $config['cf_cert_use'] && !$is_admin) {
    // Only certified members can access
    if ($is_guest) {
        alert('This board is only accessible to certified members.\n\nIf you are a member, please log in and try again.',
            G5_BBS_URL.'/login.php?wr_id='.$wr_id.$qstr.'&amp;url='.urlencode(get_pretty_url($bo_table, $wr_id,
                $qstr)));
    }

    if (strlen($member['mb_dupinfo']) == 64 && $member['mb_certify']) { // Certified members only
        goto_url(G5_BBS_URL."/member_cert_refresh.php?url=".urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
    }

    if ($board['bo_use_cert'] == 'cert' && !$member['mb_certify']) {
        alert('This board is only accessible to certified members.\n\nPlease certify your account in your member information.',
            G5_URL);
    }

    if ($board['bo_use_cert'] == 'adult' && !$member['mb_adult']) {
        alert('This board is only accessible to adult-certified members.\n\nPlease certify your account in your member information.',
            G5_URL);
    }
}

// Character limit
if ($is_admin || $board['bo_use_dhtml_editor']) {
    $write_min = $write_max = 0;
} else {
    $write_min = (int)$board['bo_write_min'];
    $write_max = (int)$board['bo_write_max'];
}

$g5['title'] = ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']).' '.$title_msg;

$is_notice = false;
$notice_checked = '';
if ($is_admin && $w != 'r') {
    $is_notice = true;

    if ($w == 'u') {
        // No notice check for reply editing
        if ($write['wr_reply']) {
            $is_notice = false;
        } elseif (in_array((int)$wr_id, $notice_array)) {
            $notice_checked = 'checked';
        }
    }
}

$is_html = false;
if ($member['mb_level'] >= $board['bo_html_level']) {
    $is_html = true;
}

$is_secret = $board['bo_use_secret'];

$is_mail = false;
if ($config['cf_email_use'] && $board['bo_use_email']) {
    $is_mail = true;
}

$recv_email_checked = '';
if ($w == '' || strstr($write['wr_option'], 'mail')) {
    $recv_email_checked = 'checked';
}

$is_name = false;
$is_password = false;
$is_email = false;
$is_homepage = false;
if ($is_guest || ($is_admin && $w == 'u' && $member['mb_id'] !== $write['mb_id'])) {
    $is_name = true;
    $is_password = true;
    $is_email = true;
    $is_homepage = true;
}

$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
    $ca_name = "";
    if (isset($write['ca_name'])) {
        $ca_name = $write['ca_name'];
    }
    $category_option = get_category_option($bo_table, $ca_name);
    $is_category = true;
}

$is_link = false;
if ($member['mb_level'] >= $board['bo_link_level']) {
    $is_link = true;
}

$is_file = false;
if ($member['mb_level'] >= $board['bo_upload_level']) {
    $is_file = true;
}

$is_file_content = false;
if ($board['bo_use_file_content']) {
    $is_file_content = true;
}

$file_count = (int)$board['bo_upload_count'];

$name = "";
$email = "";
$homepage = "";
if (($w == "" || $w == "r") && $is_member) {
    if (isset($write['wr_name'])) {
        $name = get_text(cut_str(stripslashes($write['wr_name']), 20));
    }
    $email = get_email_address($member['mb_email']);
    $homepage = get_text(stripslashes($member['mb_homepage']));
}

$html_checked = "";
$html_value = "";
$secret_checked = "";

if ($w == '') {
    $password_required = 'required';
} elseif ($w == 'u') {
    $password_required = '';
    if (!$is_admin && !$is_member && $member['mb_id'] === $write['mb_id'] && !check_password($wr_password, $write['wr_password'])) {
        $is_wrong = run_replace('invalid_password', false, 'write', $write);
        if (!$is_wrong) {
            alert('Invalid password.');
        }
    }
    $name = get_text(cut_str(stripslashes($write['wr_name']), 20));
    $email = get_email_address($write['wr_email']);
    $homepage = get_text(stripslashes($write['wr_homepage']));
    for ($i = 1; $i <= G5_LINK_COUNT; $i++) {
        $write['wr_link'.$i] = get_text($write['wr_link'.$i]);
        $link[$i] = $write['wr_link'.$i];
    }
    if (strstr($write['wr_option'], 'html1')) {
        $html_checked = 'checked';
        $html_value = 'html1';
    } elseif (strstr($write['wr_option'], 'html2')) {
        $html_checked = 'checked';
        $html_value = 'html2';
    }
    if (strstr($write['wr_option'], 'secret')) {
        $secret_checked = 'checked';
    }
    $file = get_file($bo_table, $wr_id);
    if ($file_count < $file['count']) {
        $file_count = $file['count'];
    }
    for ($i = 0; $i < $file_count; $i++) {
        if (!isset($file[$i])) {
            $file[$i] = ['file' => null, 'source' => null, 'size' => null, 'bf_content' => null];
        }
    }
} elseif ($w == 'r') {
    if (strstr($write['wr_option'], 'secret')) {
        $is_secret = true;
        $secret_checked = 'checked';
    }
    $password_required = "required";
    for ($i = 1; $i <= G5_LINK_COUNT; $i++) {
        $write['wr_link'.$i] = get_text($write['wr_link'.$i]);
    }
}

set_session('ss_bo_table', $bo_table);
set_session('ss_wr_id', $wr_id);

$subject = "";
if (isset($write['wr_subject'])) {
    $subject = str_replace("\"", "&#034;", get_text(cut_str($write['wr_subject'], 255), 0));
}

$content = '';
if ($w == '') {
    $content = html_purifier($board['bo_insert_content']);
} elseif ($w == 'r') {
    if (!strstr($write['wr_option'], 'html')) {
        $content = "\n\n\n &gt; "
            ."\n &gt; "
            ."\n &gt; ".str_replace("\n", "\n> ", get_text($write['wr_content'], 0))
            ."\n &gt; "
            ."\n &gt; ";
    }
} else {
    $content = get_text($write['wr_content'], 0);
}

$upload_max_filesize = number_format($board['bo_upload_size']).' bytes';

$width = $board['bo_table_width'];
if ($width <= 100) {
    $width .= '%';
} else {
    $width .= 'px';
}

$captcha_html = '';
$captcha_js = '';
$is_use_captcha = ((($board['bo_use_captcha'] && $w !== 'u') || $is_guest) && !$is_admin) ? 1 : 0;

if ($is_use_captcha !== 0) {
    $captcha_html = captcha_html();
    $captcha_js = chk_captcha_js();
}

$is_dhtml_editor = false;
$is_dhtml_editor_use = false;
$editor_content_js = '';
if (!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE) {
    $is_dhtml_editor_use = true;
}

// Mobile devices use DHTML editor if G5_IS_MOBILE_DHTML_USE is set
if ($config['cf_editor'] && $is_dhtml_editor_use && $board['bo_use_dhtml_editor'] && $member['mb_level'] >= $board['bo_html_level']) {
    $is_dhtml_editor = true;

    if ($w == 'u' && (!$is_member || !$is_admin || $write['mb_id'] !== $member['mb_id'])) {
        // KISA vulnerability report XSS filter applied
        $content = get_text(html_purifier($write['wr_content']), 0);
    }

    if (is_file(G5_EDITOR_PATH.'/'.$config['cf_editor'].'/autosave.editor.js')) {
        $editor_content_js = '<script src="'.G5_EDITOR_URL.'/'.$config['cf_editor'].'/autosave.editor.js"></script>'.PHP_EOL;
    }
}
$editor_html = editor_html('wr_content', $content);
$editor_js = '';
$editor_js .= get_editor_js('wr_content');
$editor_js .= chk_editor_js('wr_content');

// Number of temporarily saved posts
$autosave_count = autosave_count($member['mb_id']);

include_once(G5_PATH.'/head.sub.php');
@include_once($board_skin_path.'/write.head.skin.php');
include_once(G5_PATH.'/head.php');

$action_url = https_url(G5_BBS_DIR)."/write_update.php";

echo '<!-- skin : '.(G5_IS_MOBILE ? $board['bo_mobile_skin'] : $board['bo_skin']).' -->';
include_once($board_skin_path.'/write.skin.php');

include_once(__DIR__ . '/board_tail.php');
@include_once($board_skin_path.'/write.tail.skin.php');
include_once(G5_PATH.'/tail.sub.php');
