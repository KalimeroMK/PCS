<?php
if (!defined('_GNUBOARD_')) exit;

function empty_mb_id($reg_mb_id): string
{
    if (trim($reg_mb_id) === '')
        return "회원아이디를 입력해 주십시오.";
    else
        return "";
}

function valid_mb_id($reg_mb_id): string
{
    if (preg_match("/[^0-9a-z_]+/i", $reg_mb_id))
        return "회원아이디는 영문자, 숫자, _ 만 입력하세요.";
    else
        return "";
}

function count_mb_id($reg_mb_id): string
{
    if (strlen($reg_mb_id) < 3)
        return "회원아이디는 최소 3글자 이상 입력하세요.";
    else
        return "";
}

function exist_mb_id($reg_mb_id): string
{
    global $g5;

    $reg_mb_id = trim($reg_mb_id);
    if ($reg_mb_id === "") return "";

    $sql = " select count(*) as cnt from `{$g5['member_table']}` where mb_id = '$reg_mb_id' ";
    $row = sql_fetch($sql);
    if ($row['cnt'])
        return "이미 사용중인 회원아이디 입니다.";
    else
        return "";
}

function reserve_mb_id($reg_mb_id): string
{
    global $config;
    if (preg_match("/[\,]?{$reg_mb_id}/i", $config['cf_prohibit_id']))
        return "이미 예약된 단어로 사용할 수 없는 회원아이디 입니다.";
    else
        return "";
}

function empty_mb_nick($reg_mb_nick): string
{
    if (trim($reg_mb_nick) === '' || trim($reg_mb_nick) === '0')
        return "닉네임을 입력해 주십시오.";
    else
        return "";
}

function valid_mb_nick($reg_mb_nick): string
{
    if (!check_string($reg_mb_nick, G5_HANGUL + G5_ALPHABETIC + G5_NUMERIC))
        return "닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다.";
    else
        return "";
}

function count_mb_nick($reg_mb_nick): string
{
    if (strlen($reg_mb_nick) < 4)
        return "닉네임은 한글 2글자, 영문 4글자 이상 입력 가능합니다.";
    else
        return "";
}

function exist_mb_nick($reg_mb_nick, $reg_mb_id): string
{
    global $g5;
    $row = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_nick = '$reg_mb_nick' and mb_id <> '$reg_mb_id' ");
    if ($row['cnt'])
        return "이미 존재하는 닉네임입니다.";
    else
        return "";
}

function reserve_mb_nick($reg_mb_nick): string
{
    global $config;
    if (preg_match("/[\,]?" . preg_quote($reg_mb_nick) . "/i", $config['cf_prohibit_id']))
        return "이미 예약된 단어로 사용할 수 없는 닉네임 입니다.";
    else
        return "";
}

function empty_mb_email($reg_mb_email): string
{
    if (trim($reg_mb_email) === '' || trim($reg_mb_email) === '0')
        return "E-mail 주소를 입력해 주십시오.";
    else
        return "";
}

function valid_mb_email($reg_mb_email): string
{
    if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $reg_mb_email))
        return "E-mail 주소가 형식에 맞지 않습니다.";
    else
        return "";
}

// 금지 메일 도메인 검사
function prohibit_mb_email($reg_mb_email): string
{
    global $config;

    list($id, $domain) = explode("@", $reg_mb_email);
    $email_domains = explode("\n", trim($config['cf_prohibit_email']));
    $email_domains = array_map('trim', $email_domains);
    $email_domains = array_map('strtolower', $email_domains);
    $email_domain = strtolower($domain);

    if (in_array($email_domain, $email_domains))
        return "$domain 메일은 사용할 수 없습니다.";

    return "";
}

function exist_mb_email($reg_mb_email, $reg_mb_id): string
{
    global $g5;
    $row = sql_fetch(" select count(*) as cnt from `{$g5['member_table']}` where mb_email = '$reg_mb_email' and mb_id <> '$reg_mb_id' ");
    if ($row['cnt'])
        return "이미 사용중인 E-mail 주소입니다.";
    else
        return "";
}

function empty_mb_name($reg_mb_name): string
{
    if (trim($reg_mb_name) === '' || trim($reg_mb_name) === '0')
        return "이름을 입력해 주십시오.";
    else
        return "";
}

function valid_mb_name($mb_name): string
{
    if (!check_string($mb_name, G5_HANGUL))
        return "이름은 공백없이 한글만 입력 가능합니다.";
    else
        return "";
}

function valid_mb_hp($reg_mb_hp): string
{
    $reg_mb_hp = preg_replace("/[^0-9]/", "", $reg_mb_hp);
    if (!$reg_mb_hp) {
        return "휴대폰번호를 입력해 주십시오.";
    } elseif (preg_match("/^01\\d{8,9}\$/", $reg_mb_hp)) {
        return "";
    } else
        return "휴대폰번호를 올바르게 입력해 주십시오.";
}

function exist_mb_hp($reg_mb_hp, $reg_mb_id): string
{
    global $g5;

    if (trim($reg_mb_hp) === '' || trim($reg_mb_hp) === '0') return "";

    $reg_mb_hp = hyphen_hp_number($reg_mb_hp);

    $sql = "select count(*) as cnt from {$g5['member_table']} where mb_hp = '$reg_mb_hp' and mb_id <> '$reg_mb_id' ";
    $row = sql_fetch($sql);

    if ($row['cnt'])
        return " 이미 사용 중인 휴대폰번호입니다. " . $reg_mb_hp;
    else
        return "";
}