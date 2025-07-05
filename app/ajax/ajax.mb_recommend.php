<?php

include_once(__DIR__ . '/../common.php');
include_once(G5_LIB_PATH . "/register.lib.php");

$mb_recommend = isset($_POST["reg_mb_recommend"]) ? trim($_POST["reg_mb_recommend"]) : '';

if ($msg = valid_mb_id($mb_recommend)) {
    die("The recommender's ID may only contain letters, numbers, and underscores.");
}
if (!($msg = exist_mb_id($mb_recommend))) {
    die("The recommender you entered does not exist.");
}