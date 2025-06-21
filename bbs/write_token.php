<?php

include_once(__DIR__ . '/_common.php');

if (!$bo_table) {
    die(json_encode(['error' => '게시판 정보가 올바르지 않습니다.', 'url' => G5_URL]));
}

set_session('ss_write_'.$bo_table.'_token', '');

$token = get_write_token($bo_table);

die(json_encode(['error' => '', 'token' => $token, 'url' => '']));
