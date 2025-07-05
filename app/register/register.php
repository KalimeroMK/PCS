<?php

include_once(__DIR__ . '/../common.php');


// If already logged in, registration is not possible.
if ($is_member) {
    goto_url(G5_URL);
}

// Clear session.
set_session("ss_mb_reg", "");

$g5['title'] = 'Membership Terms';
include_once(__DIR__ . '/../head.php');
$register_action_url = G5_BBS_URL . '/register_form.php';
include_once($member_skin_path . '/register.skin.php');

include_once(__DIR__ . '/tail.php');