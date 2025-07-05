<?php

switch ($prg_sel) {
    case wel :
        $prg = "w_type = 'weld' AND j_type <> 'sfw' AND vi_date = '0000-00-00' AND ";
        break;

    case supt :
        $prg = "j_type = 'sfw' AND vi_date = '0000-00-00' AND ";
        break;

    case pwht :
        $prg = "w_type = 'weld' AND pwht_yn = 'YES' AND (pwht_rlt <> 'Accept' OR pwht_rlt = '') AND ";
        break;

    case pmi :
        $prg = "w_type = 'weld' AND (pmi_rlt <> 'Accept' OR pmi_rlt = '') AND ";
        break;

    default :
        $prg = "";
        break;
}

switch ($nde_sel) {
    case rt_5 :
        $nde = "rt_rate = '5' AND ";
        break;

    case rt_10 :
        $nde = "rt_rate = '10' AND ";
        break;

    case rt_20 :
        $nde = "rt_rate = '20' AND ";
        break;

    case rt_100 :
        $nde = "rt_rate = '100' AND ";
        break;

    case rt_100_nac :
        $nde = "rt_rate = '100' AND (rt_rlt <> 'Accept' OR rt_rlt = '') AND ";
        break;

    case rt_acc :
        $nde = "rt_rlt = 'Accept' AND ";
        break;

    case mpt :
        $nde = "(mt_rlt = 'Accept' OR pt_rlt = 'Accept') AND ";
        break;

    default :
        $nde = "";
        break;
}

switch ($s_f_sel) {
    case s :
        $s_f = "s_f = 'S' AND ";
        break;

    case f :
        $s_f = "s_f = 'F' AND ";
        break;

    default :
        $s_f = "";
        break;
}

switch ($wjt_sel) {
    case bw :
        $wjt = "j_type = 'BW' AND ";
        break;

    case cw :
        $wjt = "j_type = 'CW' AND ";
        break;

    case ow :
        $wjt = "(j_type = 'SW' OR j_type = 'RP' OR j_type = 'SFW') AND ";
        break;

    default :
        $wjt = "";
        break;
}

$pkg = $pkg_sel ? "pkg_no = '" . $pkg_sel . "'" : "";

$dwg = $dwg_sel ? "dwg_no = '" . $dwg_sel . "'" : "";


$query_pkg = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_joint WHERE j_stat = '-' AND " . $prg . $nde . $s_f . $wjt . $pkg . $dwg;


?>

<table class="__se_tbl"
       style="border-width: 1px 1px 0px 0px; border-style: solid solid none none; border-color: black black currentColor currentColor;"
       width='100%' border="0" cellspacing="0" cellpadding="0">
    <caption><span style="font-size: 20pt;"> JOINT DETAIL </span></caption>
    <tbody>

    <tr>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: plum; "
            rowspan="2"
        <p align="center">No</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 150px; height: 36px; background-color: plum; "
            rowspan="2"><p align="center">Dwg. No</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 36px; background-color: plum; "
            rowspan="2" colspan="2"><p align="center">Download<br>& <br>View Dwg</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: plum; "
            rowspan="2"
        <p align="center">RT<br>Rate</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: plum; "
            rowspan="2"
        <p align="center">J.No</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: plum; "
            rowspan="2"
        <p align="center">Type</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: plum; "
            rowspan="2"
        <p align="center">S/F</p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: plum; "
            rowspan="2"
        <p align="center">NPS</p></td>

        <?php if ($prg_sel) { ?>
            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 80px; height: 36px; background-color: plum; "
                rowspan="2"<p align="center">Welding</p></td>
            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 80px; height: 36px; background-color: plum; "
                rowspan="2"<p align="center">PWHT</p></td>
            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 80px; height: 36px; background-color: plum; "
                rowspan="2"<p align="center">PMI</p></td>
        <?php } ?>

        <?php if ($nde_sel) { ?>
            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 30px; background-color: plum;"
                rowspan="1" colspan="4"><p align="center">NDE</p></td>
        <?php } ?>
    </tr>

    <tr>
        <?php if ($nde_sel) { ?>
            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 120px; height: 30px; background-color: plum;"
                rowspan="1" colspan="2"><p align="center">RT / UT</p></td>
            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 120px; height: 30px; background-color: plum;"
                rowspan="1" colspan="2"><p align="center">MT / PT</p></td>
        <?php } ?>
    </tr>

    <?php
    $sql_pkg = sql_query($query_pkg);

    $no = 1;

    $field_query = "DESC " . G5_TABLE_PREFIX . "pcs_info_joint";
    $field_name = field_name_array($field_query);


    while ($sql_pkg_arr = sql_fetch_array($sql_pkg))    {

    $counter = count($field_name);
    for ($i = 0; $i < $counter; $i++) {
        if ($sql_pkg_arr[$field_name[$i]] == '0000-00-00') {
            $sql_pkg_arr[$field_name[$i]] = false;
        }
    }
    ?>

    <tr>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center"> <?php echo $no++ ?> </p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 150px; height: 36px; background-color: white;">
            <p align="center">
                <?php if ($prev_dwg == $sql_pkg_arr[dwg_no]) {
                    echo "Ditto";
                } else {

                $query_dwgby_jnt = "SELECT wr_id FROM " . G5_TABLE_PREFIX . "write_drawing WHERE wr_subject = '" . $sql_pkg_arr[dwg_no] . "'";
                $sql_dwgby_jnt = sql_query($query_dwgby_jnt);
                $sql_dwgby_jnt_arr = sql_fetch_array($sql_dwgby_jnt); ?>

                <a href=<?php echo G5_URL . '/bbs/board.php?bo_table=drawing&wr_id=' . $sql_dwgby_jnt_arr[wr_id]; ?> target='_self'><?php echo $sql_pkg_arr[dwg_no];
                    } ?>    </a></p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center">
                <?php if ($prev_dwg == $sql_pkg_arr[dwg_no] || !$member['mb_5']) {
                    echo "-";
                } else { ?>
                    <a href="<?php echo PCS_LIB_URL . "/PDF_down.php?flde=dwg&flna=" . $sql_pkg_arr[dwg_no]; ?>"><img
                                src="<?php echo PCS_LIB_URL ?>/pdf.gif"
                                alt="<?php echo "PDF Drawing Download"; ?>"></a> <?php } ?> </p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center">
                <?php if ($prev_dwg == $sql_pkg_arr[dwg_no]) {
                    echo "-";
                } else { ?>
                    <a href='#'
                       onclick="window.open('<?php echo PCS_DWG_ISO_URL . '/' . $sql_pkg_arr[dwg_no] . ".png"; ?>','window','location=no,directories=no,resizable=yes,status=no,toolbar=no,menubar=no,width=1500,height=900,left=0,top=0,scrollbars=yes');return false">
                        <img src="<?php echo PCS_LIB_URL ?>/photo.gif"
                             alt="<?php echo "Show Drawing"; ?>"></a> <?php } ?> </p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center">
                <?php if ($sql_pkg_arr[j_type] == "BW") {
                    echo $sql_pkg_arr[rt_rate] . "%";
                } else {
                    echo "-";
                } ?></p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center"><?php echo $sql_pkg_arr['j_no']; ?></p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center"><?php echo $sql_pkg_arr[j_type]; ?></p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center"><?php echo $sql_pkg_arr[s_f]; ?></p></td>
        <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 36px; background-color: white;">
            <p align="center"><?php echo $sql_pkg_arr[nps] . ' "'; ?></p></td>

        <?php if ($prg_sel) { ?>

            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 36px; background-color: white;">
                <p align="center"><?php echo $sql_pkg_arr[vi_date]; ?></p></td>

            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 36px; background-color: white;">
                <p align="center"><?php echo $sql_pkg_arr[pwht_date] . '<br>' . $sql_pkg_arr[pwht_rlt]; ?></p></td>

            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 36px; background-color: white;">
                <p align="center"><?php echo $sql_pkg_arr[pmi_date] . '<br>' . $sql_pkg_arr[pmi_rlt]; ?></p></td>

        <?php } ?>


        <?php if ($nde_sel) { ?>

            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 20px; background-color: white;">
                <p align="center">

                    <?php
                    if ($sql_pkg_arr[rt_rlt] != 'Accept' || !$member['mb_5']) {
                        echo $sql_pkg_arr[rt_date] . '<br>' . $sql_pkg_arr[rt_rep];
                    } else {

                        switch (substr($sql_pkg_arr[rt_rep], 4, 2)) {

                            case RT :
                                echo pdf_report_download(rt, substr($sql_pkg_arr[rt_rep], 4, 7), $sql_pkg_arr[rt_rep], $sql_pkg_arr[rt_date]);
                                break;

                            case PA :
                                echo pdf_report_download(rt, substr($sql_pkg_arr[rt_rep], 6, 7), $sql_pkg_arr[pt_rep], $sql_pkg_arr[rt_date]);
                                break;

                            default :
                                break;
                        }
                    } ?>

                </p></td>


            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 20px; background-color:
            <?php if ($sql_pkg_arr[rt_rlt] != "Accept" && $sql_pkg_arr[rt_rate] == "100") {
                echo "red";
            } else {
                echo "white";
            } ?> ;"><p align="center"><?php echo substr($sql_pkg_arr[rt_rlt], 0, 3); ?></p></td>


            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 100px; height: 20px; background-color: white;">
                <p align="center">

                    <?php
                    if (!$member['mb_5']) {
                        echo $sql_pkg_arr[mt_date] . $sql_pkg_arr[pt_date] . '<br>' . $sql_pkg_arr[mt_rep] . $sql_pkg_arr[pt_rep];
                    } else {

                        switch (substr($sql_pkg_arr[mt_rep] . $sql_pkg_arr[pt_rep], 4, 2)) {

                            case MT :
                                echo pdf_report_download(mt, substr($sql_pkg_arr[mt_rep], 4, 7), $sql_pkg_arr[mt_rep], $sql_pkg_arr[mt_date]);
                                break;

                            case PT :
                                echo pdf_report_download(pt, substr($sql_pkg_arr[pt_rep], 4, 7), $sql_pkg_arr[pt_rep], $sql_pkg_arr[pt_date]);
                                break;

                            default :
                                break;
                        }
                    } ?>

                </p></td>


            <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; width: 50px; height: 20px; background-color: white;">
                <p align="center"><?php echo substr($sql_pkg_arr[pt_rlt], 0, 3) . substr($sql_pkg_arr[mt_rlt], 0, 3); ?></p>
            </td>

        <?php }
        $prev_dwg = $sql_pkg_arr[dwg_no];
        } ?>

    </tr>

    </tbody>
</table>


<p>&nbsp;</p><p>&nbsp;</p>
