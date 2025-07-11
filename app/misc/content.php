<?php

include_once(__DIR__ . '/../common.php');


$co_id = isset($_GET['co_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_GET['co_id']) : 0;
$co_seo_title = isset($_GET['co_seo_title']) ? clean_xss_tags($_GET['co_seo_title'], 1, 1) : '';

// Check if $g5['content_table'] array variable exists in dbconfig file
if (!isset($g5['content_table'])) {
    die('<meta charset="utf-8">Please check Board Management->Content Management in admin mode first.');
}

// Content
if ($co_seo_title) {
    $co = get_content_by_field($g5['content_table'], 'content', 'co_seo_title', generate_seo_title($co_seo_title));
    $co_id = isset($co['co_id']) ? $co['co_id'] : 0;
} else {
    $co = get_content_db($co_id);
}

if (!(isset($co['co_seo_title']) && $co['co_seo_title']) && isset($co['co_id']) && $co['co_id']) {
    seo_title_update($g5['content_table'], $co['co_id'], 'content');
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH . '/content.php');
    return;
}

if (!(isset($co['co_id']) && $co['co_id'])) {
    alert('The registered content does not exist.');
}

$g5['title'] = $co['co_subject'];

if ($co['co_include_head'] && is_include_path_check($co['co_include_head'])) {
    @include_once($co['co_include_head']);
} else {
    include_once(__DIR__ . '/../header.php');
}

// KVE-2019-0828 Deprecated content
$co['co_tag_filter_use'] = 1;
$str = conv_content($co['co_content'], $co['co_html'], $co['co_tag_filter_use']);

// Replace $src with $dst
$src = $dst = [];
$src[] = "/{{쇼핑몰명}}|{{홈페이지제목}}/";
$dst[] = $config['cf_title'];
if (isset($default) && isset($default['de_admin_company_name'])) {
    $src[] = "/{{회사명}}|{{상호}}/";
    $dst[] = isset($default['de_admin_company_name']) ? $default['de_admin_company_name'] : '';
    $src[] = "/{{대표자명}}/";
    $dst[] = isset($default['de_admin_company_owner']) ? $default['de_admin_company_owner'] : '';
    $src[] = "/{{사업자등록번호}}/";
    $dst[] = isset($default['de_admin_company_saupja_no']) ? $default['de_admin_company_saupja_no'] : '';
    $src[] = "/{{대표전화번호}}/";
    $dst[] = isset($default['de_admin_company_tel']) ? $default['de_admin_company_tel'] : '';
    $src[] = "/{{팩스번호}}/";
    $dst[] = isset($default['de_admin_company_fax']) ? $default['de_admin_company_fax'] : '';
    $src[] = "/{{통신판매업신고번호}}/";
    $dst[] = isset($default['de_admin_company_tongsin_no']) ? $default['de_admin_company_tongsin_no'] : '';
    $src[] = "/{{사업장우편번호}}/";
    $dst[] = isset($default['de_admin_company_zip']) ? $default['de_admin_company_zip'] : '';
    $src[] = "/{{사업장주소}}/";
    $dst[] = isset($default['de_admin_company_addr']) ? $default['de_admin_company_addr'] : '';
    $src[] = "/{{운영자명}}|{{관리자명}}/";
    $dst[] = isset($default['de_admin_name']) ? $default['de_admin_name'] : '';
    $src[] = "/{{운영자e-mail}}|{{관리자e-mail}}/i";
    $dst[] = isset($default['de_admin_email']) ? $default['de_admin_email'] : '';
    $src[] = "/{{정보관리책임자명}}/";
    $dst[] = isset($default['de_admin_info_name']) ? $default['de_admin_info_name'] : '';
    $src[] = "/{{정보관리책임자e-mail}}|{{정보책임자e-mail}}/i";
    $dst[] = isset($default['de_admin_info_email']) ? $default['de_admin_info_email'] : '';
}
$str = preg_replace($src, $dst, $str);

// Skin path
if (trim($co['co_skin']) === '') {
    $co['co_skin'] = 'basic';
}

$content_skin_path = get_skin_path('content', $co['co_skin']);
$content_skin_url = get_skin_url('content', $co['co_skin']);
$skin_file = $content_skin_path . '/content.skin.php';

if ($is_admin) {
    echo run_replace('content_admin_button_html',
        '<div class="ctt_admin"><a href="' . G5_ADMIN_URL . '/contentform.php?w=u&amp;co_id=' . $co_id . '" class="btn_admin btn"><span class="sound_only">Edit Content</span><i class="fa fa-cog fa-spin fa-fw"></i></a></div>',
        $co);
}
?>

<?php
if (is_file($skin_file)) {
    $himg = G5_DATA_PATH . '/content/' . $co_id . '_h';
    if (file_exists($himg)) // Top image
    {
        echo run_replace('content_head_image_html',
            '<div id="ctt_himg" class="ctt_img"><img src="' . G5_DATA_URL . '/content/' . $co_id . '_h" alt=""></div>', $co);
    }

    include($skin_file);

    $timg = G5_DATA_PATH . '/content/' . $co_id . '_t';
    if (file_exists($timg)) // Bottom image
    {
        echo run_replace('content_tail_image_html',
            '<div id="ctt_timg" class="ctt_img"><img src="' . G5_DATA_URL . '/content/' . $co_id . '_t" alt=""></div>', $co);
    }
} else {
    echo '<p>' . str_replace(G5_PATH . '/', '', $skin_file) . ' does not exist.</p>';
}

if ($co['co_include_tail'] && is_include_path_check($co['co_include_tail'])) {
    @include_once($co['co_include_tail']);
} else {
    include_once(__DIR__ . '/footer.php');
}