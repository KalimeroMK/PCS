<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed
$data_path = '../'.G5_DATA_DIR;

if (! (isset($title) && $title)) $title = G5_VERSION." Installation";
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="install.css">
</head>
<body>

<div id="ins_bar">
    <span id="bar_img">GNUBOARD5</span>
    <span id="bar_txt">INSTALLATION</span>
</div>

<?php
// If the file exists, installation is not possible.
$dbconfig_file = $data_path.'/'.G5_DBCONFIG_FILE;
if (file_exists($dbconfig_file)) {
?>
<h1><?php echo G5_VERSION; ?> The program is already installed.</h1>

<div class="ins_inner">
    <p>The program is already installed.<br />To reinstall, please delete the following file and refresh the page.</p>
    <ul>
        <li><?php echo $dbconfig_file ?></li>
    </ul>
</div>
<?php
    exit;
}
?>

<?php
$exists_data_dir = true;
// Does the data directory exist?
if (!is_dir($data_path))
{
?>
<h1><?php echo G5_VERSION; ?> Please check the following to proceed with installation.</h1>

<div class="ins_inner">
    <p>
        Please create the <?php echo G5_DATA_DIR ?> directory in the root directory.<br />
        (The root directory is where the common.php file is located.)<br /><br />
        $> mkdir <?php echo G5_DATA_DIR ?><br /><br />
        For Windows, please create a data folder manually.<br /><br />
        After executing the above command, refresh your browser.
    </p>
</div>
<?php
    $exists_data_dir = false;
}
?>

<?php
$write_data_dir = true;
// Check if files can be created in the data directory.
if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
    $sapi_type = php_sapi_name();
    if (substr($sapi_type, 0, 3) == 'cgi') {
        if (!(is_readable($data_path) && is_executable($data_path)))
        {
        ?>
        <div class="ins_inner">
            <p>
                Please change the permission of the <?php echo G5_DATA_DIR ?> directory to 705.<br /><br />
                $> chmod 705 <?php echo G5_DATA_DIR ?> or chmod uo+rx <?php echo G5_DATA_DIR ?><br /><br />
                After executing the above command, refresh your browser.
            </p>
        </div>
        <?php
            $write_data_dir = false;
        }
    } else {
        if (!(is_readable($data_path) && is_writeable($data_path) && is_executable($data_path)))
        {
        ?>
        <div class="ins_inner">
            <p>
                <?php echo G5_DATA_DIR ?> 디렉토리의 퍼미션을 707로 변경하여 주십시오.<br /><br />
                $> chmod 707 <?php echo G5_DATA_DIR ?> 또는 chmod uo+rwx <?php echo G5_DATA_DIR ?><br /><br />
                위 명령 실행후 브라우저를 새로고침 하십시오.
            </p>
        </div>
        <?php
            $write_data_dir = false;
        }
    }
}