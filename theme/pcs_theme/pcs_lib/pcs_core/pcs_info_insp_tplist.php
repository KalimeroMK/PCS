<?php

$date_1f = $_POST['sel_1f'];
$date_1t = $_POST['sel_1t'];
$date_2f = $_POST['sel_2f'];
$date_2t = $_POST['sel_2t'];
$date_3f = $_POST['sel_3f'];
$date_3t = $_POST['sel_3t'];
$query_insp_spl = 'SELECT A.*, B.* FROM ' . G5_TABLE_PREFIX . 'pcs_info_tp AS A JOIN ' . G5_TABLE_PREFIX . 'pcs_info_tp_stat AS B ON A.tp_no = B.tp_no WHERE ';
$and_check = 0;
for ($i = 0; $mysql_field_array[$i]; $i++) {

    switch ($i) {

        case 1 :
            if ($_POST[$mysql_field_array[$i]]) {
                if ($and_check !== 0) {
                    $query_insp_spl .= ' AND ';
                }
                $query_insp_spl .= $mysql_field_array[$i] . ' LIKE "%' . $_POST[$mysql_field_array[$i]] . '%"';
                $and_check++;
            }
            break;

        case 3 :
            if ($date_1f) {
                if ($and_check !== 0) {
                    $query_insp_spl .= ' AND ';
                }
                $query_insp_spl .= '( "' . $date_1f . ' 00:00:00" <= ' . $mysql_field_array[$i] . ' AND ' . $mysql_field_array[$i] . ' <= "' . $date_1t . ' 23:59:59" )';
                $and_check++;
            }
            break;

        case 5 :
            if ($date_2f) {
                if ($and_check !== 0) {
                    $query_insp_spl .= ' AND ';
                }
                $query_insp_spl .= '( "' . $date_2f . ' 00:00:00" <= ' . $mysql_field_array[$i] . ' AND ' . $mysql_field_array[$i] . ' <= "' . $date_2t . ' 23:59:59" )';
                $and_check++;
            }
            break;

        case 7 :
            if ($date_3f) {
                if ($and_check !== 0) {
                    $query_insp_spl .= ' AND ';
                }
                $query_insp_spl .= '( "' . $date_3f . ' 00:00:00" <= ' . $mysql_field_array[$i] . ' AND ' . $mysql_field_array[$i] . ' <= "' . $date_3t . ' 23:59:59" )';
            }
            break;


        default:
            if ($_POST[$mysql_field_array[$i]]) {
                if ($and_check !== 0) {
                    $query_insp_spl .= ' AND ';
                }
                $query_insp_spl .= $mysql_field_array[$i] . ' = "' . $_POST[$mysql_field_array[$i]] . '" ';
                $and_check++;
            }
            break;
    }
}
$query_insp_spl .= ' ORDER BY A.tp_no';
//	echo $query_insp_spl;
?>
    <table class="main">
        <caption> Tie-in Point STATUS</caption>
        <tbody>
        <tr>
            <td class="jnt_td jnt_th" style="width: 5%"> No.</td>
            <td class="jnt_td jnt_th" style="width: 20%"> TP No.</td>
            <td class="jnt_td jnt_th" style="width: 10%"> Photo 1 by /<br> Photo 1 time</td>
            <td class="jnt_td jnt_th" style="width: 15%"> Photo 1</td>
            <td class="jnt_td jnt_th" style="width: 10%"> Photo 2 by /<br> Photo 2 time</td>
            <td class="jnt_td jnt_th" style="width: 15%"> Photo 2</td>
            <td class="jnt_td jnt_th" style="width: 10%"> Photo 3 by /<br> Photo 3 time</td>
            <td class="jnt_td jnt_th" style="width: 15%"> Photo 3</td>
        </tr>

        <?php
        $sql_insp_spl = sql_query($query_insp_spl);
        while ($sql_insp_spl_arr = sql_fetch_array($sql_insp_spl)) {

            $no++;
            $query_g5_spl = 'SELECT wr_id FROM ' . G5_TABLE_PREFIX . 'write_tp WHERE wr_subject = "' . $sql_insp_spl_arr['tp_no'] . '"';
            $sql_g5_spl = sql_query($query_g5_spl);
            $sql_g5_spl_arr = sql_fetch_array($sql_g5_spl);
            ?>
            <tr>
                <td class="jnt_td"><?php echo $no; ?></td>
                <td class="jnt_td"><a
                            href=<?php echo G5_URL . '/bbs/board.php?bo_table=tp&wr_id=' . $sql_g5_spl_arr['wr_id']; ?> target='_self'>
                        <font style='font-size:25px;'> <b> <?php echo $sql_insp_spl_arr['tp_no']; ?></b></font></a></td>
                <td class="jnt_td"><?php if ($sql_insp_spl_arr['tp_photo1_by']) {
                        echo $sql_insp_spl_arr['tp_photo1_by'] . '<br>' . $sql_insp_spl_arr['tp_photo1_tm'];
                    } ?></td>
                <td class="jnt_td"><?php if ($sql_insp_spl_arr['tp_photo1_by']) {
                        photo_thumb('tp', $sql_insp_spl_arr['tp_photo1'], '', 150);
                    } ?></td>
                <td class="jnt_td"><?php if ($sql_insp_spl_arr['tp_photo2_by']) {
                        echo $sql_insp_spl_arr['tp_photo2_by'] . '<br>' . $sql_insp_spl_arr['tp_photo2_tm'];
                    } ?></td>
                <td class="jnt_td"><?php if ($sql_insp_spl_arr['tp_photo2_by']) {
                        photo_thumb('tp', $sql_insp_spl_arr['tp_photo2'], '', 150);
                    } ?></td>
                <td class="jnt_td"><?php if ($sql_insp_spl_arr['tp_photo3_by']) {
                        echo $sql_insp_spl_arr['tp_photo3_by'] . '<br>' . $sql_insp_spl_arr['tp_photo3_tm'];
                    } ?></td>
                <td class="jnt_td"><?php if ($sql_insp_spl_arr['tp_photo3_by']) {
                        photo_thumb('tp', $sql_insp_spl_arr['tp_photo3'], '', 150);
                    } ?></td>
            </tr>

            <?php
        }
        ?>
        </tbody>
    </table>

    <p>&nbsp;
<?php 
