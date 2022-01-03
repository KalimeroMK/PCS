<?php
    if ( ! $_POST['form_type']) {
        switch ($_GET['wr_id']) {
            case 2 :
                $pcs_typ = 'joint';
                $dir     = PCS_REP_PDF;
                break;
            case 3 :
                $pcs_typ = 'spool';
                break;
            case 4 :
                $pcs_typ = 'iso';
                $dir     = PCS_DWG_ISO;
                break;
            case 5 :
                $pcs_typ = 'pnid';
                $dir     = PCS_PNID_PDF;
                break;
            case 6 :
                $pcs_typ = 'plan';
                $dir     = PCS_PLAN_PDF;
                break;
            case 7 :
                $pcs_typ = 'tp';
                break;
            case 8 :
                $pcs_typ = 'package';
                break;
            case 9 :
                $pcs_typ = 'bmlist';
                break;
            case 10 :
                $pcs_typ = 'itemcode';
                break;
            case 11 :
                $pcs_typ = 'cutpipe';
                break;
            case 12 :
                $pcs_typ = 'work';
                break;
            default:
                break;
        }

        $query_j_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_'.$pcs_typ;
        $j_qty       = pcs_sql_value($query_j_qty);
        ?>
        <div style='background-color:white; width:100%; height:300px;'>

            <div style='background-color:white; padding:10px; width:57%; float: left;'>
                <table class="main">
                    <tbody>
                    <tr>
                        <td class="main_td" style="text-align:left; padding:10px;">
                            Total <strong><?php
                                    echo $j_qty; ?></strong> <?php
                                echo $pcs_typ; ?> data in PCS Database.<br>
                            If you upload Zip file, All <?php
                                echo $pcs_typ; ?> data <font color="red">will be updated!</font><br>
                            Please make sure your data is correct.
                        </td>
                    </tr>
                    <form name="zipdata" id="zipdata" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="form_type" value="zip">
                        <input type="hidden" name="form_name" value="<?php
                            echo $pcs_typ; ?>">
                        <tr>
                            <td class="main_td" style="text-align:left; padding:10px;"><input type="file" name="zip_file" id='zip_file'
                                                                                              style='width:100%'
                                                                                              accept='application/x-zip-compressed'/>
                        </tr>
                        <tr>
                            <td class="main_td" style="padding:10px;"><input type="submit" value="<?php
                                    echo ' '.$pcs_typ.'.zip'; ?> update " accesskey="s" class="btn_submit"/>
                        </tr>
                    </form>
                    </tbody>
                </table>
            </div>
            <?php
                if ($pcs_typ == 'iso' || $pcs_typ == 'pnid' || $pcs_typ == 'plan') {
                    ?>
                    <div style='background-color:white; padding:10px; width:43%; float: right;'>
                        <table class="main">
                            <tbody>
                            <tr>
                                <td class="main_td" style="text-align:left; padding:10px;">
                                    Total <strong><?php
                                            echo count_files($dir); ?></strong> PDF files in server.<br>
                                </td>
                            </tr>
                            <form name="pdffiles" id="pdffiles" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="form_type" value="pdf">
                                <input type="hidden" name="form_name" value="<?php
                                    echo $pcs_typ; ?>">
                                <tr>
                                    <td class="main_td" style="text-align:left; padding:10px;"><input type="file" name="pdf_file[]"
                                                                                                      id='pdf_file' style='width:100%'
                                                                                                      accept='application/pdf' multiple/>
                                </tr>
                                <tr>
                                    <td class="main_td" style="padding:10px;"><input type="submit" value=" PDF upload " accesskey="s"
                                                                                     class="btn_submit" onclick="submitAction();"/>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                    </div>

                    <?php
                }
            ?>

        </div>


        <?php
    }

    if ($_POST['form_type'] == 'zip') {
        echo '<div style="font-size:30px; padding:30px;">';

        $dir = PCS_DATA.'/';

        $zip_file_name = $_FILES['zip_file']['name'];
        if (str_replace('.zip', '', $zip_file_name) == $_POST['form_name']) {
            if ($_FILES['zip_file']['type'] = 'application/x-zip-compressed') {
                if ($_FILES['zip_file']['size'] < 5000000) {
                    if (move_uploaded_file($_FILES['zip_file']['tmp_name'], $dir.$zip_file_name)) {
                        chmod($dir.$zip_file_name, G5_FILE_PERMISSION);
                        $zip = new ZipArchive;
                        if ($zip->open($dir.$zip_file_name) === true) {
                            $zip->extractTo($dir);
                            $zip->close();
                        }

                        $csvfile   = file($dir.$_POST['form_name'].'.txt');
                        $csvarray  = explode("\r\n", implode($csvfile));
                        $pcs_field = trim(implode(",", explode("\t", addslashes($csvarray[0]))));

                        if ($_POST['form_name'] != 'jnt_krp') {
                            sql_query('TRUNCATE TABLE '.G5_TABLE_PREFIX.'pcs_info_'.$_POST['form_name']);    // 테이블 비움
                        }

                        if ($_POST['form_name'] == 'joint') {        // 차후 joint수가 10만 이상 증가할 경우 속도향상을 위해 index 일시 삭제
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint DROP INDEX dwg_no');
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint DROP INDEX pkg_no');
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint DROP INDEX spool_no');
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint DROP INDEX welder_1');
                        } else {
                            sql_query('TRUNCATE TABLE '.G5_TABLE_PREFIX.'write_'.$_POST['form_name']);
                        }

                        $lpcnt = count($csvarray) - 1;

                        for ($j = 1; $j < $lpcnt; $j++) {
                            $csvfield  = explode("\t", addslashes($csvarray[$j]));
                            $row_value = "'".trim(implode("','", $csvfield))."'";

                            $query_insert = 'INSERT INTO '.G5_TABLE_PREFIX.'pcs_info_'.$_POST['form_name'].' ('.$pcs_field.') VALUES ('.$row_value.')';
                            sql_query($query_insert, true);

                            if ($_POST['form_name'] == 'iso') {
                                $query_insert = 'INSERT INTO '.G5_TABLE_PREFIX.'write_'.$_POST['form_name'].' (wr_id,wr_num,wr_parent,wr_subject,wr_1,wr_content) VALUES ("'.$j.'","'.-$j.'","'.$j.'","'.$csvfield[0].'","'.$csvfield[4].'","'.$csvfield[1].'")';
                                sql_query($query_insert);
                            } elseif ($_POST['form_name'] == 'spool' || $_POST['form_name'] == 'pnid' || $_POST['form_name'] == 'plan' || $_POST['form_name'] == 'tp' || $_POST['form_name'] == 'package' || $_POST['form_name'] == 'work') {
                                $query_insert = 'INSERT INTO '.G5_TABLE_PREFIX.'write_'.$_POST['form_name'].' (wr_id,wr_num,wr_parent,wr_subject) VALUES ("'.$j.'","'.-$j.'","'.$j.'","'.$csvfield[0].'")';
                                sql_query($query_insert);
                            }
                        }

                        if ($_POST['form_name'] == 'joint') {
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint ADD INDEX (dwg_no)');
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint ADD INDEX (pkg_no)');
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint ADD INDEX (spool_no)');
                            sql_query('ALTER TABLE '.G5_TABLE_PREFIX.'pcs_info_joint ADD INDEX (welder_1)');
                        } else {
                            sql_query('UPDATE '.G5_TABLE_PREFIX.'board set bo_count_write = '.($j - 1).' WHERE bo_table = "'.$_POST['form_name'].'"');
                        }

                        unlink($dir.$zip_file_name);
                        unlink($dir.$_POST['form_name'].'.txt');

                        $query_j_qty = "SELECT COUNT(*) FROM ".G5_TABLE_PREFIX."pcs_info_".$_POST['form_name'];
                        $j_qty       = pcs_sql_value($query_j_qty);

                        echo '<font color = "black" size = 5> Total <strong>'.$j_qty.'</strong> '.$_POST['form_name'].'s inserted into PCS Database.<br></font>';
                    } else {
                        echo "Error Update Data <br/>";
                    }
                } else {
                    echo 'Not allowed <b>over 5 Mb</b> zip file. <br/>';
                }
            } else {
                echo 'Not Zip file <br/>';
            }
        } else {
            echo 'You selected <b>'.$zip_file_name.'</b>!!  Please select <font color="red"><b>'.$_POST['form_name'].'.zip</font></b> file for Uploading. <br/>';
        }
        echo "</div>";
    }

    if ($_POST['form_type'] == 'pdf') {
        echo '<div style="font-size:30px; padding:30px;">';
        if ($_POST['form_name'] == 'joint') {
            $dir = PCS_REP_PDF.'/';
        }
        if ($_POST['form_name'] == 'iso') {
            $dir = PCS_DWG_ISO.'/';
            $fld = 'dwg_no';
        }
        if ($_POST['form_name'] == 'pnid') {
            $dir = PCS_PNID_PDF.'/';
            $fld = 'pnid_no';
        }
        if ($_POST['form_name'] == 'plan') {
            $dir = PCS_PLAN_PDF.'/';
            $fld = 'plan_no';
        }
        if ($_POST['form_name'] == 'equipment') {
            $dir = PCS_EQUI_PDF.'/';
            $fld = 'eq_no';
        }

        $pdf_file_name  = $_FILES['pdf_file']['name'];
        $upload_success = 0;
        $temp_i = count($pdf_file_name);
        for ($i = 0; $i < $temp_i; $i++) {
            if ($_POST['form_name'] != 'joint') {
                $query_file   = 'SELECT '.$fld.' FROM '.G5_TABLE_PREFIX.'pcs_info_'.$_POST['form_name'].' WHERE '.$fld.' = "'.preg_replace('/_[0-9].pdf/',
                        '', $pdf_file_name[$i]).'"';
                $sql_file     = sql_query($query_file);
                $sql_file_arr = sql_fetch_array($sql_file);

                if ($sql_file_arr[$fld]) {
                    if ( ! $_FILES['pdf_file']['error'][$i]) {
                        if ($_FILES['pdf_file']['type'][$i] == 'application/pdf') {
                            if ($_FILES['pdf_file']['size'][$i] < 5000000) {
                                if (move_uploaded_file($_FILES['pdf_file']['tmp_name'][$i], $dir.$pdf_file_name[$i])) {
                                    $upload_success++;
                                    chmod($dir.$pdf_file_name[$i], 0777);
                                } else {
                                    echo ($i + 1).'. '.$pdf_file_name[$i].' : <font color="red">Error Update Data.</font> <br/>';
                                }
                            } else {
                                echo ($i + 1).'. '.$pdf_file_name[$i].' : <font color="red">Not allowed <b>over 5 Mb</b> pdf file.</font> <br/>';
                            }
                        } else {
                            echo ($i + 1).'. '.$pdf_file_name[$i].' :<font color="red"> Not pdf file.</font> <br/>';
                        }
                    } else {
                        echo ($i + 1).'. '.$pdf_file_name[$i].' : <font color="red">Error Update Data.</font> <br/>';
                    }
                } else {
                    echo ($i + 1).'. '.$pdf_file_name[$i].' : <font color="red">Not in DATABASE.</font> <br/>';
                }
            } else {
                if ( ! $_FILES['pdf_file']['error'][$i]) {
                    if ($_FILES['pdf_file']['type'][$i] == 'application/pdf') {
                        if ($_FILES['pdf_file']['size'][$i] < 5000000) {
                            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'][$i], $dir.$pdf_file_name[$i])) {
                                $upload_success++;
                                chmod($dir.$pdf_file_name[$i], 0777);
//								echo ($i+1).'. '.$pdf_file_name[$i]." : <font color='blue'>Upload finished.</font> <br/>";
                            } else {
                                echo ($i + 1).'. '.$pdf_file_name[$i].' : <font color="red">Error Update Data.</font> <br/>';
                            }
                        } else {
                            echo ($i + 1).'. '.$pdf_file_name[$i].' : <font color="red">Not allowed <b>over 5 Mb</b> pdf file.</font> <br/>';
                        }
                    } else {
                        echo ($i + 1).'. '.$pdf_file_name[$i].' :<font color="red"> Not pdf file.</font> <br/>';
                    }
                }
            }
        }
        echo '<br/>';
        echo 'Total : <font color="blue"><b>'.$upload_success.'</b></font> pdf file(s) Upload successfully. <br/>';
        echo '</div>';
    }
?>