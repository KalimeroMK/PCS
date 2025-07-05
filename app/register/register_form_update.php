<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
include_once(G5_LIB_PATH . '/register.lib.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/thumbnail.lib.php');

// Referer check
referer_check();

if ($w != '' && $w != 'u') {
    alert('The w value was not passed correctly.');
}

if ($w == 'u' && $is_admin == 'super' && file_exists(G5_PATH . '/DEMO')) {
    alert('This action cannot be performed on the demo screen.');
}

if (run_replace('register_member_chk_captcha', !chk_captcha(), $w)) {
    alert('The anti-spam number is incorrect.');
}

if ($w == 'u') {
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';
} elseif ($w == '') {
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
} else {
    alert('Invalid access.', G5_URL);
}

if (!$mb_id) {
    alert('Member ID is missing. Please use the correct method.');
}

$mb_password = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';
$mb_password_re = isset($_POST['mb_password_re']) ? trim($_POST['mb_password_re']) : '';
$mb_name = isset($_POST['mb_name']) ? trim($_POST['mb_name']) : '';
$mb_nick = isset($_POST['mb_nick']) ? trim($_POST['mb_nick']) : '';
$mb_email = isset($_POST['mb_email']) ? trim($_POST['mb_email']) : '';
$mb_sex = isset($_POST['mb_sex']) ? trim($_POST['mb_sex']) : "";
$mb_birth = isset($_POST['mb_birth']) ? trim($_POST['mb_birth']) : "";
$mb_homepage = isset($_POST['mb_homepage']) ? trim($_POST['mb_homepage']) : "";
$mb_tel = isset($_POST['mb_tel']) ? trim($_POST['mb_tel']) : "";
$mb_hp = isset($_POST['mb_hp']) ? trim($_POST['mb_hp']) : "";
$mb_zip1 = isset($_POST['mb_zip']) ? substr(trim($_POST['mb_zip']), 0, 3) : "";
$mb_zip2 = isset($_POST['mb_zip']) ? substr(trim($_POST['mb_zip']), 3) : "";
$mb_addr1 = isset($_POST['mb_addr1']) ? trim($_POST['mb_addr1']) : "";
$mb_addr2 = isset($_POST['mb_addr2']) ? trim($_POST['mb_addr2']) : "";
$mb_addr3 = isset($_POST['mb_addr3']) ? trim($_POST['mb_addr3']) : "";
$mb_addr_jibeon = isset($_POST['mb_addr_jibeon']) ? trim($_POST['mb_addr_jibeon']) : "";
$mb_signature = isset($_POST['mb_signature']) ? trim($_POST['mb_signature']) : "";
$mb_profile = isset($_POST['mb_profile']) ? trim($_POST['mb_profile']) : "";
$mb_recommend = isset($_POST['mb_recommend']) ? trim($_POST['mb_recommend']) : "";
$mb_mailling = isset($_POST['mb_mailling']) ? trim($_POST['mb_mailling']) : "";
$mb_sms = isset($_POST['mb_sms']) ? trim($_POST['mb_sms']) : "";
$mb_open = isset($_POST['mb_open']) ? trim($_POST['mb_open']) : "0";
$mb_1 = isset($_POST['mb_1']) ? trim($_POST['mb_1']) : "";
$mb_2 = isset($_POST['mb_2']) ? trim($_POST['mb_2']) : "";
$mb_3 = isset($_POST['mb_3']) ? trim($_POST['mb_3']) : "";
$mb_4 = isset($_POST['mb_4']) ? trim($_POST['mb_4']) : "";
$mb_5 = isset($_POST['mb_5']) ? trim($_POST['mb_5']) : "";
$mb_6 = isset($_POST['mb_6']) ? trim($_POST['mb_6']) : "";
$mb_7 = isset($_POST['mb_7']) ? trim($_POST['mb_7']) : "";
$mb_8 = isset($_POST['mb_8']) ? trim($_POST['mb_8']) : "";
$mb_9 = isset($_POST['mb_9']) ? trim($_POST['mb_9']) : "";
$mb_10 = isset($_POST['mb_10']) ? trim($_POST['mb_10']) : "";

$mb_name = clean_xss_tags($mb_name);
$mb_email = get_email_address($mb_email);
$mb_homepage = clean_xss_tags($mb_homepage);
$mb_tel = clean_xss_tags($mb_tel);
$mb_zip1 = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2 = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1 = clean_xss_tags($mb_addr1);
$mb_addr2 = clean_xss_tags($mb_addr2);
$mb_addr3 = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

run_event('register_form_update_before', $mb_id, $w);

if ($w == '' || $w == 'u') {
    if ($msg = empty_mb_id($mb_id)) {
        alert($msg, "", true, true);
    } // alert($msg, $url, $error, $post);
    if ($msg = valid_mb_id($mb_id)) {
        alert($msg, "", true, true);
    }
    if ($msg = count_mb_id($mb_id)) {
        alert($msg, "", true, true);
    }

    // Error if name or nickname contains characters other than utf-8
    // May not be checked correctly depending on the server environment.
    $tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
    if ($tmp_mb_name != $mb_name) {
        alert('Please enter your name correctly.');
    }
    $tmp_mb_nick = iconv('UTF-8', 'UTF-8//IGNORE', $mb_nick);
    if ($tmp_mb_nick != $mb_nick) {
        alert('Please enter your nickname correctly.');
    }

    // The default state for checking the password is true. To skip the password check, you must change it to false via a hook.
    $is_check_password = run_replace('register_member_password_check', true, $mb_id, $mb_nick, $mb_email, $w);

    if ($is_check_password) {
        if ($w == '' && !$mb_password) {
            alert('Password was not provided.');
        }
        if ($w == '' && $mb_password !== $mb_password_re) {
            alert('Passwords do not match.');
        }
    }

    if ($msg = empty_mb_name($mb_name)) {
        alert($msg, "", true, true);
    }
    if ($msg = empty_mb_nick($mb_nick)) {
        alert($msg, "", true, true);
    }
    if ($msg = empty_mb_email($mb_email)) {
        alert($msg, "", true, true);
    }
    if ($msg = reserve_mb_id($mb_id)) {
        alert($msg, "", true, true);
    }
    if ($msg = reserve_mb_nick($mb_nick)) {
        alert($msg, "", true, true);
    }
    // Names do not require Korean name checks.
    //if ($msg = valid_mb_name($mb_name))     alert($msg, "", true, true);
    if ($msg = valid_mb_nick($mb_nick)) {
        alert($msg, "", true, true);
    }
    if ($msg = valid_mb_email($mb_email)) {
        alert($msg, "", true, true);
    }
    if ($msg = prohibit_mb_email($mb_email)) {
        alert($msg, "", true, true);
    }

    // If mobile phone is required, check mobile phone number validity
    if (($config['cf_use_hp'] || $config['cf_cert_hp'] || $config['cf_cert_simple']) && $config['cf_req_hp'] && $msg = valid_mb_hp($mb_hp)) {
        alert($msg, "", true, true);
    }

    if ($w == '') {
        if ($msg = exist_mb_id($mb_id)) {
            alert($msg);
        }

        if (get_session('ss_check_mb_id') != $mb_id || get_session('ss_check_mb_nick') != $mb_nick || get_session('ss_check_mb_email') != $mb_email) {
            set_session('ss_check_mb_id', '');
            set_session('ss_check_mb_nick', '');
            set_session('ss_check_mb_email', '');

            alert('Please use the correct method.');
        }

        // Identity verification check
        if ($config['cf_cert_use'] && $config['cf_cert_req']) {
            $post_cert_no = isset($_POST['cert_no']) ? trim($_POST['cert_no']) : '';
            if ($post_cert_no !== get_session('ss_cert_no') || !get_session('ss_cert_no')) {
                alert("You must verify your identity to register.");
            }
        }

        if ($config['cf_use_recommend'] && $mb_recommend && !exist_mb_id($mb_recommend)) {
            alert("The recommender does not exist.");
        }

        if (strtolower($mb_id) === strtolower($mb_recommend)) {
            alert('You cannot recommend yourself.');
        }
    } else {
        // Fix a bug that allows information to be changed via JavaScript
        // If the nickname modification date has not passed
        if ($member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) {
            $mb_nick = $member['mb_nick'];
        }
        // Move the member's email to the old email and compare below
        $old_email = $member['mb_email'];
    }

    run_event('register_form_update_valid', $w, $mb_id, $mb_nick, $mb_email);

    if ($msg = exist_mb_nick($mb_nick, $mb_id)) {
        alert($msg, "", true, true);
    }
    if ($msg = exist_mb_email($mb_email, $mb_id)) {
        alert($msg, "", true, true);
    }
}

// Execute user code
@include_once($member_skin_path . '/register_form_update.head.skin.php');

//===============================================================
//  Identity verification
//---------------------------------------------------------------
$mb_hp = hyphen_hp_number($mb_hp);
if ($config['cf_cert_use'] && get_session('ss_cert_type') && get_session('ss_cert_dupinfo')) {
    // Duplicate check
    $sql = " select mb_id from {$g5['member_table']} where mb_id <> '{$member['mb_id']}' and mb_dupinfo = '" . get_session('ss_cert_dupinfo') . "' ";
    $row = sql_fetch($sql);
    if (!empty($row['mb_id'])) {
        alert("A record with the same identity verification information already exists.");
    }
}

$sql_certify = '';
$md5_cert_no = get_session('ss_cert_no');
$cert_type = get_session('ss_cert_type');
if ($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
    // Save identity verification values only if the hash values match.
    if ($cert_type == 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $md5_cert_no)) {
        // IPIN: Check hash value without HP
        $sql_certify .= " , mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify  = '{$cert_type}' ";
        $sql_certify .= " , mb_adult = '" . get_session('ss_cert_adult') . "' ";
        $sql_certify .= " , mb_birth = '" . get_session('ss_cert_birth') . "' ";
        $sql_certify .= " , mb_sex = '" . get_session('ss_cert_sex') . "' ";
        $sql_certify .= " , mb_dupinfo = '" . get_session('ss_cert_dupinfo') . "' ";
        if ($w == 'u') {
            $sql_certify .= " , mb_name = '{$mb_name}' ";
        }
    } elseif ($cert_type != 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $mb_hp . $md5_cert_no)) {
        // Simple authentication, mobile phone: Check hash value with HP
        $sql_certify .= " , mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify  = '{$cert_type}' ";
        $sql_certify .= " , mb_adult = '" . get_session('ss_cert_adult') . "' ";
        $sql_certify .= " , mb_birth = '" . get_session('ss_cert_birth') . "' ";
        $sql_certify .= " , mb_sex = '" . get_session('ss_cert_sex') . "' ";
        $sql_certify .= " , mb_dupinfo = '" . get_session('ss_cert_dupinfo') . "' ";
        if ($w == 'u') {
            $sql_certify .= " , mb_name = '{$mb_name}' ";
        }
    } else {
        alert('The identity verification information does not match the member information. Please try again.');
    }
} elseif (get_session("ss_reg_mb_name") != $mb_name || get_session("ss_reg_mb_hp") != $mb_hp) {
    $sql_certify .= " , mb_hp = '{$mb_hp}' ";
    $sql_certify .= " , mb_certify = '' ";
    $sql_certify .= " , mb_adult = 0 ";
    $sql_certify .= " , mb_birth = '' ";
    $sql_certify .= " , mb_sex = '' ";
}
//===============================================================
if ($w == '') {
    $sql = " insert into {$g5['member_table']}
                set mb_id = '{$mb_id}',
                     mb_password = '" . get_encrypt_string($mb_password) . "',
                     mb_name = '{$mb_name}',
                     mb_nick = '{$mb_nick}',
                     mb_nick_date = '" . G5_TIME_YMD . "',
                     mb_email = '{$mb_email}',
                     mb_homepage = '{$mb_homepage}',
                     mb_tel = '{$mb_tel}',
                     mb_zip1 = '{$mb_zip1}',
                     mb_zip2 = '{$mb_zip2}',
                     mb_addr1 = '{$mb_addr1}',
                     mb_addr2 = '{$mb_addr2}',
                     mb_addr3 = '{$mb_addr3}',
                     mb_addr_jibeon = '{$mb_addr_jibeon}',
                     mb_signature = '{$mb_signature}',
                     mb_profile = '{$mb_profile}',
                     mb_today_login = '" . G5_TIME_YMDHIS . "',
                     mb_datetime = '" . G5_TIME_YMDHIS . "',
                     mb_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_level = '{$config['cf_register_level']}',
                     mb_recommend = '{$mb_recommend}',
                     mb_login_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_mailling = '{$mb_mailling}',
                     mb_sms = '{$mb_sms}',
                     mb_open = '{$mb_open}',
                     mb_open_date = '" . G5_TIME_YMD . "',
                     mb_1 = '{$mb_1}',
                     mb_2 = '{$mb_2}',
                     mb_3 = '{$mb_3}',
                     mb_4 = '{$mb_4}',
                     mb_5 = '{$mb_5}',
                     mb_6 = '{$mb_6}',
                     mb_7 = '{$mb_7}',
                     mb_8 = '{$mb_8}',
                     mb_9 = '{$mb_9}',
                     mb_10 = '{$mb_10}'
                     {$sql_certify} ";
    // If email authentication is not used, set the email authentication time immediately
    if (!$config['cf_use_email_certify']) {
        $sql .= " , mb_email_certify = '" . G5_TIME_YMDHIS . "' ";
    }
    sql_query($sql);
    // Grant registration points
    insert_point($mb_id, $config['cf_register_point'], 'Congratulations on registering', '@member', $mb_id, 'Registration');
    // Grant points to the recommender
    if ($config['cf_use_recommend'] && $mb_recommend) {
        insert_point($mb_recommend, $config['cf_recommend_point'], $mb_id . 's recommender', '@member', $mb_recommend,
            $mb_id . ' recommendation');
    }
    // Send an email to the member
    if ($config['cf_email_mb_member']) {
        $subject = '[' . $config['cf_title'] . '] Congratulations on registering.';

        // Create a one-time random number for authentication
        if ($config['cf_use_email_certify']) {
            $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
            sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");
            $certify_href = G5_BBS_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;
        }

        ob_start();
        include_once(__DIR__ . '/register_form_update_mail1.php');
        $content = ob_get_contents();
        ob_end_clean();

        $content = run_replace('register_form_update_mail_mb_content', $content, $mb_id);

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

        run_event('register_form_update_send_mb_mail', $config['cf_admin_email_name'], $config['cf_admin_email'],
            $mb_email, $subject, $content);

        // If email authentication is used, do not send the authentication email again
        if ($config['cf_use_email_certify']) {
            $old_email = $mb_email;
        }
    }
    // Send an email to the administrator
    if ($config['cf_email_mb_super_admin']) {
        $subject = run_replace('register_form_update_mail_admin_subject',
            '[' . $config['cf_title'] . '] ' . $mb_nick . ' has registered.', $mb_id, $mb_nick);

        ob_start();
        include_once(__DIR__ . '/register_form_update_mail2.php');
        $content = ob_get_contents();
        ob_end_clean();

        $content = run_replace('register_form_update_mail_admin_content', $content, $mb_id);

        mailer($mb_nick, $mb_email, $config['cf_admin_email'], $subject, $content, 1);

        run_event('register_form_update_send_admin_mail', $mb_nick, $mb_email, $config['cf_admin_email'], $subject,
            $content);
    }
    // Log in if email authentication is not used
    if (!$config['cf_use_email_certify']) {
        set_session('ss_mb_id', $mb_id);
        if (function_exists('update_auth_session_token')) {
            update_auth_session_token(G5_TIME_YMDHIS);
        }
    }
    set_session('ss_mb_reg', $mb_id);
    if ($cert_type == 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $md5_cert_no)) {
        // IPIN: Check hash value without HP
        insert_member_cert_history($mb_id, $mb_name, $mb_hp, get_session('ss_cert_birth'),
            get_session('ss_cert_type'));
        // Record identity verification history after modification
    } elseif ($cert_type != 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $mb_hp . $md5_cert_no)) {
        // Simple authentication, mobile phone: Check hash value with HP
        insert_member_cert_history($mb_id, $mb_name, $mb_hp, get_session('ss_cert_birth'),
            get_session('ss_cert_type'));
        // Record identity verification history after modification
    }
} elseif ($w == 'u') {
    if (trim(get_session('ss_mb_id')) === '' || trim(get_session('ss_mb_id')) === '0') {
        alert('You are not logged in.');
    }
    if (trim($_POST['mb_id']) != $mb_id) {
        alert("The logged-in information and the information you are trying to modify do not match.\\nPlease do not use incorrect methods.");
    }
    $sql_password = "";
    if ($mb_password !== '' && $mb_password !== '0') {
        $sql_password = " , mb_password = '" . get_encrypt_string($mb_password) . "' ";
    }
    $sql_nick_date = "";
    if ($mb_nick_default != $mb_nick) {
        $sql_nick_date = " , mb_nick_date = '" . G5_TIME_YMD . "' ";
    }
    $sql_open_date = "";
    if ($mb_open_default != $mb_open) {
        $sql_open_date = " , mb_open_date = '" . G5_TIME_YMD . "' ";
    }
    // If the old email address and the modified email address are different, delete the authentication value
    $sql_email_certify = '';
    if ($old_email != $mb_email && $config['cf_use_email_certify']) {
        $sql_email_certify = " , mb_email_certify = '' ";
    }
    $sql = " update {$g5['member_table']}
                set mb_nick = '{$mb_nick}',
                    mb_mailling = '{$mb_mailling}',
                    mb_sms = '{$mb_sms}',
                    mb_open = '{$mb_open}',
                    mb_email = '{$mb_email}',
                    mb_homepage = '{$mb_homepage}',
                    mb_tel = '{$mb_tel}',
                    mb_zip1 = '{$mb_zip1}',
                    mb_zip2 = '{$mb_zip2}',
                    mb_addr1 = '{$mb_addr1}',
                    mb_addr2 = '{$mb_addr2}',
                    mb_addr3 = '{$mb_addr3}',
                    mb_addr_jibeon = '{$mb_addr_jibeon}',
                    mb_signature = '{$mb_signature}',
                    mb_profile = '{$mb_profile}',
                    mb_1 = '{$mb_1}',
                    mb_2 = '{$mb_2}',
                    mb_3 = '{$mb_3}',
                    mb_4 = '{$mb_4}',
                    mb_5 = '{$mb_5}',
                    mb_6 = '{$mb_6}',
                    mb_7 = '{$mb_7}',
                    mb_8 = '{$mb_8}',
                    mb_9 = '{$mb_9}',
                    mb_10 = '{$mb_10}'
                    {$sql_password}
                    {$sql_nick_date}
                    {$sql_open_date}
                    {$sql_email_certify}
                    {$sql_certify}
              where mb_id = '$mb_id' ";
    sql_query($sql);
    if ($cert_type == 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $md5_cert_no)) {
        // IPIN: Check hash value without HP
        insert_member_cert_history($mb_id, $mb_name, $mb_hp, get_session('ss_cert_birth'),
            get_session('ss_cert_type'));
        // Record identity verification history after modification
    } elseif ($cert_type != 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $mb_hp . $md5_cert_no)) {
        // Simple authentication, mobile phone: Check hash value with HP
        insert_member_cert_history($mb_id, $mb_name, $mb_hp, get_session('ss_cert_birth'),
            get_session('ss_cert_type'));
        // Record identity verification history after modification
    }
}


// Member icon
$mb_dir = G5_DATA_PATH . '/member/' . substr($mb_id, 0, 2);

// Delete icon
if (isset($_POST['del_mb_icon'])) {
    @unlink($mb_dir . '/' . get_mb_icon_name($mb_id) . '.gif');
}

$msg = "";

// Upload icon
$mb_icon = '';
$image_regex = "/(\.(gif|jpe?g|png))$/i";
$mb_icon_img = get_mb_icon_name($mb_id) . '.gif';

if (isset($_FILES['mb_icon']) && is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
    if (preg_match($image_regex, $_FILES['mb_icon']['name'])) {
        // Only upload icons with a size less than or equal to the set value
        if ($_FILES['mb_icon']['size'] <= $config['cf_member_icon_size']) {
            @mkdir($mb_dir, G5_DIR_PERMISSION);
            @chmod($mb_dir, G5_DIR_PERMISSION);
            $dest_path = $mb_dir . '/' . $mb_icon_img;
            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            if (file_exists($dest_path)) {
                //=================================================================\
                // 090714
                // Prevent uploading images with malicious code
                // Do not display error messages
                //-----------------------------------------------------------------
                $size = @getimagesize($dest_path);
                if (!($size[2] === 1 || $size[2] === 2 || $size[2] === 3)) {
                    // If the file is not a GIF, JPG, or PNG, delete the uploaded image
                    @unlink($dest_path);
                } elseif ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                    $thumb = null;
                    if ($size[2] === 2 || $size[2] === 3) {
                        // Apply JPG or PNG
                        $thumb = thumbnail($mb_icon_img, $mb_dir, $mb_dir, $config['cf_member_icon_width'],
                            $config['cf_member_icon_height'], true, true);
                        if ($thumb) {
                            @unlink($dest_path);
                            rename($mb_dir . '/' . $thumb, $dest_path);
                        }
                    }
                    if (!$thumb) {
                        // If the icon's width or height exceeds the set value, delete the uploaded icon
                        @unlink($dest_path);
                    }
                }
                //=================================================================\
            }
        } else {
            $msg .= 'Please upload the member icon with a size less than or equal to ' . number_format($config['cf_member_icon_size']) . ' bytes.';
        }
    } else {
        $msg .= $_FILES['mb_icon']['name'] . ' is not an image file.';
    }
}

// Member profile image
if ($config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {
    $mb_tmp_dir = G5_DATA_PATH . '/member_image/';
    $mb_dir = $mb_tmp_dir . substr($mb_id, 0, 2);

    // Delete icon
    if (isset($_POST['del_mb_img'])) {
        @unlink($mb_dir . '/' . $mb_icon_img);
    }

    // Upload member profile image
    $mb_img = '';
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
        $msg = $msg !== '' && $msg !== '0' ? $msg . "\\r\\n" : '';

        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            // Only upload images with a size less than or equal to the set value
            if ($_FILES['mb_img']['size'] <= $config['cf_member_img_size']) {
                @mkdir($mb_dir, G5_DIR_PERMISSION);
                @chmod($mb_dir, G5_DIR_PERMISSION);
                $dest_path = $mb_dir . '/' . $mb_icon_img;
                move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
                chmod($dest_path, G5_FILE_PERMISSION);
                if (file_exists($dest_path)) {
                    $size = @getimagesize($dest_path);
                    if (!($size[2] === 1 || $size[2] === 2 || $size[2] === 3)) {
                        // If the file is not a GIF, JPG, or PNG, delete the uploaded image
                        @unlink($dest_path);
                    } elseif ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                        $thumb = null;
                        if ($size[2] === 2 || $size[2] === 3) {
                            // Apply JPG or PNG
                            $thumb = thumbnail($mb_icon_img, $mb_dir, $mb_dir, $config['cf_member_img_width'],
                                $config['cf_member_img_height'], true, true);
                            if ($thumb) {
                                @unlink($dest_path);
                                rename($mb_dir . '/' . $thumb, $dest_path);
                            }
                        }
                        if (!$thumb) {
                            // If the icon's width or height exceeds the set value, delete the uploaded icon
                            @unlink($dest_path);
                        }
                    }
                    //=================================================================\
                }
            } else {
                $msg .= 'Please upload the member image with a size less than or equal to ' . number_format($config['cf_member_img_size']) . ' bytes.';
            }
        } else {
            $msg .= $_FILES['mb_img']['name'] . ' is not a GIF/JPG file.';
        }
    }
}

// Send authentication email
if ($config['cf_use_email_certify'] && $old_email != $mb_email) {
    $subject = '[' . $config['cf_title'] . '] Authentication email.';

    // Create a one-time random number for authentication
    $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));

    sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");

    $certify_href = G5_BBS_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;

    ob_start();
    include_once(__DIR__ . '/register_form_update_mail3.php');
    $content = ob_get_contents();
    ob_end_clean();

    $content = run_replace('register_form_update_mail_certify_content', $content, $mb_id);

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

    run_event('register_form_update_send_certify_mail', $config['cf_admin_email_name'], $config['cf_admin_email'],
        $mb_email, $subject, $content);
}


// Generate new member coupons
if ($w == '' && $default['de_member_reg_coupon_use'] && $default['de_member_reg_coupon_term'] > 0 && $default['de_member_reg_coupon_price'] > 0) {
    $j = 0;
    $create_coupon = false;

    do {
        $cp_id = get_coupon_id();

        $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
        $row3 = sql_fetch($sql3);

        if (!$row3['cnt']) {
            $create_coupon = true;
            break;
        } elseif ($j > 20) {
            break;
        }
    } while (1);

    if ($create_coupon) {
        $cp_subject = 'New member registration coupon';
        $cp_method = 2;
        $cp_target = '';
        $cp_start = G5_TIME_YMD;
        $cp_end = date("Y-m-d", (G5_SERVER_TIME + (86400 * ((int)$default['de_member_reg_coupon_term'] - 1))));
        $cp_type = 0;
        $cp_price = $default['de_member_reg_coupon_price'];
        $cp_trunc = 1;
        $cp_minimum = $default['de_member_reg_coupon_minimum'];
        $cp_maximum = 0;

        $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
                    ( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime )
                VALUES
                    ( '$cp_id', '$cp_subject', '$cp_method', '$cp_target', '$mb_id', '$cp_start', '$cp_end', '$cp_type', '$cp_price', '$cp_trunc', '$cp_minimum', '$cp_maximum', '" . G5_TIME_YMDHIS . "' ) ";

        $res = sql_query($sql, false);

        if ($res) {
            set_session('ss_member_reg_coupon', 1);
        }
    }
}


// Execute user code
@include_once($member_skin_path . '/register_form_update.tail.skin.php');

if (isset($_SESSION['ss_cert_type'])) {
    unset($_SESSION['ss_cert_type']);
}
if (isset($_SESSION['ss_cert_no'])) {
    unset($_SESSION['ss_cert_no']);
}
if (isset($_SESSION['ss_cert_hash'])) {
    unset($_SESSION['ss_cert_hash']);
}
if (isset($_SESSION['ss_cert_birth'])) {
    unset($_SESSION['ss_cert_birth']);
}
if (isset($_SESSION['ss_cert_adult'])) {
    unset($_SESSION['ss_cert_adult']);
}

if ($msg !== '' && $msg !== '0') {
    echo '<script>alert(\'' . $msg . '\');</script>';
}

run_event('register_form_update_after', $mb_id, $w);

if ($w == '') {
    goto_url(G5_HTTP_BBS_URL . '/register_result.php');
} elseif ($w == 'u') {
    $row = sql_fetch(" select mb_password from {$g5['member_table']} where mb_id = '{$member['mb_id']}' ");
    $tmp_password = $row['mb_password'];
    if ($old_email != $mb_email && $config['cf_use_email_certify']) {
        set_session('ss_mb_id', '');
        alert('Your information has been modified.\n\nYou need to re-authenticate your email address.', G5_URL);
    } else {
        echo '
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <title>Modify member information</title>
        <body>
        <form name="fregisterupdate" method="post" action="' . G5_HTTP_BBS_URL . '/register_form.php">
        <input type="hidden" name="w" value="u">
        <input type="hidden" name="mb_id" value="' . $mb_id . '">
        <input type="hidden" name="mb_password" value="' . $tmp_password . '">
        <input type="hidden" name="is_update" value="1">
        </form>
        <script>
        alert("Your information has been modified.");
        document.fregisterupdate.submit();
        </script>
        </body>
        </html>';
    }
}