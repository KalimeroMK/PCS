<?php
if (!$_POST['result'] || $_POST['log_type'] == '') {

    $query_field = "DESCRIBE " . G5_TABLE_PREFIX . "pcs_info_jnt_sbc";
    $field_enum_value = enum_value($query_field);


    ?>
    <form method="post">
        <input type='hidden' name='result' value='check'>
        <table class="main">
            <caption> SELECT JOINT STATUS</caption>
            <tbody>
            <tr>
                <td class="main_td jnt_th">Log Type</td>
                <td class="main_td jnt_th">Unit No.</td>
                <td class="main_td jnt_th">ISO Dwg No</td>
                <td class="main_td jnt_th">Material</td>
                <td class="main_td jnt_th">Shop / Field</td>
                <td class="main_td jnt_th">Button</td>
            </tr>

            <tr>
                <td class="main_td">
                    <select name='log_type' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
                        <option value=''>-</option>
                        <option value='WELDING'>WELDING</option>
                        <option value='SUPPORT'>SUPPORT</option>
                        <option value='SPOOL'>SPOOL</option>
                    </select>
                </td>
                <td class="main_td">
                    <select name='unit' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
                        <option value=''>-</option>
                        <?php sel_option_enum($field_enum_value['unit']); ?>
                    </select>
                </td>
                <td class="main_td">
                    <input type='text' name='iso_name'
                           style='padding:0px 0px 0px 5px; text-align:left;width:90%;height:30px;font-size:15px;background-color:bisque;'>
                </td>
                <td class="main_td">
                    <select name='material' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
                        <option value=''>-</option>
                        <?php sel_option_enum($field_enum_value['material']); ?>
                    </select>
                </td>
                <td class="main_td">
                    <select name='s_f' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
                        <option value=''>-</option>
                        <?php sel_option_enum($field_enum_value['s_f']); ?>
                    </select>
                </td>
                <td class="main_td">
                    <input type='submit' style='WIDTH: 100%; height: 40px; font-size:13px;' value='check'>
                </td>
            </tr>

            </tbody>
        </table>
    </form>

    <?php
} else {

    if ($_POST[log_type] == 'SPOOL') {
        $query_jnt = "SELECT dwg_no, j_no FROM " . G5_TABLE_PREFIX . "pcs_info_jnt_sbc WHERE dwg_no!='' AND j_no LIKE '%SP%' ";
        if ($_POST['unit']) {
            $query_jnt .= "AND unit = '" . $_POST['unit'] . "' ";
        };
        if ($_POST[material]) {
            $query_jnt .= "AND material = '" . $_POST[material] . "' ";
        };
        $query_jnt .= "ORDER BY dwg_no, j_no";

        $sql_jnt = sql_query($query_jnt);
        ?>
        <table class="main">
            <caption> <?php echo $_POST[log_type]; ?> LIST STATUS</caption>
            <tbody>
            <tr>
                <td class="main_td jnt_th">No.</td>
                <td class="main_td jnt_th">dwg no.</td>
                <td class="main_td jnt_th">spool no</td>
                <td class="main_td jnt_th">dwg.2</td>
            </tr>

            <?php

            $no = 0;

            while ($sql_jnt_arr = sql_fetch_array($sql_jnt)) {
                ?>

                <tr>
                    <td class="main_td"><?php echo ++$no; ?></td>
                    <td class="main_td"><?php echo $sql_jnt_arr['dwg_no']; ?></td>
                    <td class="main_td"><?php echo $sql_jnt_arr['dwg_no'] . '-' . $sql_jnt_arr['j_no']; ?></td>
                    <td class="main_td"></td>
                </tr>

                <?php $temp_spno = $sql_jnt_arr['spool_no'];
            } ?>

            </tbody>
        </table>

        <?php

    } else {

        $log_welding_th = array('No', 'Unit', 'Level', 'Drawing no.', 'Joint<br>no.', 'Joint<br>type', "Mat'l", 'S/F', 'NPS', 'Sche', 'Item 1', 'Item 2', 'Spool no.', 'Package no.');
        $log_welding_width = array(5, 5, 5, 15, 5, 5, 5, 5, 5, 5, 5, 5, 15, 15);
        $log_welding_field = array('unit', 'ag_ug', 'dwg_no', 'j_no', 'j_type', 'material', 's_f', 'j_size', 'j_sche', 'item_1_type', 'item_2_type', 'spool_no', 'pkg_no');


        $query_jnt = "SELECT A.*, B.* FROM " . G5_TABLE_PREFIX . "pcs_info_jnt_sbc AS A JOIN " . G5_TABLE_PREFIX . "pcs_info_jnt_ext AS B ON A.j_no = B.j_no AND A.dwg_no = B.dwg_no WHERE ";


        switch ($_POST[log_type]) {
            case 'WELDING'    :
                $query_jnt .= "A.pcs_j_type != 'SPL' AND A.pcs_j_type != 'PS' ";
                break;
            case 'SUPPORT'    :
                $query_jnt .= "A.pcs_j_type = 'PS' ";
                break;
            default :
                break;
        }

        if ($_POST['unit']) {
            $query_jnt .= "AND A.unit = '" . $_POST['unit'] . "' ";
        };
        if ($_POST[material]) {
            $query_jnt .= "AND B.material = '" . $_POST[material] . "' ";
        };
        if ($_POST[s_f]) {
            $query_jnt .= "AND A.s_f = '" . $_POST[s_f] . "' ";
        };

        $query_jnt .= "ORDER BY A.dwg_no, A.j_no";

//			"dwg_no LIKE '%".$_POST[iso_name]."%' ORDER BY dwg_no, j_no";

        $sql_jnt = sql_query($query_jnt);

//			echo $query_jnt;
        ?>

        <table class="main">
        <caption> <?php echo $_POST[log_type]; ?> LOG STATUS</caption>
        <tbody>
        <tr>

            $counter = count($log_welding_th);<?php
            for ($i = 0; $i < $counter; $i++) {
                ?>

                <td class="main_td jnt_th"
                    style="width: <?php echo $log_welding_width[$i]; ?>%;"><?php echo $log_welding_th[$i]; ?></td>

                <?php
            }
            ?>

        </tr>

        <?php

        $no = 0;

        while ($sql_jnt_arr = sql_fetch_array($sql_jnt)) {
//			print_r($sql_jnt_arr);
//			echo '<br>';
            ?>
            <tr>
                <td class="jnt_td"><?php echo ++$no ?></td>


                $counter = count($log_welding_field);<?php
                for ($i = 0; $i < $counter; $i++) {
                    ?>

                    <td class="jnt_td"
                        style="width: <?php echo $log_welding_width[$i + 1]; ?>px;"> <?php echo z_rem_jno($sql_jnt_arr["$log_welding_field[$i]"]); ?></td>

                    <?php
                }
                ?>

            </tr>

            <?php
        }
    }
    ?>





    </tbody>
    </table>
    <?php
}
?>
<p>&nbsp;