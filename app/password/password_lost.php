<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

if ($is_member) {
    alert("이미 로그인중입니다.", G5_URL);
}

$g5['title'] = '회원정보 찾기';
include_once(G5_PATH . '/header.php');

$action_url = G5_HTTPS_BBS_URL . "/password_lost2.php";
include_once($member_skin_path . '/password_lost.skin.php');

include_once(__DIR__ . '/footer.php');