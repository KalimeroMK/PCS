<?php
/*
print_r($_POST['chk_by']).'<br>';
echo '<br>';

print_r($_POST['sel_dwg']).'<br>';
echo '<br>';

//print_r($_POST['curr_dwg']);
print_r($_POST['curr_jnt']).'<br>';
echo '<br>';

echo count($_POST['sel_dwg']).'<br>';
echo count($_POST['curr_jnt']).'<br>';

$compressed = gzcompress('Compress me', 9);

*/


error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once(__DIR__ . '/_common.php');
include_once(__DIR__ . '/pcs_config.php');
include_once(__DIR__ . '/pcs_common_function.php');

use setasign\Fpdi\Tcpdf\Fpdi;
require_once(__DIR__ . '/../pdfcode/FPDF/fpdf.php');
require_once(__DIR__ . '/../pdfcode/TCPDF/tcpdf_import.php');
require_once(__DIR__ . '/../pdfcode/FPDI/src/autoload.php');

require_once(__DIR__ . '/../pdfcode/TCPDF/tcpdf_barcodes_2d.php');


$pcs_markedPDF = new Fpdi('L','mm','A3', true, 'UTF-8', false);
$pcs_markedPDF->setPrintHeader(false);
$pcs_markedPDF->setPrintFooter(false);

$temp_i = count($_POST['sel_dwg']);
for($i=0; $i<$temp_i; $i++){

	$query_total_pkg = 'SELECT DISTINCT dwg_no, rev_no, shop_dwg FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$_POST['sel_dwg'][$i].'"';
	$sql_total_pkg = sql_query ($query_total_pkg);
	$sql_total_pkg_array = sql_fetch_array ($sql_total_pkg);

	$pcs_markedPDF->AddPage();
	$pcs_markedPDF->setSourceFile(PCS_DWG_ISO.'/'.$sql_total_pkg_array['dwg_no'].'/'.$sql_total_pkg_array['dwg_no'].'_'.$sql_total_pkg_array['rev_no'].'.pdf');
	$tplIdx = $pcs_markedPDF->importPage(1); 
	$pcs_markedPDF->useTemplate($tplIdx, 0, 0, 420);


	$pcs_markedPDF->SetFont('helvetica', '', 10);
	$text_x = 5;
	$text_y = 5;
	$pcs_markedPDF->SetTextColor(0,0,255);
	$pcs_markedPDF->Text($text_x+15,$text_y+15,$_POST['insp_tp']);
	$pcs_markedPDF->Text($text_x+30,$text_y+15,$_POST['insp_rlt']);
	$pcs_markedPDF->Text($text_x+15,$text_y+20,G5_TIME_YMDHIS);
	$pcs_markedPDF->Text($text_x+15,$text_y+25,'Printed by : '.$member['mb_nick']);
	$pcs_markedPDF->Text($text_x+15,$text_y+30,'Page : '.($i+1).' / '.$temp_i);


	pcsqrcode($pcs_markedPDF,'dwg_',$sql_total_pkg_array['dwg_no'],$sql_total_pkg_array['shop_dwg']);



////////////////도면시작

	$query_dwg_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso_coor WHERE dwg_no = "'.$sql_total_pkg_array['dwg_no'].'" AND rev_no = "'.$sql_total_pkg_array['rev_no'].'"';
	$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
	$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);
	$dwg_coor_info = $sql_dwg_coor_array['joint_info'];
	$jointcoor = explode(';',$dwg_coor_info);


	$temp_j = count($jointcoor)-1;
	for($j=0;$j<$temp_j;$j++){PDFjointmarking($pcs_markedPDF, $sql_total_pkg_array['dwg_no'], $jointcoor[$j], $_POST['chk_by'][$sql_total_pkg_array['dwg_no']], $_POST['curr_jnt'][$sql_total_pkg_array['dwg_no']]);}

////////////////////////////////////////////
	

}
$pcs_markedPDF->Output(remove_spe_char(G5_TIME_YMDHIS).'.pdf');

?>
