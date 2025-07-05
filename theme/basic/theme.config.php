<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

// Devices supported by the theme: pc, mobile
// If not declared or specified, follows GNUBOARD5's settings.
// Takes precedence over the G5_SET_DEVICE constant setting.
if (!defined('G5_THEME_DEVICE')) define('G5_THEME_DEVICE', '');

$theme_config = array();

// If you specify settings such as the number of gallery images,
// you can apply the value directly to the corresponding board setting field via the import function.
// Leave the value empty for skins you do not use.

// Set whether the theme supports community features.
// Set to false if the shop is the main page without community use.
// If set to false, the board head and tail will follow the shop's versions.
if (!defined('G5_COMMUNITY_USE')) define('G5_COMMUNITY_USE', true);

// If you specify settings such as the number of gallery images,
// you can apply the value directly to the corresponding board setting field via the import function.
// Leave the value empty for skins you do not use.
$theme_config = array(
    'set_default_skin' => false,   // Whether to change the default skin for recent posts, etc. in the basic environment settings: true, false
    'preview_board_skin' => 'basic', // Default board skin applied during theme preview
    'preview_mobile_board_skin' => 'basic', // Default mobile board skin applied during theme preview
    'cf_member_skin' => 'basic', // Member skin
    'cf_mobile_member_skin' => 'basic', // Mobile member skin
    'cf_new_skin' => 'basic', // Recent posts skin
    'cf_mobile_new_skin' => 'basic', // Mobile recent posts skin
    'cf_search_skin' => 'basic', // Search skin
    'cf_mobile_search_skin' => 'basic', // Mobile search skin
    'cf_connect_skin' => 'basic', // Visitor skin
    'cf_mobile_connect_skin' => 'basic', // Mobile visitor skin
    'cf_faq_skin' => 'basic', // FAQ skin
    'cf_mobile_faq_skin' => 'basic', // Mobile FAQ skin
    'bo_gallery_cols' => 4,       // 갤러리 이미지 수
    'bo_gallery_width' => 215,     // Gallery image width
    'bo_gallery_height' => 215,     // Gallery image height
    'bo_mobile_gallery_width' => 250,     // Mobile gallery image width
    'bo_mobile_gallery_height' => 200,     // Mobile gallery image height
    'bo_image_width' => 900,     // Board view image width
    'qa_skin' => 'basic', // 1:1 inquiry skin
    'qa_mobile_skin' => 'basic', // 1:1 inquiry mobile skin
    'de_shop_skin' => 'basic', // Shop PC default skin
    'de_shop_mobile_skin' => 'basic', // Shop mobile default skin
    'de_type1_list_use' => 1,       // PC hit products output
    'de_type1_list_skin' => 'main.10.skin.php', // PC hit products output스킨
    'de_type1_list_mod' => 5,       // PC hit products output 1줄당 이미지 수
    'de_type1_list_row' => 2,       // PC hit products output 출력 줄 수
    'de_type1_img_width' => 160,     // PC hit product image width
    'de_type1_img_height' => 160,     // PC hit product image height
    'de_type2_list_use' => 1,       // PC recommended products output
    'de_type2_list_skin' => 'main.20.skin.php', // PC recommended products output스킨
    'de_type2_list_mod' => 4,       // PC recommended products output 1줄당 이미지 수
    'de_type2_list_row' => 2,       // PC recommended products output 출력 줄 수
    'de_type2_img_width' => 215,     // PC recommended product image width
    'de_type2_img_height' => 215,     // PC recommended product image height
    'de_type3_list_use' => 1,       // PC latest products output
    'de_type3_list_skin' => 'main.40.skin.php', // PC latest products output스킨
    'de_type3_list_mod' => 4,       // PC latest products output 1줄당 이미지 수
    'de_type3_list_row' => 1,       // PC latest products output 출력 줄 수
    'de_type3_img_width' => 215,     // PC latest product image width
    'de_type3_img_height' => 215,     // PC latest product image height
    'de_type4_list_use' => 1,       // PC 인기상품 출력
    'de_type4_list_skin' => 'main.50.skin.php', // PC 인기상품 출력스킨
    'de_type4_list_mod' => 5,       // PC 인기상품 출력 1줄당 이미지 수
    'de_type4_list_row' => 1,       // PC 인기상품 출력 출력 줄 수
    'de_type4_img_width' => 215,     // PC 인기상품 이미지 폭
    'de_type4_img_height' => 215,     // PC 인기상품 이미지 높이
    'de_type5_list_use' => 1,       // PC discounted products output
    'de_type5_list_skin' => 'main.30.skin.php', // PC discounted products output스킨
    'de_type5_list_mod' => 4,       // PC discounted products output 1줄당 이미지 수
    'de_type5_list_row' => 1,       // PC discounted products output 출력 줄 수
    'de_type5_img_width' => 215,     // PC discounted product image width
    'de_type5_img_height' => 215,     // PC discounted product image height
    'de_mobile_type1_list_use' => 1,       // 모바일 히트상품 출력
    'de_mobile_type1_list_skin' => 'main.30.skin.php', // 모바일 히트상품 출력스킨
    'de_mobile_type1_list_mod' => 2,       // 모바일 히트상품 출력 1줄당 이미지 수
    'de_mobile_type1_list_row' => 4,       // 모바일 히트상품 출력 출력 줄 수
    'de_mobile_type1_img_width' => 230,     // 모바일 히트상품 이미지 폭
    'de_mobile_type1_img_height' => 230,     // 모바일 히트상품 이미지 높이
    'de_mobile_type2_list_use' => 1,       // Mobile recommended products output
    'de_mobile_type2_list_skin' => 'main.10.skin.php', // Mobile recommended products output스킨
    'de_mobile_type2_list_mod' => 2,       // Mobile recommended products output 1줄당 이미지 수
    'de_mobile_type2_list_row' => 2,       // Mobile recommended products output 출력 줄 수
    'de_mobile_type2_img_width' => 300,     // Mobile recommended product image width
    'de_mobile_type2_img_height' => 300,     // Mobile recommended product image height
    'de_mobile_type3_list_use' => 1,       // Mobile latest products output
    'de_mobile_type3_list_skin' => 'main.10.skin.php', // Mobile latest products output스킨
    'de_mobile_type3_list_mod' => 2,       // Mobile latest products output 1줄당 이미지 수
    'de_mobile_type3_list_row' => 4,       // Mobile latest products output 출력 줄 수
    'de_mobile_type3_img_width' => 300,     // Mobile latest product image width
    'de_mobile_type3_img_height' => 300,     // Mobile latest product image height
    'de_mobile_type4_list_use' => 1,       // 모바일 인기상품 출력
    'de_mobile_type4_list_skin' => 'main.20.skin.php', // 모바일 인기상품 출력스킨
    'de_mobile_type4_list_mod' => 2,       // 모바일 인기상품 출력 1줄당 이미지 수
    'de_mobile_type4_list_row' => 2,       // 모바일 인기상품 출력 출력 줄 수
    'de_mobile_type4_img_width' => 80,     // 모바일 인기상품 이미지 폭
    'de_mobile_type4_img_height' => 80,     // 모바일 인기상품 이미지 높이
    'de_mobile_type5_list_use' => 1,       // 모바일 할인상품 출력
    'de_mobile_type5_list_skin' => 'main.10.skin.php', // 모바일 할인상품 출력스킨
    'de_mobile_type5_list_mod' => 2,       // 모바일 할인상품 출력 1줄당 이미지 수
    'de_mobile_type5_list_row' => 2,       // 모바일 할인상품 출력 출력 줄 수
    'de_mobile_type5_img_width' => 230,     // 모바일 할인상품 이미지 폭
    'de_mobile_type5_img_height' => 230,     // 모바일 할인상품 이미지 높이
    'de_rel_list_use' => 1,       // Related products output
    'de_rel_list_skin' => 'relation.10.skin.php',  // Related products output 스킨
    'de_rel_list_mod' => 5,       // Number of images per row for related products
    'de_rel_img_width' => 215,     // Related product image width
    'de_rel_img_height' => 215,     // Related product image height
    'de_mobile_rel_list_use' => 1,       // Mobile related products output
    'de_mobile_rel_list_skin' => 'relation.10.skin.php',  // Mobile related products output 스킨
    'de_mobile_rel_list_mod' => 3,       // Number of images per row for mobile related products
    'de_mobile_rel_img_width' => 230,     // Mobile related product image width
    'de_mobile_rel_img_height' => 230,     // Mobile related product image height
    'de_search_list_skin' => 'list.10.skin.php',  // Search products output skin
    'de_search_list_mod' => 5,       // Number of images per row for search products
    'de_search_list_row' => 5,       // Number of output rows for search products
    'de_search_img_width' => 225,     // Search product image width
    'de_search_img_height' => 225,     // Search product image height
    'de_mobile_search_list_skin' => 'list.10.skin.php',  // Mobile search products output skin
    'de_mobile_search_list_mod' => 2,       // Number of images per row for mobile search products
    'de_mobile_search_list_row' => 5,       // Number of output rows for mobile search products
    'de_mobile_search_img_width' => 230,     // Mobile related product image width
    'de_mobile_search_img_height' => 230,     // Mobile related product image height
    'de_mimg_width' => 400,     // Product detail image width
    'de_mimg_height' => 400,     // Product detail image height
    'ca_skin' => 'list.10.skin.php',  // Category list skin
    'ca_img_width' => 225,     // Category list image width
    'ca_img_height' => 225,     // Category list image height
    'ca_list_mod' => 5,       // Number of images per row for category list
    'ca_list_row' => 5,       // Number of output rows for category list images
    'ca_mobile_skin' => 'list.10.skin.php',  // Mobile category list skin
    'ca_mobile_img_width' => 230,     // Mobile category list image width
    'ca_mobile_img_height' => 230,     // Mobile category list image height
    'ca_mobile_list_mod' => 2,       // Number of images per row for mobile category list
    'ca_mobile_list_row' => 5,       // Number of output rows for mobile category list images
    'ev_skin' => 'list.10.skin.php',  // Event output skin
    'ev_img_width' => 225,     // Event list image width
    'ev_img_height' => 225,     // Event list image height
    'ev_list_mod' => 5,       // Number of images per row for event list
    'ev_list_row' => 5,       // Number of output rows for event list images
    'ev_mobile_skin' => 'list.10.skin.php',  // Mobile event output skin
    'ev_mobile_img_width' => 230,     // Mobile event list image width
    'ev_mobile_img_height' => 230,     // Mobile event list image height
    'ev_mobile_list_mod' => 2,       // Number of images per row for mobile event
    'ev_mobile_list_row' => 5,       // Number of output rows for mobile event images
    'ca_mobile_list_best_mod' => 2,       // Number of images per row for mobile product list best products
    'ca_mobile_list_best_row' => 3,       // Number of output rows for mobile product list best products images
);