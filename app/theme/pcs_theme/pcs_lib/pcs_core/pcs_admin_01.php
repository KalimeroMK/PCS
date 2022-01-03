<?php

    if ($_POST['mb_nick'] or $_POST['mb_1'] or $_POST['mb_2'] or $_POST['mb_3'] or $_POST['mb_4'] or $_POST['mb_5'] or $_POST['mb_6'] or $_POST['mb_7']) {
    $query_update = 'UPDATE '.G5_TABLE_PREFIX.'member SET mb_nick = "'.$_POST['mb_nick'].'", mb_1 = "'.$_POST['mb_1'].'", mb_2 = "'.$_POST['mb_2'].'", mb_3 = "'.$_POST['mb_3'].'", mb_4 = "'.$_POST['mb_4'].'", mb_5 = "'.$_POST['mb_5'].'", mb_6 = "'.$_POST['mb_6'].'", mb_7 = "'.$_POST['mb_7'].'" WHERE mb_no = "'.$_POST['mb_no'].'"';
    sql_query($query_update);
    echo '<script type="text/javascript"> location.href="'.G5_URL.'" </script>';
}
    else {
    $table_field_array  = [
        'No', 'ID', 'Name', 'Position', 'Authority', 'Scan & Photo', 'Data control', 'Welding Check', 'PWHT / PMI', 'NDE test',
        'PKG Control', 'UPDATE',
    ];
    $table_width_array  = [4, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8];
    $mysql_field_array  = ['mb_no', 'mb_id', 'mb_name', 'mb_nick', 'mb_1', 'mb_2', 'mb_3', 'mb_4', 'mb_5', 'mb_6', 'mb_7'];
    $auth_option_array  = ['Worker', 'Inspector', 'Manager', 'Admin'];
    $spool_option_array = ['Scanning', 'Working', 'All'];
    $photo_option_array = ['Input', 'Approve', 'All'];
    $nde_option_array   = ['Work done', 'Mark-up', 'All'];
    $Weld_option_array  = ['Request', 'Accept', 'All'];
    $pkg_option_array   = ['Issue', 'Clear', 'All'];

    $query_member = "SELECT * FROM ".G5_TABLE_PREFIX."member";
    $sql_member   = sql_query($query_member);

    $query_field      = "DESC ".G5_TABLE_PREFIX."member";
    $field_enum_value = enum_value($query_field);
?>

<table class="main">
    <caption> PROJECT MEMBER STATUS</caption>
    <tbody>
    <tr>

        <?php
            for ($i = 0; $table_field_array[$i]; $i++) {
                echo '<td class="jnt_td jnt_th" style="width: '.$table_width_array[$i].'%;">'.$table_field_array[$i].'</td>';
            }
        ?>

    </tr>

    <?php
        $j = 0;
        while ($sql_member_arr = sql_fetch_array($sql_member)) {
            ?>

            <tr>
                <form name='submit_form<?php
                    echo $j + 1; ?>' method="post" onSubmit="return doSumbit()">
                    <input type='hidden' name='<?php
                        echo 'mb_no'; ?>' value=<?php
                        echo $sql_member_arr['mb_no']; ?>>

                    <?php
                        for ($i = 0; $table_field_array[$i]; $i++) {
                            echo '<td class="jnt_td" style="width:'.$table_width_array[$i].'px;">';
                            if ($i == 0) {
                                echo ++$j;
                            } elseif ($i < 3) {
                                echo $sql_member_arr[$mysql_field_array[$i]];
                            } elseif ($i < 4) {
                                echo '<input type="text" name="'.$mysql_field_array[$i].'" value="'.$sql_member_arr[$mysql_field_array[$i]].'" style="text-align:center;width:95%;height:40px;font-size:15px;background-color:bisque;">';
                            } elseif ($i < 11) {
                                echo '<select name="'.$mysql_field_array[$i].'" style="WIDTH: 95%; height: 40px; font-size:15px; background-color:bisque"><option value="0">-</option>';
                                switch ($i) {
                                    case 4    :
                                        sel_option_arr($auth_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                    case 5    :
                                        sel_option_arr($spool_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                    case 6    :
                                        sel_option_arr($photo_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                    case 8    :
                                        sel_option_arr($nde_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                    case 9    :
                                        sel_option_arr($nde_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                    case 10    :
                                        sel_option_arr($pkg_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                    default :
                                        sel_option_arr($Weld_option_array, $sql_member_arr[$mysql_field_array[$i]]);
                                        break;
                                }
                                echo '</select>';
                            } elseif ($i == 11) {
                                echo '<a href = "javascript:document.submit_form'.$j.'.submit()" style="font-size:20px;"> <b> UPDATE </b></a>';
                            }
                        }
                    ?>
                </form>
            </tr>

            <?php
        }
        }
    ?>

    </tbody>
</table>

<p>&nbsp;