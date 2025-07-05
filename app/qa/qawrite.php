<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_EDITOR_LIB);

if ($w != '' && $w != 'u' && $w != 'r') {
    alert('Please use the correct method.');
}

$qa_id = isset($_REQUEST['qa_id']) ? (int)$_REQUEST['qa_id'] : 0;
$write = ['qa_email_recv' => '', 'qa_subject' => '', 'qa_category' => ''];

if ($is_guest) {
    alert('If you are a member, please log in to use this service.', './login.php?url=' . urlencode(G5_BBS_URL . '/qalist.php'));
}

$qaconfig = get_qa_config();
$token = _token();
set_session('ss_qa_write_token', $token);

$g5['title'] = $qaconfig['qa_title'];
include_once(__DIR__ . '/qahead.php');

$skin_file = $qa_skin_path . '/write.skin.php';

if (is_file($skin_file)) {
    /*==========================
    $w == a : Answer
    $w == r : Additional question
    $w == u : Modify
    ==========================*/

    if ($w == 'u' || $w == 'r') {
        $sql = " select * from {$g5['qa_content_table']} where qa_id = '$qa_id' ";
        if (!$is_admin) {
            $sql .= " and mb_id = '{$member['mb_id']}' ";
        }

        $write = sql_fetch($sql);

        if ($w == 'u') {
            if (!$write['qa_id']) {
                alert('The post does not exist.\nIt may have been deleted or is not your own post.');
            }

            if (!$is_admin) {
                if ($write['qa_type'] == 0 && $write['qa_status'] == 1) {
                    alert('Inquiries with registered answers cannot be modified.');
                }

                if ($write['mb_id'] != $member['mb_id']) {
                    alert('You do not have permission to modify the post.\n\nPlease use the correct method.', G5_URL);
                }
            }
        }
    }

    // Category
    $category_option = '';
    if (trim($qaconfig['qa_category']) !== '' && trim($qaconfig['qa_category']) !== '0') {
        $category = explode('|', $qaconfig['qa_category']);
        $counter = count($category);
        for ($i = 0; $i < $counter; $i++) {
            $category_option .= option_selected($category[$i], $write['qa_category']);
        }
    } else {
        alert('Please set the category in the 1:1 inquiry settings');
    }

    $is_dhtml_editor = false;
    if ($config['cf_editor'] && $qaconfig['qa_use_editor'] && (!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE)) {
        $is_dhtml_editor = true;
    }

    // In additional questions, the title is left blank
    if ($w == 'r') {
        $write['qa_subject'] = '';
    }

    $content = '';
    if ($w == '') {
        $content = html_purifier($qaconfig['qa_insert_content']);
    } elseif ($w == 'r') {
        if ($is_dhtml_editor) {
            $content = '<div><br><br><br>====== Previous answer content =======<br></div>';
        } else {
            $content = "\n\n\n\n====== Previous answer content =======\n";
        }
        // KISA Vulnerability Recommendation Stored XSS (210624)
        $content .= get_text(html_purifier($write['qa_content']), 0);
    } else {
        //$content = get_text($write['qa_content'], 0);

        // KISA Vulnerability Recommendation Stored XSS
        $content = get_text(html_purifier($write['qa_content']), 0);
    }

    $editor_html = editor_html('qa_content', $content);
    $editor_js = '';
    $editor_js .= get_editor_js('qa_content');
    $editor_js .= chk_editor_js('qa_content');

    $upload_max_filesize = number_format($qaconfig['qa_upload_size']) . ' bytes';

    $html_value = '';
    $html_checked = '';
    if (isset($write['qa_html']) && $write['qa_html']) {
        $html_checked = 'checked';
        $html_value = $write['qa_html'];

        if ($w == 'r' && $write['qa_html'] == 1 && !$is_dhtml_editor) {
            $html_value = 2;
        }
    }

    $is_email = false;
    $req_email = '';
    if ($qaconfig['qa_use_email']) {
        $is_email = true;

        if ($qaconfig['qa_req_email']) {
            $req_email = 'required';
        }

        if ($w == '' || $w == 'r') {
            $write['qa_email'] = $member['mb_email'];
        }

        if ($w == 'u' && $is_admin && $write['qa_type']) {
            $is_email = false;
        }
    }

    $is_hp = false;
    $req_hp = '';
    if ($qaconfig['qa_use_hp']) {
        $is_hp = true;

        if ($qaconfig['qa_req_hp']) {
            $req_hp = 'required';
        }

        if ($w == '' || $w == 'r') {
            $write['qa_hp'] = $member['mb_hp'];
        }

        if ($w == 'u' && $is_admin && $write['qa_type']) {
            $is_hp = false;
        }
    }

    $list_href = G5_BBS_URL . '/qalist.php' . preg_replace('/^&amp;/', '?', $qstr);

    $action_url = https_url(G5_BBS_DIR) . '/qawrite_update.php';

    include_once($skin_file);
} else {
    echo '<div>' . str_replace(G5_PATH . '/', '', $skin_file) . ' does not exist.</div>';
}

include_once(__DIR__ . '/qatail.php');