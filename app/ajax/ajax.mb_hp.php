<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_LIB_PATH . '/register.lib.php');

$mb_hp = isset($_POST['reg_mb_hp']) ? trim($_POST['reg_mb_hp']) : '';
$mb_id = isset($_POST['reg_mb_id']) ? trim($_POST['reg_mb_id']) : '';

if ($msg = valid_mb_hp($mb_hp)) {
    die($msg);
}
//if ($msg = exist_mb_hp($mb_hp, $mb_id)) die($msg);