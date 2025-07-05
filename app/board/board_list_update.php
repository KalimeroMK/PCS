<?php

include_once(__DIR__ . '/../common.php');


$count = (isset($_POST['chk_wr_id']) && is_array($_POST['chk_wr_id'])) ? count($_POST['chk_wr_id']) : 0;
$post_btn_submit = isset($_POST['btn_submit']) ? clean_xss_tags($_POST['btn_submit'], 1, 1) : '';

if ($count === 0) {
    alert(addcslashes($post_btn_submit, '"\\/') . ' 하실 항목을 하나 이상 선택하세요.');
}

if ($post_btn_submit === '선택삭제') {
    include __DIR__ . '/delete_all.php';
} elseif ($post_btn_submit === '선택복사') {
    $sw = 'copy';
    include __DIR__ . '/move.php';
} elseif ($post_btn_submit === '선택이동') {
    $sw = 'move';
    include __DIR__ . '/move.php';
} else {
    alert('올바른 방법으로 이용해 주세요.');
}