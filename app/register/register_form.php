<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
include_once(G5_LIB_PATH . '/register.lib.php');

run_event('register_form_before');

// Create a token to prevent illegal access
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);
set_session("ss_cert_no", "");
set_session("ss_cert_hash", "");
set_session("ss_cert_type", "");

$is_social_login_modify = false;

// If connected via social media on mobile
if (isset($_REQUEST['provider']) && $_REQUEST['provider'] && function_exists('social_nonce_is_valid') && social_nonce_is_valid(get_session("social_link_token"), $provider)) {
    // Check if the token is valid
    $w = 'u';
    // Process as member modification
    $_POST['mb_id'] = $member['mb_id'];
    $is_social_login_modify = true;
}

if ($w == "") {
    // If already logged in, cannot register as a member
    // Replaced with the code below to prevent the alert window from appearing
    // alert("You are already logged in and cannot register.", "./");
    if ($is_member) {
        goto_url(G5_URL);
    }
    // Referer check
    referer_check();
    if (!isset($_POST['agree']) || !$_POST['agree']) {
        alert('You must agree to the terms and conditions to register.', G5_BBS_URL . '/register.php');
    }
    if (!isset($_POST['agree2']) || !$_POST['agree2']) {
        alert('You must agree to the collection and use of personal information to register.', G5_BBS_URL . '/register.php');
    }
    $agree = preg_replace('#[^0-9]#', '', $_POST['agree']);
    $agree2 = preg_replace('#[^0-9]#', '', $_POST['agree2']);
    $member['mb_birth'] = '';
    $member['mb_sex'] = '';
    $member['mb_name'] = '';
    if (isset($_POST['birth'])) {
        $member['mb_birth'] = $_POST['birth'];
    }
    if (isset($_POST['sex'])) {
        $member['mb_sex'] = $_POST['sex'];
    }
    if (isset($_POST['mb_name'])) {
        $member['mb_name'] = $_POST['mb_name'];
    }
    $g5['title'] = 'Sign Up';
} elseif ($w == 'u') {
    if ($is_admin == 'super') {
        alert('Please modify the administrator\'s information on the admin page.', G5_URL);
    }
    if (!$is_member) {
        alert('Please log in to use this service.', G5_URL);
    }
    if ($member['mb_id'] != $_POST['mb_id']) {
        alert('The logged-in member and the submitted information do not match.');
    }
    /*
    if (!($member[mb_password] == sql_password($_POST[mb_password]) && $_POST[mb_password]))
        alert("Incorrect password.");
    
    // Temporarily saved to return to this form after modification
    set_session("ss_tmp_password", $_POST[mb_password]);
    */
    if ($_POST['mb_id'] && !isset($_POST['mb_password']) && $_POST['mb_password'] && !$is_social_login_modify) {
        alert('Please enter your password.');
    }
    if (isset($_POST['mb_password'])) {
        // If returning after updating modified information, the password is encrypted
        if (isset($_POST['is_update']) && $_POST['is_update']) {
            $tmp_password = $_POST['mb_password'];
            $pass_check = ($member['mb_password'] === $tmp_password);
        } else {
            $pass_check = check_password($_POST['mb_password'], $member['mb_password']);
        }

        if (!$pass_check) {
            alert('Incorrect password.');
        }
    }
    $g5['title'] = 'Edit Member Information';
    set_session("ss_reg_mb_name", $member['mb_name']);
    set_session("ss_reg_mb_hp", $member['mb_hp']);
    $member['mb_email'] = get_text($member['mb_email']);
    $member['mb_homepage'] = get_text($member['mb_homepage']);
    $member['mb_birth'] = get_text($member['mb_birth']);
    $member['mb_tel'] = get_text($member['mb_tel']);
    $member['mb_hp'] = get_text($member['mb_hp']);
    $member['mb_addr1'] = get_text($member['mb_addr1']);
    $member['mb_addr2'] = get_text($member['mb_addr2']);
    $member['mb_signature'] = get_text($member['mb_signature']);
    $member['mb_recommend'] = get_text($member['mb_recommend']);
    $member['mb_profile'] = get_text($member['mb_profile']);
    $member['mb_1'] = get_text($member['mb_1']);
    $member['mb_2'] = get_text($member['mb_2']);
    $member['mb_3'] = get_text($member['mb_3']);
    $member['mb_4'] = get_text($member['mb_4']);
    $member['mb_5'] = get_text($member['mb_5']);
    $member['mb_6'] = get_text($member['mb_6']);
    $member['mb_7'] = get_text($member['mb_7']);
    $member['mb_8'] = get_text($member['mb_8']);
    $member['mb_9'] = get_text($member['mb_9']);
    $member['mb_10'] = get_text($member['mb_10']);
} else {
    alert('The w value was not passed correctly.');
}

include_once(__DIR__ . '/../header.php');
// Member icon path
$mb_icon_path = G5_DATA_PATH . '/member/' . substr($member['mb_id'], 0, 2) . '/' . get_mb_icon_name($member['mb_id']) . '.gif';
$mb_icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME && file_exists($mb_icon_path)) ? '?' . filemtime($mb_icon_path) : '';
$mb_icon_url = G5_DATA_URL . '/member/' . substr($member['mb_id'], 0,
        2) . '/' . get_mb_icon_name($member['mb_id']) . '.gif' . $mb_icon_filemtile;

// Member image path
$mb_img_path = G5_DATA_PATH . '/member_image/' . substr($member['mb_id'], 0,
        2) . '/' . get_mb_icon_name($member['mb_id']) . '.gif';
$mb_img_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME && file_exists($mb_img_path)) ? '?' . filemtime($mb_img_path) : '';
$mb_img_url = G5_DATA_URL . '/member_image/' . substr($member['mb_id'], 0,
        2) . '/' . get_mb_icon_name($member['mb_id']) . '.gif' . $mb_img_filemtile;

$register_action_url = G5_HTTPS_BBS_URL . '/register_form_update.php';
$req_nick = !isset($member['mb_nick_date']) || (isset($member['mb_nick_date']) && $member['mb_nick_date'] <= date("Y-m-d",
            G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400)));
$required = ($w == '') ? 'required' : '';
$readonly = ($w == 'u') ? 'readonly' : '';
$name_readonly = ($w == 'u' || ($config['cf_cert_use'] && $config['cf_cert_req'])) ? 'readonly' : '';
$hp_required = ($config['cf_req_hp'] || (($config['cf_cert_use'] && $config['cf_cert_req']) && ($config['cf_cert_hp'] || $config['cf_cert_simple']) && $member['mb_certify'] != "ipin")) ? 'required' : '';
$hp_readonly = (($config['cf_cert_use'] && $config['cf_cert_req']) && ($config['cf_cert_hp'] || $config['cf_cert_simple']) && $member['mb_certify'] != "ipin") ? 'readonly' : '';

$agree = isset($_REQUEST['agree']) ? preg_replace('#[^0-9]#', '', $_REQUEST['agree']) : '';
$agree2 = isset($_REQUEST['agree2']) ? preg_replace('#[^0-9]#', '', $_REQUEST['agree2']) : '';

// add_javascript('js statement', display_order); The smaller the number, the earlier it is displayed
if ($config['cf_use_addr']) {
    add_javascript(G5_POSTCODE_JS, 0);
}    //Daum address js

include_once($member_skin_path . '/register_form.skin.php');

run_event('register_form_after', $w, $agree, $agree2);

include_once(__DIR__ . '/footer.php');