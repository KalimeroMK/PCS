<?php

if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가

$qa_skin_path = get_skin_path('qa', (G5_IS_MOBILE ? $qaconfig['qa_mobile_skin'] : $qaconfig['qa_skin']));
$qa_skin_url = get_skin_url('qa', (G5_IS_MOBILE ? $qaconfig['qa_mobile_skin'] : $qaconfig['qa_skin']));

if (G5_IS_MOBILE) {
    // 모바일의 경우 설정을 따르지 않는다.
    include_once(__DIR__ . '/../head.php');    echo run_replace('qa_mobile_content_head', conv_content($qaconfig['qa_mobile_content_head'], 1), $qaconfig);
} else {
    if ($qaconfig['qa_include_head'] && is_include_path_check($qaconfig['qa_include_head'])) {
        @include($qaconfig['qa_include_head']);
    } else {
        include_once(__DIR__ . '/../head.php');
    }
    echo run_replace('qa_content_head', conv_content($qaconfig['qa_content_head'], 1), $qaconfig);
}