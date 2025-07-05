<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_EDITOR_LIB);

$qa_id = isset($_REQUEST['qa_id']) ? (int)$_REQUEST['qa_id'] : 0;

if ($is_guest) {
    alert('If you are a member, please log in to use this service.', './login.php?url=' . urlencode(G5_BBS_URL . '/qaview.php?qa_id=' . $qa_id));
}

$qaconfig = get_qa_config();
$content = '';

$token = _token();
set_session('ss_qa_delete_token', $token);
set_session('ss_qa_write_token', $token);

$g5['title'] = $qaconfig['qa_title'];
include_once(__DIR__ . '/qahead.php');

$skin_file = $qa_skin_path . '/view.skin.php';

if (is_file($skin_file)) {
    $sql = " select * from {$g5['qa_content_table']} where qa_id = '$qa_id' ";
    if (!$is_admin) {
        $sql .= " and mb_id = '{$member['mb_id']}' ";
    }

    $view = sql_fetch($sql);

    if (!(isset($view['qa_id']) && $view['qa_id'])) {
        alert('The post does not exist.\nIt may have been deleted or is not your own post.');
    }

    $subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];

    $view['category'] = get_text($view['qa_category']);
    $view['subject'] = conv_subject($view['qa_subject'], $subject_len, '…');
    $view['content'] = conv_content($view['qa_content'], $view['qa_html']);
    $view['name'] = get_text($view['qa_name']);
    $view['datetime'] = $view['qa_datetime'];
    $view['email'] = get_text(get_email_address($view['qa_email']));
    $view['hp'] = $view['qa_hp'];

    if (trim($stx) !== '' && trim($stx) !== '0') {
        $view['subject'] = search_font($stx, $view['subject']);
    }

    if (trim($stx) !== '' && trim($stx) !== '0') {
        $view['content'] = search_font($stx, $view['content']);
    }

    // Previous post, Next post
    $sql = " select qa_id, qa_subject
                from {$g5['qa_content_table']}
                where qa_type = '0' ";
    if (!$is_admin) {
        $sql .= " and mb_id = '{$member['mb_id']}' ";
    }

    // Previous post
    $prev_search = " and qa_num < '{$view['qa_num']}' order by qa_num desc limit 1 ";
    $prev = sql_fetch($sql . $prev_search);

    $prev_href = '';
    if (isset($prev['qa_id']) && $prev['qa_id']) {
        $prev_qa_subject = get_text(cut_str($prev['qa_subject'], 255));
        $prev_href = G5_BBS_URL . '/qaview.php?qa_id=' . $prev['qa_id'] . $qstr;
    }

    // Next post
    $next_search = " and qa_num > '{$view['qa_num']}' order by qa_num asc limit 1 ";
    $next = sql_fetch($sql . $next_search);

    $next_href = '';
    if (isset($next['qa_id']) && $next['qa_id']) {
        $next_qa_subject = get_text(cut_str($next['qa_subject'], 255));
        $next_href = G5_BBS_URL . '/qaview.php?qa_id=' . $next['qa_id'] . $qstr;
    }


    // Related questions
    $rows = 10;
    $sql = " select *
                from {$g5['qa_content_table']}
                where qa_id <> '$qa_id'
                  and qa_related = '{$view['qa_related']}'
                  and qa_type = '0'
                order by qa_num, qa_type
                limit 0, $rows ";
    $result = sql_query($sql);

    $rel_list = [];
    $rel_count = 0;
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $rel_list[$i] = $row;
        $rel_list[$i]['category'] = get_text($row['qa_category']);
        $rel_list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
        $rel_list[$i]['name'] = get_text($row['qa_name']);
        $rel_list[$i]['date'] = substr($row['qa_datetime'], 2, 8);
        $rel_list[$i]['view_href'] = G5_BBS_URL . '/qaview.php?qa_id=' . $row['qa_id'] . $qstr;
        $rel_count++;
    }
    $view['rel_count'] = $rel_count;

    $update_href = '';
    $delete_href = '';
    $write_href = G5_BBS_URL . '/qawrite.php';
    $rewrite_href = G5_BBS_URL . '/qawrite.php?w=r&amp;qa_id=' . $view['qa_id'];
    $list_href = G5_BBS_URL . '/qalist.php' . preg_replace('/^&amp;/', '?', $qstr);

    /*
    if($view['qa_type']) {
        if($is_admin)
            $update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;qa_id='.$view['qa_id'].$qstr;
    } else {
        if($view['qa_status'] == 0)
            $update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;qa_id='.$view['qa_id'].$qstr;
    }
    */

    if (($view['qa_type'] && $is_admin) || (!$view['qa_type'] && $view['qa_status'] == 0)) {
        $update_href = G5_BBS_URL . '/qawrite.php?w=u&amp;qa_id=' . $view['qa_id'] . $qstr;
        $delete_href = G5_BBS_URL . '/qadelete.php?qa_id=' . $view['qa_id'] . '&amp;token=' . $token . $qstr;
    }

    // If it is a question post and there is a registered answer
    $answer = [];
    $answer_update_href = '';
    $answer_delete_href = '';
    if (!$view['qa_type'] && $view['qa_status']) {
        $sql = " select *
                    from {$g5['qa_content_table']}
                    where qa_type = '1'
                      and qa_parent = '{$view['qa_id']}' ";
        $answer = sql_fetch($sql);

        if ($is_admin) {
            $answer_update_href = G5_BBS_URL . '/qawrite.php?w=u&amp;qa_id=' . $answer['qa_id'] . $qstr;
            $answer_delete_href = G5_BBS_URL . '/qadelete.php?qa_id=' . $answer['qa_id'] . '&amp;token=' . $token . $qstr;
        }

        $ss_name = 'ss_qa_view_' . $answer['qa_id'];
        if (!get_session($ss_name)) {
            set_session($ss_name, true);
        }

        // Answer attachments
        $answer['img_file'] = [];
        $answer['download_href'] = [];
        $answer['download_source'] = [];
        $answer['img_count'] = 0;
        $answer['download_count'] = 0;

        for ($i = 1; $i <= 2; $i++) {
            if (preg_match("/\.({$config['cf_image_extension']})$/i", $answer['qa_file' . $i])) {
                $attr_href = run_replace('thumb_view_image_href',
                    G5_BBS_URL . '/view_image.php?fn=' . urlencode('/' . G5_DATA_DIR . '/qa/' . $answer['qa_file' . $i]),
                    '/' . G5_DATA_DIR . '/qa/' . $answer['qa_file' . $i], '', '', '', '');
                $answer['img_file'][] = '<a href="' . $attr_href . '" target="_blank" class="view_image"><img src="' . G5_DATA_URL . '/qa/' . $answer['qa_file' . $i] . '"></a>';
                $answer['img_count']++;
                continue;
            }

            if ($answer['qa_file' . $i]) {
                $answer['download_href'][] = G5_BBS_URL . '/qadownload.php?qa_id=' . $answer['qa_id'] . '&amp;no=' . $i;
                $answer['download_source'][] = $answer['qa_source' . $i];
                $answer['download_count']++;
            }
        }
    }

    $stx = get_text(stripslashes($stx));

    $is_dhtml_editor = false;
    // DHTML editor cannot be used on mobile
    if ($config['cf_editor'] && $qaconfig['qa_use_editor'] && !G5_IS_MOBILE) {
        $is_dhtml_editor = true;
    }
    $editor_html = editor_html('qa_content', $content);
    $editor_js = '';
    $editor_js .= get_editor_js('qa_content');
    $editor_js .= chk_editor_js('qa_content');

    $ss_name = 'ss_qa_view_' . $qa_id;
    if (!get_session($ss_name)) {
        set_session($ss_name, true);
    }

    // Attachments
    $view['img_file'] = [];
    $view['download_href'] = [];
    $view['download_source'] = [];
    $view['img_count'] = 0;
    $view['download_count'] = 0;

    for ($i = 1; $i <= 2; $i++) {
        if (preg_match("/\.({$config['cf_image_extension']})$/i", $view['qa_file' . $i])) {
            $attr_href = run_replace('thumb_view_image_href',
                G5_BBS_URL . '/view_image.php?fn=' . urlencode('/' . G5_DATA_DIR . '/qa/' . $view['qa_file' . $i]),
                '/' . G5_DATA_DIR . '/qa/' . $view['qa_file' . $i], '', '', '', '');
            $view['img_file'][] = '<a href="' . $attr_href . '" target="_blank" class="view_image"><img src="' . G5_DATA_URL . '/qa/' . $view['qa_file' . $i] . '"></a>';
            $view['img_count']++;
            continue;
        }

        if ($view['qa_file' . $i]) {
            $view['download_href'][] = G5_BBS_URL . '/qadownload.php?qa_id=' . $view['qa_id'] . '&amp;no=' . $i;
            $view['download_source'][] = $view['qa_source' . $i];
            $view['download_count']++;
        }
    }

    $html_value = '';
    $html_checked = '';
    if (isset($view['qa_html']) && $view['qa_html']) {
        $html_checked = 'checked';
        $html_value = $view['qa_html'];

        if ($view['qa_html'] == 1 && !$is_dhtml_editor) {
            $html_value = 2;
        }
    }

    include_once($skin_file);
} else {
    echo '<div>' . str_replace(G5_PATH . '/', '', $skin_file) . ' does not exist.</div>';
}

include_once(__DIR__ . '/qatail.php');