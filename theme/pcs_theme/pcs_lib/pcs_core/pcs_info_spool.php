<?php

if ((isset($_POST['folder']) && $_POST['folder']) || (isset($_POST['ph']) && $_POST['ph'])) {
    include_once(PCS_LIB . '/pcs_photo.php');
} else {
    spl_ins_qry($_POST['field_id'] ?? null, $_POST['btn_stat'] ?? null);

    $query_spl_stat_set = 'INSERT INTO ' . G5_TABLE_PREFIX . 'pcs_info_spl_stat (spool_no) VALUES ("' . $view['wr_subject'] . '")';
    sql_query($query_spl_stat_set);

    $temptbl1 = GenerateString(15);
    $temptbl2 = GenerateString(15);

    $query_view_spool =
        'CREATE VIEW ' . $temptbl1 . ' AS SELECT * 
		FROM ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc
		WHERE spool_no = "' . $view['wr_subject'] . '" AND w_type = "WELD" ORDER BY dwg_no, j_no';
    $sql_view_spool = sql_query($query_view_spool);

    if (isset($_POST['spl_location'])) {
        $query_spl_loc = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_spl_stat SET location = "' . $_POST['spl_location'] . '" WHERE spool_no = "' . $view['wr_subject'] . '"';
        sql_query($query_spl_loc);
    }

    $query_weld_total = 'SELECT COUNT(j_size) FROM ' . $temptbl1;
    $weld_total = pcs_sql_value($query_weld_total);
    $query_weld_done = 'SELECT COUNT(j_size) FROM ' . $temptbl1 . ' WHERE pcs_vi_rlt = "Accept"';
    $weld_done = pcs_sql_value($query_weld_done);
    $query_pwht_total = 'SELECT COUNT(j_size) FROM ' . $temptbl1 . ' WHERE pwht_yn = "YES"';
    $pwht_total = pcs_sql_value($query_pwht_total);
    $query_pwht_done = 'SELECT COUNT(j_size) FROM ' . $temptbl1 . ' WHERE pcs_pwht_rlt = "Accept"';
    $pwht_done = pcs_sql_value($query_pwht_done);
    $query_pmi_total = 'SELECT COUNT(j_size) FROM ' . $temptbl1 . ' WHERE pmi_yn = "YES"';
    $pmi_total = pcs_sql_value($query_pmi_total);
    $query_pmi_done = 'SELECT COUNT(j_size) FROM ' . $temptbl1 . ' WHERE pcs_pmi_rlt = "Accept"';
    $pmi_done = pcs_sql_value($query_pmi_done);

    $query_spl_stat = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_spl_stat SET st_weld = "' . $weld_done . ' / ' . $weld_total . '", state = ';
    if ($weld_total && $weld_done == $weld_total) {
        $query_spl_stat .= '"Finished"';
    } elseif ($weld_done) {
        $query_spl_stat .= '"On_going"';
    } else {
        $query_spl_stat .= '"Not_started"';
    }
    $query_spl_stat .= ', st_pwht = ';

    if ($pwht_total) {
        $query_spl_stat .= '"' . $pwht_done . ' / ' . $pwht_total . '"';
    } else {
        $query_spl_stat .= '"N/A"';
    }
    $query_spl_stat .= ', st_pmi = ';

    if ($pmi_total) {
        $query_spl_stat .= '"' . $pmi_done . ' / ' . $pmi_total . '"';
    } else {
        $query_spl_stat .= '"N/A"';
    }
    $query_spl_stat .= ' WHERE spool_no = "' . $view['wr_subject'] . '"';
    sql_query($query_spl_stat, true);

    if (isset($_POST['field_id']) && isset($_POST['btn_stat'])) {
        spl_ins_qry($_POST['field_id'] ?? null, $_POST['btn_stat'] ?? null);
    }

//	$query_spl_info = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_spool WHERE spool_no = "'.$view['wr_subject'].'"';
    $query_spl_info = 'SELECT A.*, B.* FROM ' . G5_TABLE_PREFIX . 'pcs_info_spool AS A LEFT JOIN ' . G5_TABLE_PREFIX . 'pcs_info_spl_stat AS B ON A.spool_no = B.spool_no
						WHERE A.spool_no = "' . $view['wr_subject'] . '"';
    $sql_spl_info = sql_query($query_spl_info);
    $sql_spl_info_arr = sql_fetch_array($sql_spl_info);


    $query_dwg_info = 'SELECT DISTINCT dwg_no FROM ' . $temptbl1;
    $sql_dwg_info = sql_query($query_dwg_info);

    $field_query = 'DESCRIBE ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc';
    $field_name_sbc = field_name_array($field_query);

    $field_query_con = 'DESCRIBE ' . G5_TABLE_PREFIX . 'pcs_info_joint';
    $field_name_con = field_name_array($field_query_con);

    $query_sbc = 'SELECT * FROM ' . $temptbl1;
    $sql_spl_jnt = sql_query($query_sbc);


    $query_view_dwg_create =
        'CREATE VIEW ' . $temptbl2 . ' AS SELECT *
		FROM ' . G5_TABLE_PREFIX . 'pcs_info_joint
		WHERE dwg_no = "' . $view['wr_subject'] . '" ORDER BY j_no';
    sql_query($query_view_dwg_create);

    $query_field = 'DESCRIBE ' . G5_TABLE_PREFIX . 'pcs_info_spl_stat';
    $field_enum_value = enum_value($query_field);


    if (!G5_IS_MOBILE) { /////////// PC 버전 시작
        ?>

        <table class="main">
            <caption> SPECIFICATION</caption>
            <tbody>
            <tr>
                <?php
                for ($j = 1; $j < 4; $j++) {
                    if ($sql_spl_info_arr['dwg' . $j]) {
                        $query_dwg_wr = "SELECT wr_id FROM " . G5_TABLE_PREFIX . "write_iso WHERE wr_subject = '" . $sql_spl_info_arr['dwg' . $j] . "'";
                        $sql_dwg_wr = sql_query($query_dwg_wr);
                        $sql_dwg_wr_arr = sql_fetch_array($sql_dwg_wr);

                        $query_ref_dwg_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_iso WHERE dwg_no = '" . $sql_spl_info_arr['dwg' . $j] . "'";
                        $sql_ref_dwg_info = sql_query($query_ref_dwg_info);
                        $sql_ref_dwg_info_arr = sql_fetch_array($sql_ref_dwg_info);

                        echo '<td class="main_td" style="width:70px; height:80px; background-color:orange; font-size:15px;">';
                        echo '<a href = "javascript:document.dwg' . $j . '.submit()"><b> Ref. Dwg ' . $j . ' </b></a>';
                        viewPDF('dwg' . $j, 'fab', $sql_ref_dwg_info_arr['dwg_no'], $sql_ref_dwg_info_arr['rev_no']);
                        echo '</td>';
                        echo '<td class="main_td"><a href=' . G5_URL . '/bbs/board.php?bo_table=iso&wr_id=' . $sql_dwg_wr_arr['wr_id'] . ' target="_self">' . $sql_spl_info_arr['dwg' . $j] . '</a></td>';

                    } else {
                        echo '<td class="main_td" style="width:70px; font-size:15px;"></td><td class="main_td"></td>';
                    }
                }
                ?>
            </tr>
            </tbody>
        </table>

        <table class="main">
            <tbody>
            <tr>
                <td class="main_td td_sub" style="height:80px;"> LEVEL / UNIT</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['ag_ug'] . ' / ' . $sql_ref_dwg_info_arr['unit']; ?>  </td>
                <td class="main_td td_sub"> NPS</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['line_size']; ?>  </td>
                <td class="main_td td_sub"> MATERIAL</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['material']; ?>  </td>
            </tr>

            <tr>
                <td class="main_td td_sub"> PMI / PWHT</td>
                <td class="main_td"
                    style="height:80px;"> <?php echo $sql_ref_dwg_info_arr['pmi'] . ' / ' . $sql_ref_dwg_info_arr['pwht']; ?>  </td>
                <td class="main_td td_sub"> NDE</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['nde_rate'] . ' %'; ?>  </td>
                <td class="main_td td_sub"> PAINT CODE</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['paint_code']; ?>  </td>
            </tr>


            <tr>
                <td class="main_td" style='background-color: gold; height:200px;'>
                    <a onclick='window.open("<?php echo PCS_CORE_URL . '/pcs_googlemap.php?spl=' . $view['wr_subject'] . '&lat=' . $sql_spl_info_arr['gps_lat'] . '&lon=' . $sql_spl_info_arr['gps_lon']; ?>","w","width=1000, height=900, left=200, top=100");'>LOCATION<br><span
                                style="font-weight: bold; font-size: 20px;">(GOOGLE MAP)</span> </a></td>
                <td class="main_td">
                    <?php
                    if ($member['mb_2'] > 1 && $sql_spl_info_arr['state'] == 'Finished' && !(isset($_POST['spl_location']) && $_POST['spl_location'])) {
                        echo '<form name="spool_location" method="post" onSubmit="return doSumbit()"><select name="spl_location" style="height:50px; width:100%; font-size:30px; padding:0 20px; border:none;">';
                        sel_option_enum($field_enum_value['location'], $sql_spl_info_arr['location']);
                        echo '</select>';
                    } else {
                        echo $sql_spl_info_arr['location'];
                    }
                    ?>
                </td>
                <td class="main_td" style='background-color: gold; height:80px;'>FABRICATION<br>STATE</td>
                <td class="main_td">
                    <?php
                    if ($member['mb_2'] > 1 && $sql_spl_info_arr['state'] == 'Finished' && !(isset($_POST['spl_location']) && $_POST['spl_location'])) {
                        echo '
		<a href = "javascript:spool_location.submit()" ><font color = blue><b>Finished</b></font></a>
		</form>';
                    } else {
                        echo str_replace('_', ' ', $sql_spl_info_arr['state']);
                    }
                    ?>
                </td>
                <td class="main_td" style='background-color: gold; height:80px;'> SPOOL PHOTO</td>
                <td class="main_td">
                    <?php
                    photo_thumb('spool', $sql_spl_info_arr['photo'], '', 180, $sql_spl_info_arr['dwg1']);
                    if (!$sql_spl_info_arr['photo_by'] && $member['mb_2'] > 1) {
                        photo_up('spool', $sql_spl_info_arr['spool_no'], $sql_spl_info_arr['location'], $sql_spl_info_arr['photo'], $sql_spl_info_arr['dwg1']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_spl_info_arr['photo_by'] && G5_TIME_YMD == substr($sql_spl_info_arr['photo_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('spool', $sql_spl_info_arr['spool_no'], $sql_spl_info_arr['location'], $sql_spl_info_arr['photo'], $sql_spl_info_arr['dwg1']);
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>

        <p>&nbsp; <p>&nbsp; <p>&nbsp;


        <table class="main">
            <caption> SPOOL JOINT STATUS</caption>
            <tbody>
            <tr>
                <td class="jnt_td jnt_th" style="width:300px"> Drawing no.</td>
                <td class="jnt_td jnt_th" style="width:100px"> J.No</td>
                <td class="jnt_td jnt_th" style="width:100px"> Type</td>
                <td class="jnt_td jnt_th" style="width:100px"> S / F</td>
                <td class="jnt_td jnt_th" style="width:100px"> NPS</td>
                <td class="jnt_td jnt_th" style="width:200px"> Photo 1</td>
                <td class="jnt_td jnt_th" style="width:200px"> Photo 2</td>
                <td class="jnt_td jnt_th" style="width:200px"> Fit-up</td>
                <td class="jnt_td jnt_th" style="width:200px"> Welding</td>
                <td class="jnt_td jnt_th" style="width:200px"> PMI</td>
                <td class="jnt_td jnt_th" style="width:200px"> PWHT</td>
                <td class="jnt_td jnt_th" style="width:200px"> NDE</td>
            </tr>

            <?php

            $idx = 0;

            while ($sql_ref_sbc_arr = sql_fetch_array($sql_spl_jnt))    {
            if ($sql_ref_sbc_arr['j_stat'] != 'REM' && $sql_ref_sbc_arr['j_type'] != 'SPL'){
            $idx++;

            $query_con = 'SELECT * FROM ' . $temptbl2 . ' WHERE j_key = "' . $sql_ref_sbc_arr['j_key'] . '"';
            $sql_ref_con = sql_query($query_con, true);
            $sql_ref_con_arr = sql_fetch_array($sql_ref_con);
            $counter = count($field_name_sbc);

            for ($i = 0; $i < $counter; $i++) {
                if (substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 4, 1) === '-' && substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 7, 1) === '-') {
                    if ($sql_ref_sbc_arr[$field_name_sbc[$i]] == '0000-00-00 00:00:00') {
                        $sql_ref_sbc_arr[$field_name_sbc[$i]] = false;
                    } else {
                        $sql_ref_sbc_arr[$field_name_sbc[$i]] = substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 0, 10);
                    }
                }
            }
            $counter = count($field_name_con);

            for ($i = 0; $i < $counter; $i++) {
                $key = $field_name_con[$i];
                $val = $sql_ref_con_arr[$key] ?? null;
                if (
                    is_string($val) &&
                    substr($val, 4, 1) === '-' &&
                    substr($val, 7, 1) === '-' &&
                    $val === '0000-00-00'
                ) {
                    $sql_ref_con_arr[$key] = false;
                }
            }
            $PCS_DEL = $sql_ref_sbc_arr['j_stat'] == 'DEL' ? "<del><font color = red>" : '';
            //	echo $query_sbc;
            ?>

            <tr id='tr<?php echo $idx; ?>'>
                <td class="jnt_td" rowspan="2"><?php echo $PCS_DEL;
                    echo $sql_ref_sbc_arr['dwg_no']; ?></td>
                <td class="jnt_td" rowspan="2">
                    <?php
                    echo $PCS_DEL;
                    echo z_rem_jno($sql_ref_sbc_arr['j_no']);
                    ?>
                </td>
                <td class="td_upper">    <?php echo $PCS_DEL;
                    echo $sql_ref_sbc_arr['j_type']; ?></td>
                <td class="td_upper">    <?php echo $PCS_DEL;
                    echo $sql_ref_sbc_arr['s_f']; ?></td>
                <td class="td_upper">    <?php echo $PCS_DEL;
                    echo $sql_ref_sbc_arr['j_size']; ?></td>

                <td class="td_upper" rowspan="2">
                    <?php
                    echo $PCS_DEL;
                    photo_thumb('photo_1', $sql_ref_sbc_arr['photo_1'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
                    if (!$sql_ref_sbc_arr['photo_1'] && $member['mb_2'] > 1) {
                        photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
                    } elseif ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_1_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_1_tm']) || $member['mb_2'] == 3)) {
                        photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
                    }
                    $heat_1 = $sql_ref_con_arr['heat_1'] ?? '';
                    echo '<br>' . $sql_ref_sbc_arr['item_1_type'];
                    rep_view('heat_1' . $idx, 'report/heat', '', (string)$heat_1);
                    ?>

                </td>

                <td class="td_upper" rowspan="2">
                    <?php
                    echo $PCS_DEL;
                    photo_thumb('photo_2', $sql_ref_sbc_arr['photo_2'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
                    if (!$sql_ref_sbc_arr['photo_2'] && $member['mb_2'] > 1) {
                        photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
                    } elseif ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_2_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_2_tm']) || $member['mb_2'] == 3)) {
                        photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
                    }
                    echo '<br>' . $sql_ref_sbc_arr['item_2_type'];
                    rep_view('heat_2' . $idx, 'report/heat', '', $sql_ref_con_arr['heat_2'] ?? '');
                    ?>
                </td>

                <td class="td_upper">
                    <?php
                    echo $PCS_DEL;
                    if ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                        echo $sql_ref_sbc_arr['pcs_fitup_req_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
                    } else {
                        insp_fitup($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_fitup_req_date'], $sql_ref_sbc_arr['pcs_fitup_rlt'], $sql_ref_sbc_arr['pcs_fitup_rlt_date'], $sql_ref_sbc_arr['pcs_vi_req_date']);
                    }
                    ?>
                </td>

                <td class="td_upper">
                    <?php
                    echo $PCS_DEL;

                    if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
                        echo 'N/A';
                    } elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                        echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
                    } elseif ($sql_ref_sbc_arr['pcs_fitup_rlt'] == 'Accept') {
                        insp_vi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_vi_req_date'], $sql_ref_sbc_arr['pcs_vi_rlt'], $sql_ref_sbc_arr['pcs_vi_rlt_date'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
                    } else {
                        echo 'Not yet<br>Fit-up';
                    }
                    ?>
                </td>

                <td class="td_upper">
                    <?php
                    echo $PCS_DEL;

                    if ($sql_ref_sbc_arr['pmi_yn'] == 'YES') {
                        if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
                            echo 'Not yet<br>VI accepted';
                        } elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                            echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
                        } else {
                            insp_pmi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_pmi_rlt'], $sql_ref_sbc_arr['pcs_pmi_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
                        }
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>

                <td class="td_upper">

                    <?php
                    echo $PCS_DEL;

                    if ($sql_ref_sbc_arr['pwht_yn'] == 'YES') {
                        if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
                            echo 'Not yet<br>VI accepted';
                        } elseif ($sql_ref_sbc_arr['j_stat'] != 'DEL') {
                            insp_pwht($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pwht_rlt'], $sql_ref_sbc_arr['pcs_pwht_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
                        }
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </td>

                <td class="td_upper">
                    <?php
                    echo $PCS_DEL;
                    if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
                        echo 'N/A';
                    } elseif ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
                        echo 'Not yet<br>VI accepted';
                    } elseif ($sql_ref_sbc_arr['pwht_yn'] == 'YES' && $sql_ref_sbc_arr['pcs_pwht_rlt'] != 'Accept') {
                        echo 'Not yet<br>PWHT accepted';
                    } elseif ($sql_ref_sbc_arr['j_stat'] != 'DEL') {
                        insp_nde($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_6'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_nde_type'], $sql_ref_sbc_arr['pcs_nde_req_date'], $sql_ref_sbc_arr['pcs_nde_rlt']);
                    }
                    ?>
                </td>

            </tr>

            <tr>
                <td class="td_lower"><?php echo $sql_ref_con_arr['j_type'] ?? ''; ?></td>
                <td class="td_lower"><?php echo $sql_ref_con_arr['s_f'] ?? ''; ?></td>
                <td class="td_lower"><?php echo $sql_ref_con_arr['nps'] ?? ''; ?></td>
                <td class="td_lower"><?php echo $sql_ref_con_arr['ft_date'] ?? ''; ?></td>
                <td class="td_lower"><?php rep_view('vi' . $idx, 'report/vi', $sql_ref_con_arr['vi_date'] ?? '', isset($sql_ref_con_arr['vi_rep']) ? substr($sql_ref_con_arr['vi_rep'], -11) : ''); ?></td>
                <td class="td_lower"><?php rep_view('pmi' . $idx, 'report/pmi', $sql_ref_con_arr['pmi_date'] ?? '', $sql_ref_con_arr['pmi_rep'] ?? ''); ?></td>
                <td class="td_lower"><?php rep_view('pwht' . $idx, 'report/pwht', $sql_ref_con_arr['pwht_date'] ?? '', $sql_ref_con_arr['pwht_rep'] ?? ''); ?></td>
                <td class="td_lower"><?php rep_view('nde' . $idx, 'report/nde', $sql_ref_con_arr['nde_date'] ?? '', $sql_ref_con_arr['nde_rep'] ?? ''); ?></td>
                <?php
                }
                }
                ?>

            </tbody>
        </table>

        <?php
    }    /////////////  PC 버전 끝


    else {  ///////////////////////////////////////////////////////////////////////////  Mobile 버전 시작
        ?>


        <table class="main">
            <caption>SPECIFICATION</caption>
            <tr>
                <?php
                for ($j = 1; $j < 4; $j++) {
                    if ($sql_spl_info_arr['dwg' . $j]) {
                        $query_ref_dwg_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_iso WHERE dwg_no = '" . $sql_spl_info_arr['dwg' . $j] . "'";
                        $sql_ref_dwg_info = sql_query($query_ref_dwg_info);
                        $sql_ref_dwg_info_arr = sql_fetch_array($sql_ref_dwg_info);

                        echo '<td class="main_td" style="width:70px; height:80px; background-color:orange; font-size:15px;">';
                        echo '<a href = "javascript:document.dwg' . $j . '.submit()"><b> Ref. Dwg ' . $j . ' </b></a>';
                        viewPDF('dwg' . $j, 'fab', $sql_ref_dwg_info_arr['dwg_no'], $sql_ref_dwg_info_arr['rev_no']);
                        echo '</td>';
                    }
                }
                ?>
            </tr>
        </table>


        <table class="main">
            <tbody>
            <tr>
                <td class="main_td td_sub" style='background-color: gold;'
                    colspan="2"> <?php echo $view['wr_subject']; ?></td>
            </tr>
            <tr>
                <td class="main_td td_sub"> MATERIAL</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['material']; ?> </td>
            </tr>
            <tr>
                <td class="main_td td_sub"> PMI / PWHT</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['pmi'] . ' / ' . $sql_ref_dwg_info_arr['pwht']; ?> </td>
            </tr>
            <tr>
                <td class="main_td td_sub"> NDE / PAINT</td>
                <td class="main_td"> <?php echo $sql_ref_dwg_info_arr['nde_rate'] . ' % / ' . $sql_ref_dwg_info_arr['paint_code']; ?> </td>
            </tr>
            <tr>
                <td class="main_td td_sub"> FABRICATION</td>
                <td class="main_td">
                    <?php
                    if ($member['mb_2'] > 1 && $sql_spl_info_arr['state'] == 'Finished' && !(isset($_POST['spl_location']) && $_POST['spl_location'])) {
                        echo '<form name="spool_location" method="post" onSubmit="return doSumbit()">
		<a href = "javascript:spool_location.submit()" ><font color = blue><b>Finished</b></font></a>
		';
                    } else {
                        echo str_replace('_', ' ', $sql_spl_info_arr['state']);
                    }
                    ?>
            </tr>
            <tr>
                <td class="main_td td_sub"><a
                            href=<?php echo PCS_CORE_URL . '/pcs_googlemap.php?lon=' . $sql_spl_info_arr['gps_lon'] . '&lat=' . $sql_spl_info_arr['gps_lat']; ?> target='_blank'>
                        <b>LOCATION</b></a></td>
                <td class="main_td">
                    <?php
                    if ($member['mb_2'] > 1 && $sql_spl_info_arr['state'] == 'Finished' && !(isset($_POST['spl_location']) && $_POST['spl_location'])) {
                        echo '<select name="spl_location" style="height:50px; width:100%; font-size:25px; padding:0 20px; border:none;">';
                        sel_option_enum($field_enum_value['location'], $sql_spl_info_arr['location']);
                        echo '</select></form>';
                    } else {
                        echo $sql_spl_info_arr['location'];
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="main_td" colspan="2">
                    <?php
                    photo_thumb('spool', $sql_spl_info_arr['photo'], '', 180, $sql_spl_info_arr['dwg1']);
                    if (!$sql_spl_info_arr['photo_by'] && $member['mb_2'] > 1) {
                        photo_up('spool', $sql_spl_info_arr['spool_no'], $sql_spl_info_arr['location'], $sql_spl_info_arr['photo'], $sql_spl_info_arr['dwg1']);
                    } elseif (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_spl_info_arr['photo_by'] && G5_TIME_YMD == substr($sql_spl_info_arr['photo_tm'], 0, 10)) || $member['mb_2'] == 3) {
                        photo_up('spool', $sql_spl_info_arr['spool_no'], $sql_spl_info_arr['location'], $sql_spl_info_arr['photo'], $sql_spl_info_arr['dwg1']);
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>

        <p>&nbsp;<p>&nbsp;<p>&nbsp;
        <table class='main'>
            <caption> SPOOL JOINT STATUS</caption>
            <tbody>

            <?php

            $idx = 0;

            while ($sql_ref_sbc_arr = sql_fetch_array($sql_spl_jnt)) {
                if ($sql_ref_sbc_arr['j_stat'] != 'REM' && $sql_ref_sbc_arr['j_type'] != 'SPL') {
                    $idx++;
                    $counter = count($field_name_sbc);

                    for ($i = 0; $i < $counter; $i++) {
                        if (substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 4, 1) === '-' && substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 7, 1) === '-') {
                            if ($sql_ref_sbc_arr[$field_name_sbc[$i]] == '0000-00-00 00:00:00') {
                                $sql_ref_sbc_arr[$field_name_sbc[$i]] = false;
                            } else {
                                $sql_ref_sbc_arr[$field_name_sbc[$i]] = substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 0, 10);
                            }
                        }
                    }

                    $PCS_DEL = $sql_ref_sbc_arr['j_stat'] == 'DEL' ? "<del><font color = red>" : '';

                    $query_ref_rev = "SELECT rev_no FROM " . G5_TABLE_PREFIX . "pcs_info_iso WHERE dwg_no = '" . $sql_ref_sbc_arr['dwg_no'] . "'";
                    $sql_ref_rev = sql_query($query_ref_rev);
                    $sql_ref_rev_arr = sql_fetch_array($sql_ref_rev);

                    ?>


                    <tr>
                        <td class='jnt_td jnt_th' style="width: 25%;"> Joint no.<br><font
                                    size="5"><?php echo z_rem_jno($sql_ref_sbc_arr['j_no']); ?></font></td>
                        <td class='jnt_td jnt_th' style="width: 75%;" colspan="3">
                            <a href='javascript:document.smt_<?php echo $idx; ?>.submit()'><b> <?php echo $sql_ref_sbc_arr['dwg_no']; ?> </b></a>
                            <?php viewPDF('smt_' . $idx, 'fab', $sql_ref_sbc_arr['dwg_no'], $sql_ref_rev_arr['rev_no']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Photo 1</td>
                        <td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Photo 2</td>
                    </tr>
                    <tr>
                        <td class='jnt_td' style="width: 50%;" colspan="2">
                            <?php
                            echo $PCS_DEL;
                            photo_thumb('photo_1', $sql_ref_sbc_arr['photo_1'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
                            if (!$sql_ref_sbc_arr['photo_1'] && $member['mb_2'] > 1) {
                                photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
                            } elseif ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_1_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_1_tm']) || $member['mb_2'] == 3)) {
                                photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
                            }
                            echo '<br>' . $sql_ref_sbc_arr['item_1_type']
                            ?>
                        </td>
                        <td class='jnt_td' style="width: 50%;" colspan="2">
                            <?php
                            echo $PCS_DEL;
                            photo_thumb('photo_2', $sql_ref_sbc_arr['photo_2'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
                            if (!$sql_ref_sbc_arr['photo_2'] && $member['mb_2'] > 1) {
                                photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
                            } elseif ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_2_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_2_tm']) || $member['mb_2'] == 3)) {
                                photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
                            }
                            echo '<br>' . $sql_ref_sbc_arr['item_2_type']
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Fit-up</td>
                        <td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Welding</td>
                    </tr>
                    <tr id='tr<?php echo $idx; ?>'>
                        <td class='jnt_td' style="width: 50%;" colspan="2">
                            <?php
                            echo $PCS_DEL;
                            if ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                                echo $sql_ref_sbc_arr['pcs_fitup_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
                            } elseif ($member['mb_4'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_fitup_rlt_by'] && $sql_ref_sbc_arr['pcs_fitup_rlt_by'] != '') {
                                echo $sql_ref_sbc_arr['pcs_fitup_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
                            } else {
                                insp_fitup($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_fitup_req_date'], $sql_ref_sbc_arr['pcs_fitup_rlt'], $sql_ref_sbc_arr['pcs_fitup_rlt_date'], $sql_ref_sbc_arr['pcs_vi_req_date']);
                            }
                            ?>
                        </td>
                        <td class='jnt_td' style="width: 50%;" colspan="2">
                            <?php
                            echo $PCS_DEL;

                            if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
                                echo 'N/A';
                            } elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                                echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
                            } elseif ($sql_ref_sbc_arr['pcs_fitup_rlt'] == 'Accept') {
                                if ($member['mb_4'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_vi_rlt_by'] && $sql_ref_sbc_arr['pcs_vi_rlt_by'] != '') {
                                    echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
                                } else {
                                    insp_vi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_vi_req_date'], $sql_ref_sbc_arr['pcs_vi_rlt'], $sql_ref_sbc_arr['pcs_vi_rlt_date'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
                                }
                            } else {
                                echo 'Not yet<br>Fit-up';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    if ($sql_ref_sbc_arr['pmi_yn'] == 'YES' || $sql_ref_sbc_arr['pwht_yn'] == 'YES') {
                        ?>
                        <tr>
                            <td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> PMI</td>
                            <td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> PWHT</td>
                        </tr>
                        <tr>
                            <td class='jnt_td' style="width: 50%;" colspan="2">
                                <?php
                                echo $PCS_DEL;

                                if ($sql_ref_sbc_arr['pmi_yn'] == 'YES') {
                                    if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
                                        echo 'Not yet<br>VI accepted';
                                    } elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                                        echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
                                    } elseif ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_pmi_rlt_by'] && $sql_ref_sbc_arr['pcs_pmi_rlt_by'] != '') {
                                        echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
                                    } else {
                                        insp_pmi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_pmi_rlt'], $sql_ref_sbc_arr['pcs_pmi_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td class='jnt_td' style="width: 50%;" colspan="2">
                                <?php
                                echo $PCS_DEL;

                                if ($sql_ref_sbc_arr['pwht_yn'] == 'YES') {
                                    if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
                                        echo 'Not yet<br>VI accepted';
                                    } elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                                        echo $sql_ref_sbc_arr['pcs_pwht_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pwht_rlt'];
                                    } elseif ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_pwht_rlt_by'] && $sql_ref_sbc_arr['pcs_pwht_rlt_by'] != '') {
                                        echo $sql_ref_sbc_arr['pcs_pwht_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pwht_rlt'];
                                    } else {
                                        insp_pwht($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pwht_rlt'], $sql_ref_sbc_arr['pcs_pwht_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td class='jnt_td td_sub_pkg2' style="width: 50%; height: 20px" colspan="4"> NDE</td>
                    </tr>
                    <tr>
                        <td class='jnt_td' style="width: 50%;" colspan="4">
                            <?php
                            echo $PCS_DEL;
                            if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
                                echo 'N/A';
                            } elseif ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
                                echo 'Not yet<br>VI accepted';
                            } elseif ($sql_ref_sbc_arr['pwht_yn'] == 'YES' && $sql_ref_sbc_arr['pcs_pwht_rlt'] != 'Accept') {
                                echo 'Not yet<br>PWHT accepted';
                            } elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
                                echo $sql_ref_sbc_arr['pcs_nde_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_nde_rlt'];
                            } elseif ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_nde_rlt_by'] && $sql_ref_sbc_arr['pcs_nde_rlt_by'] != '') {
                                echo $sql_ref_sbc_arr['pcs_nde_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_nde_rlt'];
                            } else {
                                insp_nde($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_6'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_nde_type'], $sql_ref_sbc_arr['pcs_nde_req_date'], $sql_ref_sbc_arr['pcs_nde_rlt']);
                            }

                            ?>
                        </td>
                    </tr>


                    <?php

                }
            }
            ?>
            </tbody>
        </table>

    <?php } /////////   Mobile 버전 끝
    $query_view_dwg_drop = 'DROP VIEW IF EXISTS ' . $temptbl2;
    sql_query($query_view_dwg_drop);

    $query_view_spool_drop = 'DROP VIEW IF EXISTS ' . $temptbl1;
    sql_query($query_view_spool_drop);
}
?>
<p>&nbsp;
    <script language="javascript">
        $('html, body').stop().animate({scrollTop: $("#tr<?php echo $_POST['html_loc'];?>").offset().top - screen.height / 2}, 300);
    </script>