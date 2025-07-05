<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // Individual page access disabled

if (G5_IS_MOBILE) {
    echo run_replace('qa_mobile_content_tail', conv_content($qaconfig['qa_mobile_content_tail'], 1), $qaconfig);
    // For mobile, do not follow the settings.
    include_once(__DIR__ . '/tail.php');
} else {
    echo run_replace('qa_content_tail', conv_content($qaconfig['qa_content_tail'], 1), $qaconfig);
    if ($qaconfig['qa_include_tail'] && is_include_path_check($qaconfig['qa_include_tail'])) {
        @include($qaconfig['qa_include_tail']);
    } else {
        include(__DIR__ . '/tail.php');
    }
}