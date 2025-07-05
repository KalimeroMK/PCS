<?php

include_once(__DIR__ . '/../common.php');

/*==========================
$w == a : Answer
$w == r : Additional question
$w == u : Modify
==========================*/

if ($is_guest) {
    alert('If you are a member, please log in to use this service.', './login.php?url=' . urlencode(G5_BBS_URL . '/qalist.php'));
}

$msg = [];

$write_token = get_session('ss_qa_write_token');
set_session('ss_qa_write_token', '');

$token = isset($_POST['token']) ? clean_xss_tags($_POST['token'], 1, 1) : '';

// Validate all members' tokens.
if (!($token && $write_token === $token)) {
    alert('Please use the correct method.');
}

// 1:1 inquiry settings
$qaconfig = get_qa_config();
$qa_id = isset($_POST['qa_id']) ? (int)$_POST['qa_id'] : 0;

if (trim($qaconfig['qa_category']) !== '' && trim($qaconfig['qa_category']) !== '0') {
    if ($w != 'a') {
        $category = explode('|', $qaconfig['qa_category']);
        if (!in_array($qa_category, $category)) {
            alert('Please specify the category correctly.');
        }
    }
} else {
    alert('Please set the category in the 1:1 inquiry settings');
}

// e-mail check
$qa_email = '';
if (isset($_POST['qa_email']) && $_POST['qa_email']) {
    $qa_email = get_email_address(trim($_POST['qa_email']));
}

if ($w != 'a' && $qaconfig['qa_req_email'] && !$qa_email) {
    $msg[] = 'Please enter your email.';
}

$qa_subject = '';
if (isset($_POST['qa_subject'])) {
    $qa_subject = substr(trim($_POST['qa_subject']), 0, 255);
    $qa_subject = preg_replace("#[\\\\]+$#", "", $qa_subject);
}
if ($qa_subject == '') {
    $msg[] = '<strong>Subject</strong> is required.';
}

$qa_content = '';
if (isset($_POST['qa_content'])) {
    $qa_content = substr(trim($_POST['qa_content']), 0, 65536);
    $qa_content = preg_replace("#[\\\\]+$#", "", $qa_content);
}
if ($qa_content == '') {
    $msg[] = '<strong>Content</strong> is required.';
}

if (!empty($msg)) {
    $msg = implode('<br>', $msg);
    alert($msg);
}

$qa_hp = isset($_POST['qa_hp']) ? preg_replace('/[^0-9\-]/', '', $_POST['qa_hp']) : '';

// 090710
if (substr_count($qa_content, '&#') > 50) {
    alert('The content contains multiple invalid codes.');
    exit;
}

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST)) {
    alert("An error occurred because the file or content size exceeds the server's configured value.\npost_max_size=" . ini_get('post_max_size') . " , upload_max_filesize=" . $upload_max_filesize . "\nPlease contact the board administrator or server administrator.");
}

$qa_type = 0;
$qa_parent = 0;
$qa_related = 0;
$qa_email_recv = (isset($_POST['qa_email_recv']) && $_POST['qa_email_recv']) ? 1 : 0;
$qa_sms_recv = (isset($_POST['qa_sms_recv']) && $_POST['qa_sms_recv']) ? 1 : 0;
$qa_status = 0;
$answer_id = null;

for ($i = 1; $i <= 5; $i++) {
    $var = "qa_$i";
    $$var = "";
    if (isset($_POST['qa_' . $i]) && $_POST['qa_' . $i]) {
        $$var = trim($_POST['qa_' . $i]);
    }
}

if ($w == 'u' || $w == 'a' || $w == 'r') {
    if ($w == 'a' && !$is_admin) {
        alert('Only administrators can register answers.');
    }

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

    if ($w == 'a') {
        if (!$write['qa_id']) {
            alert('Cannot register an answer because the inquiry post does not exist.');
        }

        if ($write['qa_type'] == 1) {
            alert('You cannot reply to an answer post again.');
        }
    }
}

// Check file count
$file_count = 0;
$upload_count = isset($_FILES['bf_file']['name']) ? count($_FILES['bf_file']['name']) : 0;

for ($i = 1; $i <= $upload_count; $i++) {
    if ($_FILES['bf_file']['name'][$i] && is_uploaded_file($_FILES['bf_file']['tmp_name'][$i])) {
        $file_count++;
    }
}

if ($file_count > 2) {
    alert('Please upload 2 or fewer attachments.');
}

// If the directory does not exist, create it. (And change permissions.)
@mkdir(G5_DATA_PATH . '/qa', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH . '/qa', G5_DIR_PERMISSION);

$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

// Variable file upload
$file_upload_msg = '';
$upload = [];
for ($i = 1; $i <= $upload_count; $i++) {
    $upload[$i]['file'] = '';
    $upload[$i]['source'] = '';
    $upload[$i]['del_check'] = false;

    // If deletion is checked, delete the file.
    if (isset($_POST['bf_file_del'][$i]) && $_POST['bf_file_del'][$i]) {
        $upload[$i]['del_check'] = true;
        @unlink(G5_DATA_PATH . '/qa/' . clean_relative_paths($write['qa_file' . $i]));
        // Delete thumbnail
        if (preg_match("/\.({$config['cf_image_extension']})$/i", $write['qa_file' . $i])) {
            delete_qa_thumbnail($write['qa_file' . $i]);
        }
    }

    $tmp_file = $_FILES['bf_file']['tmp_name'][$i];
    $filesize = $_FILES['bf_file']['size'][$i];
    $filename = $_FILES['bf_file']['name'][$i];
    $filename = get_safe_filename($filename);

    // If uploading a file larger than the server's configured value
    if ($filename) {
        if ($_FILES['bf_file']['error'][$i] == 1) {
            $file_upload_msg .= 'The size of file \"' . $filename . '\" exceeds the server limit (' . $upload_max_filesize . ') and cannot be uploaded.\n';
            continue;
        } elseif ($_FILES['bf_file']['error'][$i] != 0) {
            $file_upload_msg .= 'File \"' . $filename . '\" was not uploaded successfully.\n';
            continue;
        }
    }

    if (is_uploaded_file($tmp_file)) {
        // If not an admin and the file size is larger than the configured upload size, skip
        if (!$is_admin && $filesize > $qaconfig['qa_upload_size']) {
            $file_upload_msg .= 'The size of file \"' . $filename . '\" (' . number_format($filesize) . ' bytes) exceeds the board\'s configured limit (' . number_format($qaconfig['qa_upload_size']) . ' bytes) and will not be uploaded.\n';
            continue;
        }

        //=================================================================
        // 090714
        // Prevent uploading malicious code embedded in image or flash files
        // Do not display error messages.
        //-----------------------------------------------------------------
        $timg = @getimagesize($tmp_file);
        // image type
        // Modified to allow upload since webp file type is 18
        if ((preg_match("/\.({$config['cf_image_extension']})$/i", $filename) || preg_match("/\.({$config['cf_flash_extension']})$/i", $filename)) && ($timg['2'] < 1 || $timg['2'] > 18)) {
            continue;
        }
        //=================================================================

        if ($w == 'u') {
            // If the file exists, delete it.
            @unlink(G5_DATA_PATH . '/qa/' . clean_relative_paths($write['qa_file' . $i]));
            // If it is an image file, delete the thumbnail
            if (preg_match("/\.({$config['cf_image_extension']})$/i", $write['qa_file' . $i])) {
                delete_qa_thumbnail($row['qa_file' . $i]);
            }
        }

        // Original program file name
        $upload[$i]['source'] = $filename;
        $upload[$i]['filesize'] = $filesize;

        // Append -x to files containing the following strings to prevent execution even if the web path is known
        $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc|phar)/i", "$0-x", $filename);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);

        // If the attached file name contains spaces, it may not be visible or downloadable on some PCs. (From Gilsang-yeoui, 090925)
        $upload[$i]['file'] = md5(sha1($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0,
                8) . '_' . replace_filename($filename);

        $dest_file = G5_DATA_PATH . '/qa/' . $upload[$i]['file'];

        // If upload fails, print an error message and die.
        if (!$error_code = move_uploaded_file($tmp_file, $dest_file)) {
            die($_FILES['bf_file']['error'][$i]);
        }

        // Change the permission of the uploaded file.
        chmod($dest_file, G5_FILE_PERMISSION);
    }
}

if ($w == '' || $w == 'a' || $w == 'r') {
    if ($w == '' || $w == 'r') {
        $row = sql_fetch(" select MIN(qa_num) as min_qa_num from {$g5['qa_content_table']} ");
        $qa_num = $row['min_qa_num'] - 1;
    }
    if ($w == 'a') {
        $qa_num = $write['qa_num'];
        $qa_parent = $write['qa_id'];
        $qa_related = $write['qa_related'];
        $qa_category = addslashes($write['qa_category']);
        $qa_type = 1;
        $qa_status = 1;
    }
    $insert_qa_file1 = isset($upload[1]['file']) ? $upload[1]['file'] : '';
    $insert_qa_source1 = isset($upload[1]['source']) ? $upload[1]['source'] : '';
    $insert_qa_file2 = isset($upload[2]['file']) ? $upload[2]['file'] : '';
    $insert_qa_source2 = isset($upload[2]['source']) ? $upload[2]['source'] : '';
    $sql = " insert into {$g5['qa_content_table']}
                set qa_num          = '$qa_num',
                    mb_id           = '{$member['mb_id']}',
                    qa_name         = '" . addslashes($member['mb_nick']) . "',
                    qa_email        = '$qa_email',
                    qa_hp           = '$qa_hp',
                    qa_type         = '$qa_type',
                    qa_parent       = '$qa_parent',
                    qa_related      = '$qa_related',
                    qa_category     = '$qa_category',
                    qa_email_recv   = '$qa_email_recv',
                    qa_sms_recv     = '$qa_sms_recv',
                    qa_html         = '$qa_html',
                    qa_subject      = '$qa_subject',
                    qa_content      = '$qa_content',
                    qa_status       = '$qa_status',
                    qa_file1        = '{$insert_qa_file1}',
                    qa_source1      = '{$insert_qa_source1}',
                    qa_file2        = '{$insert_qa_file2}',
                    qa_source2      = '{$insert_qa_source2}',
                    qa_ip           = '{$_SERVER['REMOTE_ADDR']}',
                    qa_datetime     = '" . G5_TIME_YMDHIS . "',
                    qa_1            = '$qa_1',
                    qa_2            = '$qa_2',
                    qa_3            = '$qa_3',
                    qa_4            = '$qa_4',
                    qa_5            = '$qa_5' ";
    sql_query($sql);
    if ($w == '' || $w == 'r') {
        $qa_id = sql_insert_id();

        $qa_related = $w == 'r' && $write['qa_related'] ? $write['qa_related'] : $qa_id;

        $sql = " update {$g5['qa_content_table']}
                    set qa_parent   = '$qa_id',
                        qa_related  = '$qa_related'
                    where qa_id = '$qa_id' ";
        sql_query($sql);
    }
    if ($w == 'a') {
        $answer_id = (int)sql_insert_id();
        $sql = " update {$g5['qa_content_table']}
                    set qa_status = '1'
                    where qa_id = '{$write['qa_parent']}' ";
        sql_query($sql);
    }
} elseif ($w == 'u') {
    if (!$upload[1]['file'] && !$upload[1]['del_check']) {
        $upload[1]['file'] = $write['qa_file1'];
        $upload[1]['source'] = $write['qa_source1'];
    }
    if (!$upload[2]['file'] && !$upload[2]['del_check']) {
        $upload[2]['file'] = $write['qa_file2'];
        $upload[2]['source'] = $write['qa_source2'];
    }
    $sql = " update {$g5['qa_content_table']}
                set qa_email    = '$qa_email',
                    qa_hp       = '$qa_hp',
                    qa_category = '$qa_category',
                    qa_html     = '$qa_html',
                    qa_subject  = '$qa_subject',
                    qa_content  = '$qa_content',
                    qa_file1    = '{$upload[1]['file']}',
                    qa_source1  = '{$upload[1]['source']}',
                    qa_file2    = '{$upload[2]['file']}',
                    qa_source2  = '{$upload[2]['source']}',
                    qa_1        = '$qa_1',
                    qa_2        = '$qa_2',
                    qa_3        = '$qa_3',
                    qa_4        = '$qa_4',
                    qa_5        = '$qa_5' ";
    if ($qa_sms_recv !== 0) {
        $sql .= ", qa_sms_recv = '$qa_sms_recv' ";
    }
    $sql .= " where qa_id = '$qa_id' ";
    sql_query($sql);
}

/**
 * Event Hook for when 1:1 inquiry/answer is changed
 * @var int $qa_id ID of the post to be inserted/modified or replied to/additionally questioned
 * @var array $write Data of the post to be inserted/modified or replied to/additionally questioned
 * @var string $w Operation mode ('': writing a question, 'a': writing an answer, 'u': modifying a question/answer, 'r': additional (related) question)
 * @var array $qaconfig 1:1 inquiry settings
 * @var ?int $answer_id ID of the answer post when writing an answer ($w = 'a')
 */
run_event('qawrite_update', $qa_id, $write, $w, $qaconfig, ($w === 'a') ? $answer_id : null);

// SMS Notification
if ($config['cf_sms_use'] == 'icode' && $qaconfig['qa_use_sms']) {
    if ($config['cf_sms_type'] == 'LMS') {
        include_once(G5_LIB_PATH . '/icode.lms.lib.php');

        $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

        // Create SMS module class
        if ($port_setting !== false) {
            // Send to the question registrant for answer posts
            if ($w == 'a' && $write['qa_sms_recv'] && trim($write['qa_hp'])) {
                $sms_content = $config['cf_title'] . ' ' . $qaconfig['qa_title'] . ' answer has been registered.';
                $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
                $recv_number = preg_replace('/[^0-9]/', '', $write['qa_hp']);

                if ($recv_number) {
                    $strDest = [];
                    $strDest[] = $recv_number;
                    $strCallBack = $send_number;
                    $strCaller = iconv_euckr(trim($config['cf_title']));
                    $strSubject = '';
                    $strURL = '';
                    $strData = iconv_euckr($sms_content);
                    $strDate = '';
                    $nCount = count($strDest);

                    $SMS = new LMS;
                    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'],
                        $port_setting);
                    $res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate,
                        $nCount);

                    if ($res) {
                        $SMS->Send();
                    }

                    $SMS->Init(); // Clear the stored result values.
                }
            }

            // Send to administrator when an inquiry is registered
            if (($w == '' || $w == 'r') && trim($qaconfig['qa_admin_hp'])) {
                $sms_content = $config['cf_title'] . ' ' . $qaconfig['qa_title'] . ' inquiry has been registered.';
                $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
                $recv_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_admin_hp']);

                if ($recv_number) {
                    $strDest = [];
                    $strDest[] = $recv_number;
                    $strCallBack = $send_number;
                    $strCaller = iconv_euckr(trim($config['cf_title']));
                    $strSubject = '';
                    $strURL = '';
                    $strData = iconv_euckr($sms_content);
                    $strDate = '';
                    $nCount = count($strDest);

                    $SMS = new LMS;
                    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'],
                        $port_setting);
                    $res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate,
                        $nCount);

                    if ($res) {
                        $SMS->Send();
                    }

                    $SMS->Init(); // Clear the stored result values.
                }
            }
        }
    } else {
        include_once(G5_LIB_PATH . '/icode.sms.lib.php');

        // Send to the question registrant for answer posts
        if ($w == 'a' && $write['qa_sms_recv'] && trim($write['qa_hp'])) {
            $sms_content = $config['cf_title'] . ' ' . $qaconfig['qa_title'] . ' answer has been registered.';
            $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
            $recv_number = preg_replace('/[^0-9]/', '', $write['qa_hp']);

            if ($recv_number) {
                $SMS = new SMS; // SMS connection
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'],
                    $config['cf_icode_server_port']);
                $SMS->Add($recv_number, $send_number, $config['cf_icode_id'],
                    iconv("utf-8", "euc-kr", stripslashes($sms_content)), "");
                $SMS->Send();
            }
        }

        // Send to administrator when an inquiry is registered
        if (($w == '' || $w == 'r') && trim($qaconfig['qa_admin_hp'])) {
            $sms_content = $config['cf_title'] . ' ' . $qaconfig['qa_title'] . ' inquiry has been registered.';
            $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
            $recv_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_admin_hp']);

            if ($recv_number) {
                $SMS = new SMS; // SMS connection
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'],
                    $config['cf_icode_server_port']);
                $SMS->Add($recv_number, $send_number, $config['cf_icode_id'],
                    iconv("utf-8", "euc-kr", stripslashes($sms_content)), "");
                $SMS->Send();
            }
        }
    }
}

// Send answer email
if ($w == 'a' && $write['qa_email_recv'] && trim($write['qa_email'])) {
    include_once(G5_LIB_PATH . '/mailer.lib.php');

    $subject = $config['cf_title'] . ' ' . $qaconfig['qa_title'] . ' Answer Notification Mail';
    $content = nl2br(conv_unescape_nl(stripslashes($qa_content)));

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $write['qa_email'], $subject, $content, 1);
}

// Send inquiry registration email
if (($w == '' || $w == 'r') && trim($qaconfig['qa_admin_email'])) {
    include_once(G5_LIB_PATH . '/mailer.lib.php');

    $subject = $config['cf_title'] . ' ' . $qaconfig['qa_title'] . ' Question Notification Mail';
    $content = nl2br(conv_unescape_nl(stripslashes($qa_content)));

    mailer($config['cf_admin_email_name'], $qa_email, $qaconfig['qa_admin_email'], $subject, $content, 1);
}

if ($w == 'a') {
    $result_url = G5_BBS_URL . '/qaview.php?qa_id=' . $qa_id . $qstr;
} elseif ($w == 'u' && $write['qa_type']) {
    $result_url = G5_BBS_URL . '/qaview.php?qa_id=' . $write['qa_parent'] . $qstr;
} else {
    $result_url = G5_BBS_URL . '/qalist.php' . preg_replace('/^&amp;/', '?', $qstr);
}

if ($file_upload_msg !== '' && $file_upload_msg !== '0') {
    alert($file_upload_msg, $result_url);
} else {
    goto_url($result_url);
}