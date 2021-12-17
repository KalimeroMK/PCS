<?php
include_once('./_common.php');
include_once('./pcs_config.php');

switch ($_GET['flde']) {

	case 'vi'	: $filepath = PCS_REP_PDF.'/vi/'.$_GET['flna'].'.pdf';	break;

	case 'pmi'	: $filepath = PCS_REP_PDF.'/pmi/'.$_GET['flna'].'.pdf';	break;

	case 'pwht'	: $filepath = PCS_REP_PDF.'/pwht/'.$_GET['flna'].'.pdf';	break;

	case 'nde'	: $filepath = PCS_REP_PDF.'/nde/'.$_GET['flna'].'.pdf';	break;

	case 'heat'	: $filepath = PCS_REP_PDF.'/heat/'.$_GET['flna'].'.pdf';	break;

	default		: $filepath = PCS_REP_PDF.'/'.$_GET['flna'].'.pdf';	break;
}


$filesize = filesize($filepath);
$path_parts = pathinfo($filepath);
$filename = $path_parts['basename'];
$extension = $path_parts['extension'];
 
header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $filesize");
 
ob_clean();
flush();
readfile($filepath);
?>