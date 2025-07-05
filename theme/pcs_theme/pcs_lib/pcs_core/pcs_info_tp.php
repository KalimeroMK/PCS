<?php
if (($_POST['folder'] ?? null) || ($_POST['ph'] ?? null)) {
    include_once(PCS_LIB . '/pcs_photo.php');
} else {

    $query_tp_stat_set = 'INSERT INTO ' . G5_TABLE_PREFIX . 'pcs_info_tp_stat (tp_no) VALUES ("' . $view['wr_subject'] . '")';
    sql_query($query_tp_stat_set);

    $query_tp = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_tp WHERE tp_no = "' . $view['wr_subject'] . '"';
    $sql_tp = sql_query($query_tp);
    $sql_tp_arr = sql_fetch_array($sql_tp);

    $query_tp_plan = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_plan WHERE plan_no = "' . $sql_tp_arr['plan_no'] . '"';
    $sql_tp_plan = sql_query($query_tp_plan);
    $sql_tp_plan_arr = sql_fetch_array($sql_tp_plan);

    $query_tp_pnid = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_pnid WHERE pnid_no = "' . $sql_tp_arr['pnid_no'] . '"';
    $sql_tp_pnid = sql_query($query_tp_pnid);
    $sql_tp_pnid_arr = sql_fetch_array($sql_tp_pnid);

    $query_tp_info = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_tp_stat WHERE tp_no = "' . $view['wr_subject'] . '"';
    $sql_tp_info = sql_query($query_tp_info);
    $sql_tp_info_arr = sql_fetch_array($sql_tp_info);


    $Dwg_array = explode(';', $sql_tp_arr['dwg_no']);


    if (!G5_IS_MOBILE) { /////////// PC 버전 시작

        ?>

        <table class="main">
            <caption> SPECIFICATION</caption>
            <tbody>

            <tr>
                <td class="main_td td_sub" style="height:80px;" colspan="6"> Tie-in Point
                    <b><?php echo $view['wr_subject']; ?></b> INFORMATION
                </td>
                <form name='submit_for' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post"
                      target="<?php echo $view['wr_subject']; ?>" onSubmit="return doSumbit()">
                    <input type="hidden" name="folder" value="tp">
                    <input type="hidden" name="file" value="<?php echo $view['wr_subject']; ?>">
                    <input type="hidden" name="rev" value="<?php echo $sql_tp_arr['rev_no'] ?? ''; ?>">
                </form>
            </tr>
            <tr>
                <td class="main_td td_sub" style="height:80px;"> PLAN Dwg.</td>
                <td class="main_td" colspan="2"><a href='javascript:document.submit_forplan.submit()'>
                        <b> <?php echo $sql_tp_arr['plan_no']; ?>  </b></a></td>
                <form name='submit_forplan' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post"
                      target="<?php echo $or_sub . 'plan'; ?>" onSubmit="return doSumbit()">
                    <input type="hidden" name="folder" value="plan">
                    <input type="hidden" name="file" value="<?php echo $sql_tp_arr['plan_no']; ?>">
                    <input type="hidden" name="rev" value="<?php echo $sql_tp_plan_arr['rev_no']; ?>">
                </form>

                <td class="main_td td_sub" style="height:80px;"> P&ID Dwg.</td>
                <td class="main_td" colspan="2"><a href='javascript:document.submit_forpnid.submit()'>
                        <b> <?php echo $sql_tp_arr['pnid_no']; ?>  </b></a></td>
                <form name='submit_forpnid' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post"
                      target="<?php echo $or_sub . 'pnid'; ?>" onSubmit="return doSumbit()">
                    <input type="hidden" name="folder" value="pnid">
                    <input type="hidden" name="file" value="<?php echo $sql_tp_arr['pnid_no']; ?>">
                    <input type="hidden" name="rev" value="<?php echo $sql_tp_pnid_arr['rev_no']; ?>">
                </form>

            </tr>
        </table>
        <table class="main">
            <tr>
                <td class="main_td td_sub" colspan=2 style="height:80px;"> Tie-in 3D Model</td>
                <td class="main_td td_sub" colspan=2 style="height:80px;"> Tie-in Tag Photo</td>
                <td class="main_td td_sub" colspan=2 style="height:80px;"> Tie-in Work Photo</td>
            </tr>
            <tr>
                <td class="main_td " colspan="2" style="width:33%;height:200px;">
                    <?php
                    photo_thumb('tp', $sql_tp_info_arr['tp_photo1'], 'photo1', 180);
                    if (!$sql_tp_info_arr['tp_photo1_by'] && $member['mb_2'] > 1) {
                        photo_up('tp', $view['wr_subject'], 'photo1', $sql_tp_info_arr['tp_photo1']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_tp_info_arr['tp_photo1_by'] && G5_TIME_YMD == substr($sql_tp_info_arr['tp_photo1_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('tp', $view['wr_subject'], 'photo1', $sql_tp_info_arr['tp_photo1']);
                    }
                    ?>
                </td>
                <td class="main_td " colspan="2" style="width:33%;height:200px;">
                    <?php
                    photo_thumb('tp', $sql_tp_info_arr['tp_photo2'], 'photo2', 180, 'thumb_');
                    if (!$sql_tp_info_arr['tp_photo2_by'] && $member['mb_2'] > 1) {
                        photo_up('tp', $view['wr_subject'], 'photo2', $sql_tp_info_arr['tp_photo2']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_tp_info_arr['tp_photo2_by'] && G5_TIME_YMD == substr($sql_tp_info_arr['tp_photo2_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('tp', $view['wr_subject'], 'photo2', $sql_tp_info_arr['tp_photo2']);
                    }
                    ?>
                </td>
                <td class="main_td " colspan="2" style="width:33%;height:200px;">
                    <?php
                    photo_thumb('tp', $sql_tp_info_arr['tp_photo3'], 'photo3', 180, 'thumb_');
                    if (!$sql_tp_info_arr['tp_photo3_by'] && $member['mb_2'] > 1) {
                        photo_up('tp', $view['wr_subject'], 'photo3', $sql_tp_info_arr['tp_photo3']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_tp_info_arr['tp_photo3_by'] && G5_TIME_YMD == substr($sql_tp_info_arr['tp_photo3_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('tp', $view['wr_subject'], 'photo3', $sql_tp_info_arr['tp_photo3']);
                    }
                    ?>
                </td>
            </tr>
        </table>
        <table class="main">
            <tr>
                <?php
                echo '<td class="main_td" colspan=3 style="background-color: #F6D8CE; height:80px;"><b>INCLUDED ISO DRAWING</td></tr>';

                $j = 0;
                $cnt_arr = count($Dwg_array) - 1;
                for ($i = 0; $i < $cnt_arr; $i++) {
                    $query_con_dwg = "SELECT wr_id, wr_1  FROM " . G5_TABLE_PREFIX . "write_drawing WHERE wr_subject = '" . $Dwg_array[$i] . "'";
                    $sql_con_dwg = sql_query($query_con_dwg);
                    $sql_con_dwg_arr = sql_fetch_array($sql_con_dwg);

                    $query_con_dwg_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_drawing WHERE dwg_no = '" . $Dwg_array[$i] . "'";
                    $sql_con_dwg_info = sql_query($query_con_dwg_info);
                    $sql_con_dwg_info_arr = sql_fetch_array($sql_con_dwg_info);

                    ?>

                    <td class="jnt_td" style='width:33%;height:150px;font-size:20px;'>
                        <?php
                        if ($Dwg_array[$i] !== '' && $Dwg_array[$i] !== '0') {
                            $j++;
                            $con_no = $i + 1;
                            if (isset($sql_con_dwg_arr['wr_id']) && $sql_con_dwg_arr['wr_id']) {
                                echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=drawing&wr_id=' . $sql_con_dwg_arr['wr_id'] . '> <b>' . $con_no . '. ' . $Dwg_array[$i] . '</b></a></br>';
                                echo $sql_con_dwg_info_arr['line_size'] . ' - ' . $sql_con_dwg_info_arr['test_type'] . ' - ' . $sql_con_dwg_info_arr['pressure'] . '</br>';
                                echo "<a href = 'javascript:document.submit_for" . $i . $j . ".submit()'> <b> View ISO Drawing </b> </a>";
                            } else {
                                echo '<mark>' . $con_no . '. ' . $Dwg_array[$i] . '</mark>';
                            }

                            $rev_no = isset($sql_con_dwg_info_arr['rev_no']) && $sql_con_dwg_info_arr['rev_no'] !== null ? (string)$sql_con_dwg_info_arr['rev_no'] : '';
                            viewPDF('submit_for' . $i . $j, 'fab', $Dwg_array[$i], $rev_no);
                        }
                        ?>
                    </td>
                    <?php
                    if ($j % 3 == 0) {
                        echo '</tr><tr>';
                    }

                }
                if ($j % 3 !== 0) {
                    for ($k = 0; $k < 3 - ($j % 3); $k++) { ?>
                        <td class="jnt_td"></td>
                        <?php
                    }
                }

                ?>
            </tr>

            </tbody>
        </table>
        <?php
    } else {  /////////////////////////////////////////////////////////////////////////////////  Mobile 버전 시작

        ?>

        <table class="main">
            <caption> SPECIFICATION</caption>
            <tbody>

            <tr>
                <td class="main_td td_sub" style="height:60px;" colspan="4"><b><?php echo $view['wr_subject']; ?></b>
                </td>
                <form name='submit_for' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post"
                      target="<?php echo $view['wr_subject']; ?>" onSubmit="return doSumbit()">
                    <input type="hidden" name="folder" value="tp">
                    <input type="hidden" name="file" value="<?php echo $view['wr_subject']; ?>">
                    <input type="hidden" name="rev" value="<?php echo $sql_tp_arr['rev_no'] ?? ''; ?>">
                </form>
            </tr>
            <tr>
                <td class="main_td td_sub" style="width:25%;height:60px;"> PLAN</td>
                <td class="main_td" colspan="3"><a href='javascript:document.submit_forplan.submit()'>
                        <b> <?php echo $sql_tp_arr['plan_no']; ?>  </b></a></td>
                <form name='submit_forplan' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post"
                      target="<?php echo $or_sub . 'plan'; ?>" onSubmit="return doSumbit()">
                    <input type="hidden" name="folder" value="plan">
                    <input type="hidden" name="file" value="<?php echo $sql_tp_arr['plan_no']; ?>">
                    <input type="hidden" name="rev" value="<?php echo $sql_tp_plan_arr['rev_no']; ?>">
                </form>
            </tr>
            <tr>
                <td class="main_td td_sub" style="width:25%;height:60px;"> P&ID</td>
                <td class="main_td" colspan="3"><a href='javascript:document.submit_forpnid.submit()'>
                        <b> <?php echo $sql_tp_arr['pnid_no']; ?>  </b></a></td>
                <form name='submit_forpnid' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post"
                      target="<?php echo $or_sub . 'pnid'; ?>" onSubmit="return doSumbit()">
                    <input type="hidden" name="folder" value="pnid">
                    <input type="hidden" name="file" value="<?php echo $sql_tp_arr['pnid_no']; ?>">
                    <input type="hidden" name="rev" value="<?php echo $sql_tp_pnid_arr['rev_no']; ?>">
                </form>

            </tr>
            <tr>
                <td class="main_td " colspan="4" style="height:300px;">
                    <?php
                    photo_thumb('tp', $sql_tp_info_arr['tp_photo1'], 'photo1', 300);
                    if (!$sql_tp_info_arr['tp_photo1_by'] && $member['mb_2'] > 1) {
                        photo_up('tp', $view['wr_subject'], 'photo1', $sql_tp_info_arr['tp_photo1']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_tp_info_arr['tp_photo1_by'] && G5_TIME_YMD == substr($sql_tp_info_arr['tp_photo1_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('tp', $view['wr_subject'], 'photo1', $sql_tp_info_arr['tp_photo1']);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="main_td td_sub" colspan="2" style="height:50px;"> Photo 1</td>
                <td class="main_td td_sub" colspan="2" style="height:50px;"> Photo 2</td>
            </tr>
            <tr>
                <td class="main_td " colspan="2" style="width:50%;height:150px;font-size:20px;">
                    <?php
                    photo_thumb('tp', $sql_tp_info_arr['tp_photo2'], 'photo2', 100, 'thumb_');
                    if (!$sql_tp_info_arr['tp_photo2_by'] && $member['mb_2'] > 1) {
                        photo_up('tp', $view['wr_subject'], 'photo2', $sql_tp_info_arr['tp_photo2']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_tp_info_arr['tp_photo2_by'] && G5_TIME_YMD == substr($sql_tp_info_arr['tp_photo2_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('tp', $view['wr_subject'], 'photo2', $sql_tp_info_arr['tp_photo2']);
                    }
                    ?>
                </td>
                <td class="main_td " colspan="2" style="width:50%;height:150px;font-size:20px;">
                    <?php
                    photo_thumb('tp', $sql_tp_info_arr['tp_photo3'], 'photo3', 100, 'thumb_');
                    if (!$sql_tp_info_arr['tp_photo3_by'] && $member['mb_2'] > 1) {
                        photo_up('tp', $view['wr_subject'], 'photo3', $sql_tp_info_arr['tp_photo3']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_tp_info_arr['tp_photo3_by'] && G5_TIME_YMD == substr($sql_tp_info_arr['tp_photo3_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('tp', $view['wr_subject'], 'photo3', $sql_tp_info_arr['tp_photo3']);
                    }
                    ?>
                </td>
            </tr>
        </table>

        <table class="main">
            <tr>
                <?php
                echo '<td class="main_td" style="background-color: #F6D8CE; height:50px;"><b>INCLUDED ISO</b></td></tr>';

                $j = 0;
                $cnt_arr = count($Dwg_array) - 1;
                for ($i = 0; $i < $cnt_arr; $i++) {
                    $query_con_dwg = "SELECT wr_id, wr_1  FROM " . G5_TABLE_PREFIX . "write_drawing WHERE wr_subject = '" . $Dwg_array[$i] . "'";
                    $sql_con_dwg = sql_query($query_con_dwg);
                    $sql_con_dwg_arr = sql_fetch_array($sql_con_dwg);

                    $query_con_dwg_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_drawing WHERE dwg_no = '" . $Dwg_array[$i] . "'";
                    $sql_con_dwg_info = sql_query($query_con_dwg_info);
                    $sql_con_dwg_info_arr = sql_fetch_array($sql_con_dwg_info);

                    ?>

                    <td class="jnt_td" style='width:33%;height:150px;font-size:20px;'>
                        <?php
                        if ($Dwg_array[$i] !== '' && $Dwg_array[$i] !== '0') {
                            $j++;
                            $con_no = $i + 1;
                            if (isset($sql_con_dwg_arr['wr_id']) && $sql_con_dwg_arr['wr_id']) {
                                echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=drawing&wr_id=' . $sql_con_dwg_arr['wr_id'] . '> <b>' . $con_no . '. ' . $Dwg_array[$i] . '</b></a></br>';
                                echo $sql_con_dwg_info_arr['line_size'] . ' - ' . $sql_con_dwg_info_arr['test_type'] . ' - ' . $sql_con_dwg_info_arr['pressure'] . '</br>';
                                echo "<a href = 'javascript:document.submit_for" . $i . $j . ".submit()'> <b> View ISO Drawing </b> </a>";
                            } else {
                                echo '<mark>' . $con_no . '. ' . $Dwg_array[$i] . '</mark>';
                            }

                            $rev_no = isset($sql_con_dwg_info_arr['rev_no']) && $sql_con_dwg_info_arr['rev_no'] !== null ? (string)$sql_con_dwg_info_arr['rev_no'] : '';
                            viewPDF('submit_for' . $i . $j, 'fab', $Dwg_array[$i], $rev_no);
                        }
                        ?>
                    </td>
                    <?php
                    echo '</tr><tr>';

                }

                ?>
            </tr>

            </tbody>
        </table>
        <?php
    }
}
?>