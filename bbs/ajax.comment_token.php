<?php

include_once(__DIR__ . '/_common.php');

$ss_name = 'ss_comment_token';

set_session($ss_name, '');

$token = _token();

set_session($ss_name, $token);

die(json_encode(['token' => $token]));
