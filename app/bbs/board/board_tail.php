<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access not allowed

// Path to the board management footer file
if (G5_IS_MOBILE) {
    echo run_replace('board_mobile_content_tail', html_purifier(stripslashes($board['bo_mobile_content_tail'])),
        $board);
    // For mobile, do not follow the settings.
    include_once(__DIR__ . '/tail.php');
} else {
    echo run_replace('board_content_tail', html_purifier(stripslashes($board['bo_content_tail'])), $board);
    // If no footer file path is entered, do not include the default footer file
    if (trim($board['bo_include_tail']) !== '' && trim($board['bo_include_tail']) !== '0') {
        if (is_include_path_check($board['bo_include_tail'])) {  //Check file path
            @include($board['bo_include_tail']);
        } else {    //If the file path is incorrect, load the default file
            include_once(__DIR__ . '/tail.php');
        }
    }
}