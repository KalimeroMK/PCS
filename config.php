<?php

/********************
 * Constant Declaration
 ********************/

// If this constant is not defined, each individual page cannot be executed separately
define('_GNUBOARD_', true);

include_once($g5_path['path'] . '/version.php');   // Settings file

// Set default timezone
date_default_timezone_set("UTC");

/********************
 * Path Constants
 ********************/

/*
Secure server domain
This is the address starting with https used for registration and posting.
If there is a port, enter it after the domain like :443.
If there is no secure server address, leave it blank. Do not add / at the end of the secure server address.
Example: https://www.domain.com:443/gnuboard5
*/
define('G5_DOMAIN', '');
define('G5_HTTPS_DOMAIN', '');

// GNUBOARD debug bar settings, set to false for production servers.
define('G5_DEBUG', false);
define('G5_COLLECT_QUERY', false);

// Set Database table default engine is Database default_storage_engine, If you want to use MyISAM or InnoDB, change to MyISAM or InnoDB.
// When creating tables in the DB, you can set the default storage engine for the table.
// Can be set to InnoDB or MyISAM.
// If left blank, it will be set according to the DB version or hosting provider's default.
define('G5_DB_ENGINE', '');

// Set Database table default Charset
// You can specify utf8, utf8mb4, etc. Default is utf8. If you change to utf8mb4 before installation, all tables can accept emoji input.
// utf8mb4 encoding requires MySQL or MariaDB version 5.5 or higher.
define('G5_DB_CHARSET', 'utf8');

/*
www.sir.kr and sir.kr are recognized as different domains. To share cookies, enter .sir.kr.
If nothing is entered here, domains with and without www will not share cookies and login may be lost.
*/
define('G5_COOKIE_DOMAIN', '');

define('G5_DBCONFIG_FILE', 'dbconfig.php');

define('G5_ADMIN_DIR', 'adm');
define('G5_BBS_DIR', 'bbs');
define('G5_CSS_DIR', 'css');
define('G5_DATA_DIR', 'data');
define('G5_EXTEND_DIR', 'extend');
define('G5_IMG_DIR', 'img');
define('G5_JS_DIR', 'js');
define('G5_LIB_DIR', 'lib');
define('G5_PLUGIN_DIR', 'plugin');
define('G5_SKIN_DIR', 'skin');
define('G5_EDITOR_DIR', 'editor');
define('G5_MOBILE_DIR', 'mobile');
define('G5_OKNAME_DIR', 'okname');

define('G5_KCPCERT_DIR', 'kcpcert');
define('G5_INICERT_DIR', 'inicert');
define('G5_LGXPAY_DIR', 'lgxpay');

define('G5_SNS_DIR', 'sns');
define('G5_SYNDI_DIR', 'syndi');
define('G5_PHPMAILER_DIR', 'PHPMailer');
define('G5_SESSION_DIR', 'session');
define('G5_THEME_DIR', 'theme');

define('G5_GROUP_DIR', 'group');
define('G5_CONTENT_DIR', 'content');

// URL is the path in the browser (from the domain)
if (G5_DOMAIN) {
    define('G5_URL', G5_DOMAIN);
} else {
    if (isset($g5_path['url']))
        define('G5_URL', $g5_path['url']);
    else
        define('G5_URL', '');
}

if (isset($g5_path['path'])) {
    define('G5_PATH', $g5_path['path']);
} else {
    define('G5_PATH', '');
}

define('G5_ADMIN_URL', G5_URL . '/' . G5_ADMIN_DIR);
define('G5_BBS_URL', G5_URL . '/' . G5_BBS_DIR);
define('G5_CSS_URL', G5_URL . '/' . G5_CSS_DIR);
define('G5_DATA_URL', G5_URL . '/' . G5_DATA_DIR);
define('G5_IMG_URL', G5_URL . '/' . G5_IMG_DIR);
define('G5_JS_URL', G5_URL . '/' . G5_JS_DIR);
define('G5_SKIN_URL', G5_URL . '/' . G5_SKIN_DIR);
define('G5_PLUGIN_URL', G5_URL . '/' . G5_PLUGIN_DIR);
define('G5_EDITOR_URL', G5_PLUGIN_URL . '/' . G5_EDITOR_DIR);
define('G5_OKNAME_URL', G5_PLUGIN_URL . '/' . G5_OKNAME_DIR);
define('G5_KCPCERT_URL', G5_PLUGIN_URL . '/' . G5_KCPCERT_DIR);
define('G5_INICERT_URL', G5_PLUGIN_URL . '/' . G5_INICERT_DIR);
define('G5_LGXPAY_URL', G5_PLUGIN_URL . '/' . G5_LGXPAY_DIR);
define('G5_SNS_URL', G5_PLUGIN_URL . '/' . G5_SNS_DIR);
define('G5_SYNDI_URL', G5_PLUGIN_URL . '/' . G5_SYNDI_DIR);
define('G5_MOBILE_URL', G5_URL . '/' . G5_MOBILE_DIR);

// PATH is the absolute path on the server
define('G5_ADMIN_PATH', G5_PATH . '/' . G5_ADMIN_DIR);
define('G5_BBS_PATH', G5_PATH . '/' . G5_BBS_DIR);
define('G5_DATA_PATH', G5_PATH . '/' . G5_DATA_DIR);
define('G5_EXTEND_PATH', G5_PATH . '/' . G5_EXTEND_DIR);
define('G5_LIB_PATH', G5_PATH . '/' . G5_LIB_DIR);
define('G5_PLUGIN_PATH', G5_PATH . '/' . G5_PLUGIN_DIR);
define('G5_SKIN_PATH', G5_PATH . '/' . G5_SKIN_DIR);
define('G5_MOBILE_PATH', G5_PATH . '/' . G5_MOBILE_DIR);
define('G5_SESSION_PATH', G5_DATA_PATH . '/' . G5_SESSION_DIR);
define('G5_EDITOR_PATH', G5_PLUGIN_PATH . '/' . G5_EDITOR_DIR);
define('G5_OKNAME_PATH', G5_PLUGIN_PATH . '/' . G5_OKNAME_DIR);

define('G5_KCPCERT_PATH', G5_PLUGIN_PATH . '/' . G5_KCPCERT_DIR);
define('G5_INICERT_PATH', G5_PLUGIN_PATH . '/' . G5_INICERT_DIR);
define('G5_LGXPAY_PATH', G5_PLUGIN_PATH . '/' . G5_LGXPAY_DIR);

define('G5_SNS_PATH', G5_PLUGIN_PATH . '/' . G5_SNS_DIR);
define('G5_SYNDI_PATH', G5_PLUGIN_PATH . '/' . G5_SYNDI_DIR);
define('G5_PHPMAILER_PATH', G5_PLUGIN_PATH . '/' . G5_PHPMAILER_DIR);
//==============================================================================


//==============================================================================
// Device Settings
// pc setting shows PC screen on mobile devices
// mobile setting shows mobile screen on PC
// both setting shows screen according to device
//------------------------------------------------------------------------------
define('G5_SET_DEVICE', 'both');

define('G5_USE_MOBILE', true); // Set to false if you don't want to use mobile homepage
define('G5_USE_CACHE', true); // Set to false if you don't want to use cache for latest articles


/********************
 * Time Constants
 ********************/
// If the server time and actual time are different, adjust here.
// One day is 86400 seconds. One hour is 3600 seconds
// If 6 hours ahead, use time() + (3600 * 6);
// If 6 hours behind, use time() - (3600 * 6);
define('G5_SERVER_TIME', time());
define('G5_TIME_YMDHIS', date('Y-m-d H:i:s', G5_SERVER_TIME));
define('G5_TIME_YMD', substr(G5_TIME_YMDHIS, 0, 10));
define('G5_TIME_HIS', substr(G5_TIME_YMDHIS, 11, 8));

// Input validation constants (do not change numbers)
define('G5_ALPHAUPPER', 1); // Uppercase letters
define('G5_ALPHALOWER', 2); // Lowercase letters
define('G5_ALPHABETIC', 4); // Uppercase and lowercase letters
define('G5_NUMERIC', 8); // Numbers
define('G5_HANGUL', 16); // Korean characters
define('G5_SPACE', 32); // Spaces
define('G5_SPECIAL', 64); // Special characters

// SEO title length
define('G5_SEO_TITLE_WORD_CUT', 8);        // SEO title length

// Permissions
define('G5_DIR_PERMISSION', 0755); // Directory creation permission
define('G5_FILE_PERMISSION', 0644); // File creation permission

// Mobile device detection
// $_SERVER['HTTP_USER_AGENT']
define('G5_MOBILE_AGENT', 'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|BB10|android|sony');

// SMTP
// Used in lib/mailer.lib.php
define('G5_SMTP', '127.0.0.1');
define('G5_SMTP_PORT', '25');


/********************
 * Miscellaneous Constants
 ********************/

// Encryption function
// Do not change this setting during site operation, as it may cause login issues.
// 5.4 version and earlier used sql_password, 5.4 version and later use create_hash by default
//define('G5_STRING_ENCRYPT_FUNCTION', 'sql_password');
define('G5_STRING_ENCRYPT_FUNCTION', 'create_hash');
define('G5_MYSQL_PASSWORD_LENGTH', 41);         // mysql password length 41, old_password is 16

// Display SQL errors
// Set to true to display errors
define('G5_DISPLAY_SQL_ERROR', false);

// Escape string function
// Can be changed to addslashes
define('G5_ESCAPE_FUNCTION', 'sql_escape_string');

// sql_escape_string function pattern
//define('G5_ESCAPE_PATTERN',  '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
//define('G5_ESCAPE_REPLACE',  '');

// Default link count in boards
// Increase this number if you add fields
define('G5_LINK_COUNT', 2);

// Thumbnail JPG quality
define('G5_THUMB_JPG_QUALITY', 90);

// Thumbnail PNG compression
define('G5_THUMB_PNG_COMPRESS', 5);

// Use DHTML editor on mobile devices
define('G5_IS_MOBILE_DHTML_USE', false);

// Use MySQLi
define('G5_MYSQLI_USE', true);

// Use Browscap
define('G5_BROWSCAP_USE', true);

// Use Browscap for visitor records
define('G5_VISIT_BROWSCAP_USE', false);

// IP hiding method
/* 123.456.789.012 IP hiding method
\\1 is 123, \\2 is 456, \\3 is 789, \\4 is 012
Use \\1 for visible parts and other characters for hidden parts
*/
define('G5_IP_DISPLAY', '\\1.♡.\\3.\\4');

// KAKAO postcode service CDN
define('G5_POSTCODE_JS', '<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js" async></script>');
