<?php
include_once(__DIR__ . '/_common.php');
include_once(__DIR__ . '/pcs_config.php');
include_once(__DIR__ . '/pcs_common_function.php');

use setasign\Fpdi\Tcpdf\Fpdi;

require_once(__DIR__ . '/../pdfcode/FPDF/fpdf.php');
require_once(__DIR__ . '/../pdfcode/TCPDF/tcpdf_import.php');
require_once(__DIR__ . '/../pdfcode/FPDI/src/autoload.php');

$pnid_dwg = $_POST['mapped_pnid'];
$pnid_rev = $_POST['rev_pnid'];

$pcs_markedPDF = new Fpdi('L','mm','A3', true, 'UTF-8', false);
$pcs_markedPDF->setPrintHeader(false);
$pcs_markedPDF->setPrintFooter(false);

$pcs_markedPDF->AddPage();

$pcs_markedPDF->setSourceFile(PCS_PNID_PDF.'/'.$pnid_dwg.'_'.$pnid_rev.'.pdf');
$tplIdx = $pcs_markedPDF->importPage(1);
$pcs_markedPDF->useTemplate($tplIdx, 0, 0, 420);

$color_array = array(array(255,255,255),array(255,64,0),array(255,128,0),array(255,191,0),array(255,255,0),array(191,255,0),array(0,255,0),array(0,255,255),array(0,191,255),array(0,64,255),array(0,0,255),array(191,0,255),array(255,0,255));
$color_red = array(255,0,0);

$temp_i = count($color_array);
for($i=0;$i<$temp_i;$i++){
	$dwg_mark_line[$i] = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_array[$i]);
	$dwg_pkg_line[$i] = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_array[$i]);
}
$pkg_page_line = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_red);

$pcs_markedPDF->Circle(500,500, 2,0,360,'F',$dwg_mark_line,array(255,255,255));

	$query_pnid_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_no = "'.$pnid_dwg.'" AND rev_no = "'.$pnid_rev.'"';
	$sql_pnid_coor_check = sql_query ($query_pnid_coor_check);
	$sql_pnid_coor_array = sql_fetch_array ($sql_pnid_coor_check);
	$pnid_coor_info = $sql_pnid_coor_array['pnid_coor'];
	$jointcoor = explode(';',$pnid_coor_info);


	$temp_j = count($jointcoor)-1;
if(!$_POST['pkg_no']){	
	for($j=0;$j<$temp_j;$j++){

		$jointcoor_val = explode(',',$jointcoor[$j]);

		$xf = round($jointcoor_val[3]/5);
		$yf = round($jointcoor_val[4]/5);
		$xt = round($jointcoor_val[5]/5);
		$yt = round($jointcoor_val[6]/5);


		$query_officialPKG = 'SELECT offi_no FROM '.G5_TABLE_PREFIX.'pcs_info_package WHERE pkg_no = "'.$jointcoor_val[1].'"';
		$sql_officialPKG = sql_query ($query_officialPKG);
		$sql_officialPKGNO = sql_fetch_array ($sql_officialPKG);

//		if($jointcoor_val[12]=='ACT'){

			switch ($jointcoor_val[2]) {

				case 'line' :
					$pcs_markedPDF->SetAlpha(0.5);
					$pcs_markedPDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line[$jointcoor_val[7]]);
				break;

				case 'blind':
					$pcs_markedPDF->SetAlpha(0.7);
					$pcs_markedPDF->StartTransform();
					$pcs_markedPDF->Rotate(compass($xt-$xf,$yt-$yf)+180,$xf,$yf);
					$pcs_markedPDF->Polygon(array($xf-5,$yf,$xf-3.5,$yf-2,$xf-2,$yf,$xf+5,$yf), 'DF', array('all' => $pkg_page_line), $color_red);
					$pcs_markedPDF->StopTransform();
				break;

				case 'pkg' :
					$angle = compass($xt-$xf,$yt-$yf);
					if ($angle<90) {
                        $FX = $xt-18 ;
                        $FY = $yt-3 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    } elseif ($angle<180) {
                        $FX = $xt-18 ;
                        $FY = $yt+1 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    } elseif ($angle<270) {
                        $FX = $xt+22 ;
                        $FY = $yt+1 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    } elseif ($angle<360) {
                        $FX = $xt+22 ;
                        $FY = $yt-3 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    }

					$pcs_markedPDF->SetFont('helvetica', '', 8);
					$text_x = $xt-17;
					$text_y = $yt-3;

					$pcs_markedPDF->SetAlpha(0.5);
					$pcs_markedPDF->Line($xf,$yf,$FX,$FY,$dwg_pkg_line[$jointcoor_val[7]]);
					$pcs_markedPDF->Rect($TX,$TY,50,4, 'D', array('all' => $dwg_pkg_line[$jointcoor_val[7]]));
					$pcs_markedPDF->SetAlpha(0.7);
					$pcs_markedPDF->SetTextColor(0,0,255);
					$pcs_markedPDF->Text($text_x,$text_y,$sql_officialPKGNO['offi_no']);

				break;

				default :	break;
			}

//		}
	}
$pcs_markedPDF->Output(PCS_PNID_MST.'/master_'.$pnid_dwg.'_'.$pnid_rev.'.pdf','F');

echo '<html>
<head>
</head>
	<body>

	<form name="form" action="'.PCS_WPV_URL.'/viewer.php" method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="folder" value="pnid/master">
	<input type="hidden" name="file" value="master_'.$pnid_dwg.'">
	<input type="hidden" name="rev" value="'.$pnid_rev.'">
	<script>document.form.submit();</script>
	</form>

</body>
</html>
';

}
else {
	for($j=0;$j<$temp_j;$j++){
		
		$jointcoor_val = explode(',',$jointcoor[$j]);
		
		$xf = round($jointcoor_val[3]/5);
		$yf = round($jointcoor_val[4]/5);
		$xt = round($jointcoor_val[5]/5);
		$yt = round($jointcoor_val[6]/5);

		$query_officialPKG = 'SELECT offi_no FROM '.G5_TABLE_PREFIX.'pcs_info_package WHERE pkg_no = "'.$jointcoor_val[1].'"';
		$sql_officialPKG = sql_query ($query_officialPKG);
		$sql_officialPKGNO = sql_fetch_array ($sql_officialPKG);
		
		if($jointcoor_val[1]==$_POST['pkg_no']){

			switch ($jointcoor_val[2]) {

				case 'line' :
					$pcs_markedPDF->SetAlpha(0.5);
					$pcs_markedPDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line[6]);
				break;

				case 'blind':
					$pcs_markedPDF->SetAlpha(0.7);
					$pcs_markedPDF->StartTransform();
					$pcs_markedPDF->Rotate(compass($xt-$xf,$yt-$yf)+180,$xf,$yf);
					$pcs_markedPDF->Polygon(array($xf-5,$yf,$xf-3.5,$yf-2,$xf-2,$yf,$xf+5,$yf), 'DF', array('all' => $pkg_page_line), $color_red);
					$pcs_markedPDF->StopTransform();
				break;

				case 'pkg' :
					$angle = compass($xt-$xf,$yt-$yf);
					if ($angle<90) {
                        $FX = $xt-18 ;
                        $FY = $yt-3 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    } elseif ($angle<180) {
                        $FX = $xt-18 ;
                        $FY = $yt+1 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    } elseif ($angle<270) {
                        $FX = $xt+22 ;
                        $FY = $yt+1 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    } elseif ($angle<360) {
                        $FX = $xt+22 ;
                        $FY = $yt-3 ;
                        $TX = $xt-18 ;
                        $TY = $yt-3 ;
                    }

					$pcs_markedPDF->SetFont('helvetica', '', 8);
					$text_x = $xt-17;
					$text_y = $yt-3;

					$pcs_markedPDF->SetAlpha(0.5);
					$pcs_markedPDF->Line($xf,$yf,$FX,$FY,$dwg_pkg_line[6]);
					$pcs_markedPDF->Rect($TX,$TY,50,4, 'D', array('all' => $dwg_pkg_line[6]));
					$pcs_markedPDF->SetAlpha(0.7);
					$pcs_markedPDF->SetTextColor(0,0,255);
					$pcs_markedPDF->Text($text_x,$text_y,$sql_officialPKGNO['offi_no']);

				break;

				default :	break;
			}

		}
	}
$pcs_markedPDF->Output($pnid_dwg.'_'.$pnid_rev.'.pdf');
}


?>
