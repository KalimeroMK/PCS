<?php

include_once(__DIR__ . '/../common.php');


$token_case = isset($_POST['token_case']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['token_case']) : '';

if ($token_case) {
    $token = _token();
    set_session('ss_' . $token_case . '_token', $token);
    die(json_encode(['error' => '', 'token' => $token, 'url' => '']));
}
