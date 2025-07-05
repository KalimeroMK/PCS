<?php

define('G5_CERT_IN_PROG', true);
include_once(__DIR__ . '/../common.php');

global $g5;

if ($w != '' && $w != 'u') {
    alert('The value of w was not passed correctly.');
}
$url = urldecode($url);

if ($w == '') {
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
    $mb_name = isset($_POST['mb_name']) ? trim($_POST['mb_name']) : '';
    $mb_hp = isset($_POST['mb_hp']) ? trim($_POST['mb_hp']) : '';
} else {
    alert('Invalid access', G5_URL);
}

if (!$mb_id) {
    alert('Member ID value is missing. Please use the correct method.');
}

//===============================================================
//  Identity verification
//---------------------------------------------------------------
$mb_hp = hyphen_hp_number($mb_hp);
if ($config['cf_cert_use'] && get_session('ss_cert_type') && get_session('ss_cert_dupinfo')) {
    // Duplicate check
    $sql = " select mb_id from {$g5['member_table']} where mb_id <> '{$member['mb_id']}' and mb_dupinfo = '" . get_session('ss_cert_dupinfo') . "' ";
    $row = sql_fetch($sql);
    if (!empty($row['mb_id'])) {
        alert("There is already an entry with the entered identity verification information.");
    }
}

$sql = '';
$sql_certify = '';
$md5_cert_no = get_session('ss_cert_no');
$cert_type = get_session('ss_cert_type');
if ($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
    // Only save identity verification value if the values match
    if ($cert_type == 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $md5_cert_no)) {
        // When ipin, check hash value (hp not included)
        $sql_certify .= " mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify  = '{$cert_type}' ";
        $sql_certify .= " , mb_adult = '" . get_session('ss_cert_adult') . "' ";
        $sql_certify .= " , mb_birth = '" . get_session('ss_cert_birth') . "' ";
        $sql_certify .= " , mb_sex = '" . get_session('ss_cert_sex') . "' ";
        $sql_certify .= " , mb_dupinfo = '" . get_session('ss_cert_dupinfo') . "' ";
        $sql_certify .= " , mb_name = '{$mb_name}' ";
    } elseif ($cert_type != 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $mb_hp . $md5_cert_no)) {
        // Simple verification, when mobile phone, check hash value (hp included)
        $sql_certify .= " mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify  = '{$cert_type}' ";
        $sql_certify .= " , mb_adult = '" . get_session('ss_cert_adult') . "' ";
        $sql_certify .= " , mb_birth = '" . get_session('ss_cert_birth') . "' ";
        $sql_certify .= " , mb_sex = '" . get_session('ss_cert_sex') . "' ";
        $sql_certify .= " , mb_dupinfo = '" . get_session('ss_cert_dupinfo') . "' ";
        $sql_certify .= " , mb_name = '{$mb_name}' ";
    } else {
        alert('The entered member information does not match the verified information. Please try again.');
    }
} elseif (get_session("ss_reg_mb_name") != $mb_name || get_session("ss_reg_mb_hp") != $mb_hp) {
    $sql_certify .= " mb_hp = '{$mb_hp}' ";
    $sql_certify .= " , mb_certify = '' ";
    $sql_certify .= " , mb_adult = 0 ";
    $sql_certify .= " , mb_birth = '' ";
    $sql_certify .= " , mb_sex = '' ";
}

$sql = "update {$g5['member_table']} set {$sql_certify} where mb_id = '{$mb_id}'";
$result = sql_query($sql, false);

if ($result) {
    if ($cert_type == 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $md5_cert_no)) {
        // When ipin, check hash value (hp not included)
        insert_member_cert_history($mb_id, $mb_name, $mb_hp, get_session('ss_cert_birth'),
            get_session('ss_cert_type'));
        // Identity verification after information modification record
    } elseif ($cert_type != 'ipin' && get_session('ss_cert_hash') == md5($mb_name . $cert_type . get_session('ss_cert_birth') . $mb_hp . $md5_cert_no)) {
        // Simple verification, when mobile phone, check hash value (hp included)
        insert_member_cert_history($mb_id, $mb_name, $mb_hp, get_session('ss_cert_birth'),
            get_session('ss_cert_type'));
        // Identity verification after information modification record
    }
}

run_event('cert_refresh_update_after', $mb_id);

//===============================================================

(empty($url)) ? goto_url(G5_URL) : goto_url($url);