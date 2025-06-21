<?php
if($_POST['p_page'] == 'p_upda'){
	$filepath  = PCS_DWG_PKG.'/'.$_POST['pkg_no'].'/';

	if ($_POST['s_no']*1<10) {
        $punchFile = $_POST['pkg_no'].'_00'.$_POST['s_no'];
    } elseif ($_POST['s_no']*1<100) {
        $punchFile = $_POST['pkg_no'].'_0'.$_POST['s_no'];
    } else {$punchFile = $_POST['pkg_no'].'_'.$_POST['s_no'];}
	
	if($_POST['punch']=='issue'){				
		$punch_sql = 'INSERT INTO '.G5_TABLE_PREFIX.'pcs_info_punch SET
					  pkg_no = "'.$_POST['pkg_no'].'" 
					, s_no = "'.$_POST['s_no'].'"
					, dwg_no = "'.$_POST['dwg_no'].'"
					, category = "'.$_POST['punch_cat'].'"';	sql_query($punch_sql);
	}
	if($_POST['punch']=='issue' || $_POST['punch_des']){				
		$punch_sql = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_punch SET
						punch_desc = "'.$_POST['punch_des'].'"
					WHERE pkg_no = "'.$_POST['pkg_no'].'" AND s_no = "'.$_POST['s_no'].'"';	sql_query($punch_sql);
	}
	if($_POST['punch']=='clear' || $_POST['pcs_img_str2']){
		$punch_sql = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_punch SET
						  cleared_date = "'.G5_TIME_YMDHIS.'"
						, cleared_by = "'.$member['mb_nick'].'"
						, cleared_photo = "'.$punchFile.'_AF"
					WHERE pkg_no = "'.$_POST['pkg_no'].'" AND s_no = "'.$_POST['s_no'].'"';	sql_query($punch_sql);

		$filename = get_safe_filename($punchFile.'_AF.jpg');
		base64_to_img($_POST['pcs_img_str2'],$filepath.$filename);
	}
	if($_POST['punch']=='issue' || $_POST['pcs_img_str1']){
		$punch_sql = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_punch SET
						  issued_date = "'.G5_TIME_YMDHIS.'"
						, issued_by = "'.$member['mb_nick'].'"
						, Issued_photo = "'.$punchFile.'_BF"
					WHERE pkg_no = "'.$_POST['pkg_no'].'" AND s_no = "'.$_POST['s_no'].'"';	sql_query($punch_sql);

		$filename = get_safe_filename($punchFile.'_BF.jpg');
		base64_to_img($_POST['pcs_img_str1'],$filepath.$filename);
	}
	if($_POST['punch']=='clear' || $_POST['pcs_img_str2']){
		$punch_sql = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_punch SET
						  cleared_date = "'.G5_TIME_YMDHIS.'"
						, cleared_by = "'.$member['mb_nick'].'"
						, cleared_photo = "'.$punchFile.'_AF"
					WHERE pkg_no = "'.$_POST['pkg_no'].'" AND s_no = "'.$_POST['s_no'].'"';	sql_query($punch_sql);

		$filename = get_safe_filename($punchFile.'_AF.jpg');
		base64_to_img($_POST['pcs_img_str2'],$filepath.$filename);
	}
	if($_POST['punch']=='remove'){
		$punch_sql = 'DELETE FROM '.G5_TABLE_PREFIX.'pcs_info_punch	WHERE pkg_no = "'.$_POST['pkg_no'].'" AND s_no = "'.$_POST['s_no'].'"';	sql_query($punch_sql);
	unlink($filepath.'/'.$punchFile.'_BF.jpg');
	unlink($filepath.'/'.$punchFile.'_AF.jpg');
	}
	
	echo '<script type="text/javascript"> location.href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&wr_id='.$wr_id.'" </script>';
}
