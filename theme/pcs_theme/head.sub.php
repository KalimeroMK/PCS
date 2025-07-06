<?php
// This file must be included when creating a new file
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
} else {
    $g5_head_title = $g5['title']; // Title to be displayed in the status bar
    $g5_head_title .= " | " . $config['cf_title'];
}

$g5['title'] = strip_tags($g5['title']);
$g5_head_title = strip_tags($g5_head_title);

// Current visitor
// Error occurs if the board title contains a single quote
$g5['lo_location'] = addslashes($g5['title']);
if ($g5['lo_location'] === '' || $g5['lo_location'] === '0')
    $g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/' . G5_ADMIN_DIR . '/') || $is_admin == 'super') $g5['lo_url'] = '';

/*
// If you want to use as an expired page
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
    <!doctype html>
    <html lang="ko">
    <head>
        <meta charset="utf-8">
        <?php
        if (G5_IS_MOBILE) {
            echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">' . PHP_EOL;
            echo '<meta name="HandheldFriendly" content="true">' . PHP_EOL;
            echo '<meta name="format-detection" content="telephone=no">' . PHP_EOL;
        } else {
            echo '<meta http-equiv="imagetoolbar" content="no">' . PHP_EOL;
            echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . PHP_EOL;
        }

        if ($config['cf_add_meta'])
            echo $config['cf_add_meta'] . PHP_EOL;
        ?>
        <title><?php echo $g5_head_title; ?></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <link rel="stylesheet"
              href="<?php echo G5_THEME_CSS_URL; ?>/<?php echo G5_IS_MOBILE ? 'mobile' : 'default'; ?>.css?ver=<?php echo G5_CSS_VER; ?>">
        <link rel="stylesheet"
              href="<?php echo G5_THEME_CSS_URL; ?>/<?php echo G5_IS_MOBILE ? 'pcs_mobile' : 'pcs_default'; ?>.css">

        <!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->
        <script>
            const g5_url = "<?php echo G5_URL ?>";
            const g5_is_member = "<?php echo $is_member ?? ''; ?>";
            const g5_is_admin = "<?php echo $is_admin ?? ''; ?>";
            const g5_bo_table = "<?php echo $bo_table ?? ''; ?>";
            const g5_sca = "<?php echo $sca ?? ''; ?>";
            const g5_editor = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor']) ? $config['cf_editor'] : ''; ?>";
            const g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
            <?php if(defined('G5_IS_ADMIN')) { ?>
            const g5_admin_url = "<?php echo G5_ADMIN_URL; ?>";
            <?php } ?>
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <?php
        add_javascript('<script src="' . G5_JS_URL . '/jquery-1.12.4.min.js"></script>', 0);
        add_javascript('<script src="' . G5_JS_URL . '/jquery-migrate-1.4.1.min.js"></script>', 0);
        add_javascript('<script src="' . G5_JS_URL . '/jquery.menu.js?ver=' . G5_JS_VER . '"></script>', 0);
        add_javascript('<script src="' . G5_JS_URL . '/common.js?ver=' . G5_JS_VER . '"></script>', 0);
        add_javascript('<script src="' . G5_JS_URL . '/wrest.js?ver=' . G5_JS_VER . '"></script>', 0);
        add_javascript('<script src="' . G5_JS_URL . '/placeholders.min.js"></script>', 0);
        add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/font-awesome/css/font-awesome.min.css">', 0);
        if (!defined('G5_IS_ADMIN'))
            echo $config['cf_add_script'];
        ?>
    </head>
<body<?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
<?php
if ($is_member) { // If the user is a member, display a login message.
    $sr_admin_msg = '';
    if ($is_admin == 'super') {
        $sr_admin_msg = "Super Administrator ";
    } elseif ($is_admin == 'group') {
        $sr_admin_msg = "Group Administrator ";
    } elseif ($is_admin == 'board') {
        $sr_admin_msg = "Board Administrator ";
    }

    echo '<div id="hd_login_msg">' . $sr_admin_msg . get_text($member['mb_nick']) . ' is logged in ';
    echo '<a href="' . G5_BBS_URL . '/logout.php">Logout</a></div>';
}
?>