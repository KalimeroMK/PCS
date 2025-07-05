<?php
$query_view_punch_create =
    'CREATE VIEW punchInPKG AS SELECT *
		FROM ' . G5_TABLE_PREFIX . 'pcs_info_punch
		WHERE pkg_no = "' . $view['wr_subject'] . '" ORDER BY s_no';
sql_query($query_view_punch_create);

$query_pkg_Pchk = 'SELECT * FROM punchInPKG';
$sql_pkg_Pchk = sql_query($query_pkg_Pchk);
?>
<table class="main">
    <caption>PUNCH LIST</caption>
    <tbody>
    <tr>
        <td class="jnt_td td_sub_pkg1" style="width:8%" rowspan="3"> no.</td>
        <td class="jnt_td td_sub_pkg1" style="height:45px; width:69%" colspan="3"> Dwg no.</td>
        <td class="jnt_td td_sub_pkg1" style="height:45px; width:23%" colspan="1"> Cate<br>gory</td>
    </tr>
    <tr>
        <td class="jnt_td td_sub_pkg1" style="height:45px;" colspan="4"> Punch Description</td>
    </tr>
    <tr>
        <td class="jnt_td td_sub_pkg1" style="height:45px; width:46%" colspan="2"> ISSUE</td>
        <td class="jnt_td td_sub_pkg1" style="height:45px; width:46%" colspan="2"> CLEAR</td>
    </tr>
    <?php
    while ($sql_pkg_Pchk_arr = sql_fetch_array($sql_pkg_Pchk)) {
        if ($sql_pkg_Pchk_arr['s_no'] * 1 < 10) {
            $jpgfile = PCS_URL_PKG . '/' . $sql_pkg_Pchk_arr['pkg_no'] . '_00' . $sql_pkg_Pchk_arr['s_no'];
        } elseif ($sql_pkg_Pchk_arr['s_no'] * 1 < 100) {
            $jpgfile = PCS_URL_PKG . '/' . $sql_pkg_Pchk_arr['pkg_no'] . '_0' . $sql_pkg_Pchk_arr['s_no'];
        } else {
            $jpgfile = PCS_URL_PKG . '/' . $sql_pkg_Pchk_arr['pkg_no'] . '_' . $sql_pkg_Pchk_arr['s_no'];
        }

        ?>
        <tr>
            <td class="jnt_td" style="width:8%" rowspan="3"><?php echo $sql_pkg_Pchk_arr['s_no']; ?></td>
            <td class="jnt_td" style="height:45px; width:69%"
                colspan="3"><?php echo $sql_pkg_Pchk_arr['dwg_no']; ?></td>
            <td class="main_td">
                <a href='javascript:document.punch_modi<?php echo $sql_pkg_Pchk_arr['s_no']; ?>.submit()'><?php echo $sql_pkg_Pchk_arr['category']; ?></a>
                <form name='punch_modi<?php echo $sql_pkg_Pchk_arr['s_no']; ?>' method="post" target="_self"
                      onSubmit="return doSumbit()">
                    <input type="hidden" name="p_page" value="p_cont">
                    <input type="hidden" name="mode" value="remove">
                    <input type="hidden" name="t_no" value="<?php echo $sql_pkg_Pchk_arr['s_no']; ?>">
                    <input type="hidden" name="dwg" value="<?php echo $sql_pkg_Pchk_arr['dwg_no']; ?>">
                    <input type="hidden" name="pkg" value="<?php echo $view['wr_subject']; ?>">
                </form>
            </td>
        </tr>
        <tr>
            <td class="jnt_td" style="height:45px; width:23%; padding:0px 0px 0px 15px; text-align:left;" colspan="4">
                <?php
                if ($sql_pkg_Pchk_arr['punch_desc']) {
                    echo $sql_pkg_Pchk_arr['punch_desc'];
                } else {
                    echo '<p style="text-align:center;font-size:25px;"><font color = red><b> No description </b></font></p>';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="jnt_td" style="height:45px; width:46%" colspan="2">
                <?php
                if ($sql_pkg_Pchk_arr['issued_by']) {
                    echo '<a onclick=\'window.open("' . $jpgfile . '_BF.jpg","' . $jn . $photoType . '","width=650, height=500, left=200, top=100");\'>';
                    echo $sql_pkg_Pchk_arr['issued_by'] . '<br>' . $sql_pkg_Pchk_arr['issued_date'] . '</a>';
                }
                ?>
            </td>
            <td class="jnt_td" style="height:45px; width:46%" colspan="2">
                <?php
                if ($sql_pkg_Pchk_arr['cleared_by']) {
                    echo '<a onclick=\'window.open("' . $jpgfile . '_AF.jpg","' . $jn . $photoType . '","width=650, height=500, left=200, top=100");\'>';
                    echo $sql_pkg_Pchk_arr['cleared_by'] . '<br>' . $sql_pkg_Pchk_arr['cleared_date'] . '</a>';
                } elseif ($member['mb_7'] > 1) {
                    ?>
                    <a href='javascript:document.punch_clear<?php
                    echo $sql_pkg_Pchk_arr['s_no'];
                    ?>.submit()'><font color=blue><b>CLEAR PUNCH</b></font></a>
                    <form name='punch_clear<?php
                    echo $sql_pkg_Pchk_arr['s_no'];
                    ?>' method="post" target="_self" onSubmit="return doSumbit()">
                        <input type="hidden" name="p_page" value="p_cont">
                        <input type="hidden" name="mode" value="clear">
                        <input type="hidden" name="t_no" value="<?php
                        echo $sql_pkg_Pchk_arr['s_no'];
                        ?>">
                        <input type="hidden" name="dwg" value="<?php
                        echo $sql_pkg_Pchk_arr['dwg_no'];
                        ?>">
                        <input type="hidden" name="pkg" value="<?php
                        echo $view['wr_subject'];
                        ?>">
                    </form>
                    <?php
                } else {
                    echo '<font color = red><b> Not yet cleared </b></font>';
                }
                ?>
            </td>
        </tr>

        <?php
    }
    $query_view_punch_drop = 'DROP VIEW IF EXISTS punchInPKG';
    sql_query($query_view_punch_drop);

    ?>
    </tbody>
</table>
<p>&nbsp;