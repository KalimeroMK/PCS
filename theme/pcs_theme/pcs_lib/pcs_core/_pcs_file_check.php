<?php

$fld_name = $_POST[type_chk];

switch ($fld_name) {
    case 'dwg_pdf':
    case 'dwg_png':
        $txt_align = 'right';
        $fld_name = 'dwg_no';
        $file_dir = PCS_DWG_ISO;
        $query_select = "SELECT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_drawing";
        break;
    case 'pkg_pdf' :
        $txt_align = 'left';
        $fld_name = 'pkg_no';
        $file_dir = PCS_PKG_PDF;
        $query_select = "SELECT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_package";
        break;
    case 'pwht_rep' :
        $txt_align = 'left';
        $file_dir = PCS_REP_PDF . '/pwht';
        $query_select = "SELECT DISTINCT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE " . $fld_name . " <>'' ORDER BY " . $fld_name . " ASC";
        break;
    case 'pmi_rep' :
        $txt_align = 'left';
        $file_dir = PCS_REP_PDF . '/pmi';
        $query_select = "SELECT DISTINCT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE " . $fld_name . " <>'' ORDER BY " . $fld_name . " ASC";
        break;
    case 'ut_rep' :
        $txt_align = 'left';
        $fld_name = 'rt_rep';
        $file_dir = PCS_REP_PDF . '/ut';
        $query_select = "SELECT DISTINCT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE " . $fld_name . " LIKE '%PA%' ORDER BY " . $fld_name . " ASC";
        break;
    case 'rt_rep' :
        $txt_align = 'left';
        $file_dir = PCS_REP_PDF . '/rt';
        $query_select = "SELECT DISTINCT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE " . $fld_name . " LIKE '%RT%' ORDER BY " . $fld_name . " ASC";
        break;
    case 'mt_rep' :
        $txt_align = 'left';
        $file_dir = PCS_REP_PDF . '/mt';
        $query_select = "SELECT DISTINCT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE " . $fld_name . " <>'' ORDER BY " . $fld_name . " ASC";
        break;
    case 'pt_rep' :
        $txt_align = 'left';
        $file_dir = PCS_REP_PDF . '/pt';
        $query_select = "SELECT DISTINCT " . $fld_name . " FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE " . $fld_name . " <>'' ORDER BY " . $fld_name . " ASC";
        break;
}

$sql_ref_pkg = sql_query($query_select);

$i = 0;
?>
<table class='__se_tbl'
       style='font-size:15px; border-width: 2px 2px 0px 0px; border-style: solid solid none none; border-color: black black currentColor currentColor;'
       width='<?php
       if ($_POST[type_chk] == 'pkg_pdf') {
           echo '40';
       } else {
           echo '30';
       } ?>%'
       border='0' cellspacing='0' cellpadding='0'>
    <caption><span style="font-size: 20pt;"> REPORT LIST </span>
        <tbody>
        <tr>
            <td style='border-width: 0px 0px 2px 2px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 50px; height: 50px; background-color: aqua;'>
                <p align='center'>No.</p></td>
            <td style='border-width: 0px 0px 2px 2px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 150px; height: 50px; background-color: aqua;'>
                <p align='center'>Lost File</p></td>
        </tr>
        <?php
        while ($sql_ref_pkg_arr = sql_fetch_array($sql_ref_pkg)) {
            if ($_POST[type_chk] == 'dwg_png') {
                $file_name = $file_dir . '/' . $sql_ref_pkg_arr[$fld_name] . '.png';
            } else {
                $file_name = $file_dir . '/' . $sql_ref_pkg_arr[$fld_name] . '.pdf';
            }
            $file_name = str_replace('-A', '', $file_name);    //
            if (!file_exists($file_name)) {
                ?>
                <tr>
                    <td style='border-width: 0px 0px 2px 2px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 50px; height: 30px;'>
                        <p align='center'><?php
                            echo ++$i; ?></p></td>
                    <td style='border-width: 0px 0px 2px 2px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 150px; height: 30px;'>
                        <p align='<?php echo $txt_align; ?>'
                           style="margin-<?php echo $txt_align; ?>: 30px;"><?php echo $sql_ref_pkg_arr[$fld_name]; ?></p>
                    </td>
                </tr>
            <?php }
        }
        ?>
        </tbody>
</table>
