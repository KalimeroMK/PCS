<?php
ini_set('display_errors', '0');

include_once(__DIR__ . '/../common.php');

include_once(__DIR__ . '/pcs_config.php');

use setasign\Fpdi\Tcpdf\Fpdi;

require_once(__DIR__ . '/../pdfcode/FPDF/fpdf.php');
require_once(__DIR__ . '/../pdfcode/TCPDF/tcpdf_import.php');
require_once(__DIR__ . '/../pdfcode/FPDI/src/autoload.php');

$pcs_markedPDF = new Fpdi('L', 'mm', 'A3', true, 'UTF-8', false);
$pcs_markedPDF->setPrintHeader(false);
$pcs_markedPDF->setPrintFooter(false);

$query_total_pkg = 'SELECT DISTINCT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_iso WHERE (pkg_no1 = "' . $_POST['pkg'] . '" OR pkg_no2 = "' . $_POST['pkg'] . '" OR pkg_no3 = "' . $_POST['pkg'] . '" OR pkg_no4 = "' . $_POST['pkg'] . '" OR pkg_no5 = "' . $_POST['pkg'] . '" OR pkg_no6 = "' . $_POST['pkg'] . '") ORDER BY line_size DESC';
$sql_total_pkg = sql_query($query_total_pkg);

$query_dwg_spec = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_dwgconfig';
$sql_dwg_spec = sql_query($query_dwg_spec);
$sql_dwg_spec_array = sql_fetch_array($sql_dwg_spec);

$pg_x = $sql_dwg_spec_array['pkg_x'] / 5;
$pg_y = $sql_dwg_spec_array['pkg_y'] / 5;

$page_no = 0;
$page_link[1] = 1;
$tb_qty = 0;

while ($sql_total_pkg_array = sql_fetch_array($sql_total_pkg)) {

    $page_no++;


    $pcs_markedPDF->AddPage();
    $pcs_markedPDF->SetLink($page_no);
//$pcs_markedPDF->setSourceFile(PCS_DWG_ISO.'/'.$sql_total_pkg_array['dwg_no'].'/'.$sql_total_pkg_array['shop_dwg'].'.pdf');
    $pcs_markedPDF->setSourceFile(PCS_DWG_ISO . '/' . $sql_total_pkg_array['dwg_no'] . '/fab_' . $sql_total_pkg_array['dwg_no'] . '_' . $sql_total_pkg_array['rev_no'] . '.pdf');
    $tplIdx = $pcs_markedPDF->importPage(1);
    $pcs_markedPDF->useTemplate($tplIdx, 0, 0, 420);

    $color_green = array(141, 253, 115);
    $color_red = array(255, 0, 0);
    $color_blue = array(0, 0, 255);

    $dwg_mark_line = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_blue);
    $pkg_mark_line = array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_green);
    $pkg_page_line = array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_red);

    $pcs_markedPDF->SetAlpha(0.7);
    $pcs_markedPDF->SetLineStyle($pkg_page_line);
    $pcs_markedPDF->RegularPolygon($pg_x, $pg_y, 7, 3, 60);
    if ($page_no < 10) {
        $pcs_markedPDF->SetFont('helvetica', '', 20);
        $text_x = $pg_x - 3;
        $text_y = $pg_y - 5;
    } elseif ($page_no < 100) {
        $pcs_markedPDF->SetFont('helvetica', '', 17);
        $text_x = $pg_x - 4.2;
        $text_y = $pg_y - 3;
    } else {
        $pcs_markedPDF->SetFont('helvetica', '', 14);
        $text_x = $pg_x - 5;
        $text_y = $pg_y - 2;
    }
    $pcs_markedPDF->SetTextColor(0, 0, 255);
    $pcs_markedPDF->Text($text_x, $text_y, $page_no);

    $query_pkg_coor_check = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor WHERE dwg_no = "' . $sql_total_pkg_array['dwg_no'] . '" AND pkg_no = "' . $_POST['pkg'] . '" AND rev_no = "' . $sql_total_pkg_array['rev_no'] . '"';
    $sql_pkg_coor_check = sql_query($query_pkg_coor_check);
    $sql_pkg_coor_array = sql_fetch_array($sql_pkg_coor_check);
    $pkg_coor_info = $sql_pkg_coor_array['joint_info'];
    $jointcoor = explode(';', $pkg_coor_info);

    $temp_j = count($jointcoor) - 1;
    for ($j = 0; $j < $temp_j; $j++) {

        $jointcoor_val = explode(",", $jointcoor[$j]);

        $xf = round($jointcoor_val[1] / 5);
        $yf = round($jointcoor_val[2] / 5);
        $xt = round($jointcoor_val[3] / 5);
        $yt = round($jointcoor_val[4] / 5);

        if ($jointcoor_val[8] == 'Act') {

            switch ($jointcoor_val[5]) {
                case 'line' :
                    $pcs_markedPDF->SetAlpha(0.5);
                    $pcs_markedPDF->Line($xf, $yf, $xt, $yt, $pkg_mark_line);
                    break;

                case 'page' :
                    $pcs_markedPDF->SetAlpha(0.7);
                    $pcs_markedPDF->SetLineStyle($pkg_page_line);
                    $pcs_markedPDF->RegularPolygon($xt, $yt, 7, 3, 60);
                    if ($jointcoor_val[7] < 10) {
                        $pcs_markedPDF->SetFont('helvetica', '', 20);
                        $text_x = $xt - 3;
                        $text_y = $yt - 5;
                    } elseif ($jointcoor_val[7] < 100) {
                        $pcs_markedPDF->SetFont('helvetica', '', 17);
                        $text_x = $xt - 4.2;
                        $text_y = $yt - 3;
                    } else {
                        $pcs_markedPDF->SetFont('helvetica', '', 14);
                        $text_x = $xt - 5;
                        $text_y = $yt - 2;
                    }
                    $pcs_markedPDF->SetTextColor(0, 0, 255);
                    $page_link[$jointcoor_val[7]] = $pcs_markedPDF->AddLink();
                    $page_link[$jointcoor_val[7]] = $jointcoor_val[7] * 1;
                    $pcs_markedPDF->Text($text_x, $text_y, $jointcoor_val[7], false, false, true, 0, 0, '', false, $page_link[$jointcoor_val[7]]);
                    break;

                case 'blind':
                    $pcs_markedPDF->SetAlpha(0.7);
                    $pcs_markedPDF->StartTransform();
                    $pcs_markedPDF->Rotate(compass($xt - $xf, $yt - $yf) + 180, $xf, $yf);
                    $pcs_markedPDF->Polygon(array($xf - 7, $yf, $xf - 5, $yf - 3, $xf - 3, $yf, $xf + 7, $yf), 'DF', array('all' => $pkg_page_line), $color_red);
                    $pcs_markedPDF->StopTransform();
                    break;

                case 'tbno' :
                    $pcs_markedPDF->SetAlpha(0.7);
                    $pcs_markedPDF->SetLineStyle($pkg_page_line);
                    $pcs_markedPDF->Circle($xt, $yt, 7);
                    $pcs_markedPDF->SetFont('helvetica', '', 17);
                    $pcs_markedPDF->SetTextColor(0, 0, 255);
                    $pcs_markedPDF->Text($xt - 4.5, $yt - 6, 'TB');
                    $jointcoor_val[7] += $tb_qty;
                    if ($jointcoor_val[7] < 10) {
                        $pcs_markedPDF->SetFont('helvetica', '', 17);
                        $text_x = $xt - 4;
                        $text_y = $yt - 1;
                        $jointcoor_val[7] = '0' . $jointcoor_val[7];
                    } elseif ($jointcoor_val[7] < 100) {
                        $pcs_markedPDF->SetFont('helvetica', '', 17);
                        $text_x = $xt - 4;
                        $text_y = $yt - 1;
                    } else {
                        $pcs_markedPDF->SetFont('helvetica', '', 14);
                        $text_x = $xt - 5;
                        $text_y = $yt - 1;
                    }
                    $pcs_markedPDF->Text($text_x, $text_y, $jointcoor_val[7]);
                    break;
                case 'mark' :
                    $pcs_markedPDF->SetAlpha(0.7);
                    $pcs_markedPDF->SetLineStyle($pkg_page_line);
                    $pcs_markedPDF->Circle($xt, $yt, 7);
                    $pcs_markedPDF->SetFont('helvetica', '', 17);
                    $pcs_markedPDF->SetTextColor(0, 0, 255);
                    $pcs_markedPDF->Text($xt - 4.5, $yt - 3, $jointcoor_val[7]);
                    break;

                default :
                    break;
            }
        }
    }
    $tb_qty += $sql_pkg_coor_array['tb_qty'];

}
$pcs_markedPDF->Output($_POST['pkg'] . '.pdf');


function compass($x, $y): int|float
{
    if ($x == 0) {
        if ($y > 0) {
            return 0;
        } else {
            return 180;
        }
    }
    return ($x < 0)
        ? rad2deg(atan2($x, $y)) + 360
        : rad2deg(atan2($x, $y));
}


?>
