<?php

include_once(__DIR__ . '/../common.php');


// clean the output buffer
ob_end_clean();

$no = isset($_REQUEST['no']) ? (int)$_REQUEST['no'] : 0;

// Error occurs if the ID value stored in the cookie and the passed ID value do not match
// Code to prevent linking from other places
if (!get_session('ss_qa_view_' . $qa_id)) {
    alert('Invalid approach.');
}

$sql = " select qa_subject, qa_file{$no}, qa_source{$no} from {$g5['qa_content_table']} where qa_id = '$qa_id' ";
$file = sql_fetch($sql);
if (!$file['qa_file' . $no]) {
    alert_close('File information does not exist.');
}

if ($is_guest) {
    alert('You do not have permission to download.\nIf you are a member, please log in and try again.',
        G5_BBS_URL . '/login.php?url=' . urlencode(G5_BBS_URL . '/qaview.php?qa_id=' . $qa_id));
}

$filepath = G5_DATA_PATH . '/qa/' . $file['qa_file' . $no];
$filepath = addslashes($filepath);
$file_exist_check = (!is_file($filepath) || !file_exists($filepath)) ? false : true;

if (false === run_replace('qa_download_file_exist_check', $file_exist_check, $file)) {
    alert('File does not exist.');
}

$g5['title'] = 'Download &gt; ' . conv_subject($file['qa_subject'], 255);

run_event('qa_download_file_header', $file, $file_exist_check);

$original = urlencode($file['qa_source' . $no]);

if (preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
    header("content-type: doesn/matter");
    header("content-length: " . filesize($filepath));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: " . filesize($filepath));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen($filepath, 'rb');

// 4.00 replacement
// To reduce server load, this method is better than using print, echo, or a while loop...
//if (!fpassthru($fp)) {
//    fclose($fp);
//}

$download_rate = 10;

while (!feof($fp)) {
    //echo fread($fp, 100*1024);
    /*
    echo fread($fp, 100*1024);
    flush();
    */

    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose($fp);
flush();