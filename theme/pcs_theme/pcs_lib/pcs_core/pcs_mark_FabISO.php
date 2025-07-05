<?php
include_once(__DIR__ . '/../common.php');

include_once(__DIR__ . '/pcs_config.php');

use setasign\Fpdi\Tcpdf\Fpdi;

require_once(__DIR__ . '/../pdfcode/FPDF/fpdf.php');
require_once(__DIR__ . '/../pdfcode/TCPDF/tcpdf_import.php');
require_once(__DIR__ . '/../pdfcode/FPDI/src/autoload.php');
require_once(__DIR__ . '/../pdfcode/TCPDF/tcpdf_barcodes_2d.php');


$pcs_markedPDF = new Fpdi('L', 'mm', 'A3', true, 'UTF-8', false);
$pcs_markedPDF->setPrintHeader(false);
$pcs_markedPDF->setPrintFooter(false);

$pcs_markedPDF->AddPage();

$pcs_markedPDF->setSourceFile(PCS_DWG_ISO . '/' . $_POST['mapped_dwg'] . '/' . $_POST['mapped_dwg'] . '_' . $_POST['rev_dwg'] . '.pdf');
$tplIdx = $pcs_markedPDF->importPage(1);
$pcs_markedPDF->useTemplate($tplIdx, 0, 0, 420);

pcsqrcode($pcs_markedPDF, 'dwg_', $_POST['mapped_dwg'], $_POST['shop_dwg']);


$query_dwg_coor_check = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_iso_coor WHERE dwg_no = "' . $_POST['mapped_dwg'] . '" AND rev_no = "' . $_POST['rev_dwg'] . '"';
$sql_dwg_coor_check = sql_query($query_dwg_coor_check);
$sql_dwg_coor_array = sql_fetch_array($sql_dwg_coor_check);
$dwg_coor_info = $sql_dwg_coor_array['joint_info'];
$jointcoor = explode(';', $dwg_coor_info);


$temp_j = count($jointcoor) - 1;
for ($j = 0; $j < $temp_j; $j++) {
    PDFjointmarking($pcs_markedPDF, $_POST['mapped_dwg'], $jointcoor[$j], '', '');
}

$pcs_markedPDF->Output(PCS_DWG_ISO . '/' . $_POST['mapped_dwg'] . '/fab_' . $_POST['mapped_dwg'] . '_' . $_POST['rev_dwg'] . '.pdf', 'F');

echo '
	<html>
	<head>
	</head>
	<body>

	<form name="form" action="' . PCS_WPV_URL . '/viewer.php" method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="folder" value="dwg_iso/' . $_POST['mapped_dwg'] . '">
	<input type="hidden" name="file" value="fab_' . $_POST['mapped_dwg'] . '">
	<input type="hidden" name="rev" value="' . $_POST['rev_dwg'] . '">
	<script>document.form.submit();</script>
	</form>

	</body>
	</html>
	';