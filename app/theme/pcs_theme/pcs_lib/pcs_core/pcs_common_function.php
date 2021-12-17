<?php 
function photo_thumb($folder, $photoFile, $jn, $thumbWidth, $dwg='') {
	if ($photoFile) {
		$ran = mt_rand(1, 10000);

		if($folder=='photo_1'||$folder=='photo_2'||$folder=='spool'){
			$ptPath = PCS_ISO_URL.'/'.$dwg;
			echo '<a onclick=\'window.open("'.$ptPath.'/'.$photoFile.'.jpg?ran='.$ran.'","'.$folder.$jn.'","width=800, height=600, left=200, top=100");\'>';
			echo '<img src="'.$ptPath.'/thumb_'.$photoFile.'.jpg?ran='.$ran.'" width="'.$thumbWidth.'px" ></a><br>';
		}
		else{
			if($folder=='tp'){
				$ptPath = PCS_URL_PHOTO.'/tp';
				echo '<a onclick=\'window.open("'.$ptPath.'/'.$photoFile.'.jpg?ran='.$ran.'","'.$folder.$jn.'","width=800, height=600, left=200, top=100");\'>';
				echo '<img src="'.$ptPath.'/'.$dwg.$photoFile.'.jpg?ran='.$ran.'" width="'.$thumbWidth.'px" ></a><br>';
			}
			else if($folder=='daily'){
				$ptPath = PCS_URL_DAILY.'/'.$photoFile;
				echo '<a onclick=\'window.open("'.$ptPath.'/'.$photoFile.'_'.$jn.'.jpg?ran='.$ran.'","'.$folder.$jn.'","width=800, height=600, left=200, top=100");\'>';
				echo '<img src="'.$ptPath.'/thumb_'.$photoFile.'_'.$jn.'.jpg?ran='.$ran.'" width="'.$thumbWidth.'px" ></a><br>';
			}
			else{$ptPath = PCS_ISO_URL.'/'.$dwg;
				echo '<a onclick=\'window.open("'.$ptPath.'/'.$photoFile.'.jpg?ran='.$ran.'","'.$folder.$jn.'","width=800, height=600, left=200, top=100");\'>';
				echo '<img src="'.$ptPath.'/'.$dwg.$photoFile.'.jpg?ran='.$ran.'" width="'.$thumbWidth.'px" ></a><br>';
			}
		}
	}
}

function photo_up($folder, $sub, $jn, $photo, $dwg='') {
	if(!$dwg){$dwg=$sub;}
	echo '
	<a href = "javascript:document.'.$folder.$jn.'.submit()">(Photo update)</a>
	<form name="'.$folder.$jn.'" method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="photo" value="'.$photo.'">
	<input type="hidden" name="sbjt" value="'.$sub.'">
	<input type="hidden" name="j_no" value="'.$jn.'">
	<input type="hidden" name="folder" value="'.$folder.'">
	<input type="hidden" name="spdwg" value="'.$dwg.'">
	</form>
	';
}



// field명 추출
function field_name_array($query) {
	
	$sql_field = sql_query ($query);

	while ($fld_arr = sql_fetch_array ($sql_field))	{ $qry_field_name[] = $fld_arr['Field'];}

	return $qry_field_name;
}


// Enum value 배열반환함수
function enum_value($query) {

	$sql_field = sql_query ($query);

	while ($fld_arr = sql_fetch_array ($sql_field))	{
		if(strpos($fld_arr['Type'],'enum') !== false) {
			$temp_str = str_replace('enum(','',$fld_arr['Type']);
			$temp_str = str_replace(')','',$temp_str);
			$temp_str = str_replace("'",'',$temp_str);
			$qry_field_name[$fld_arr['Field']] = explode(',',$temp_str);
		}
	}
	return $qry_field_name;
}


// sql_value 함수
function pcs_sql_value($query) {
	$rlt = sql_query ($query);
	if($rlt){$sql_value_array = mysqli_fetch_row ($rlt);return $sql_value_array[0];}
	else{return 0;}
}



// package insert to pcs 함수
function pcs_package_insert($arr) {

	$query_pkg_pcs = "SELECT * FROM ".G5_TABLE_PREFIX."pcs_info_pkg_stat WHERE pkg_no = '".$arr['pkg_no']."'";
	$sql_pkg_pcs = sql_query ($query_pkg_pcs);
	if ($sql_pkg_pcs_arr = sql_fetch_array ($sql_pkg_pcs)) {	}
	else {
		$insert_pkg_pcs = "INSERT INTO ".G5_TABLE_PREFIX."pcs_info_pkg_stat (pkg_no) VALUES ('".$arr['pkg_no']."')";
		sql_query ($insert_pkg_pcs);

		$query_pkg_pcs = "SELECT * FROM ".G5_TABLE_PREFIX."pcs_info_pkg_stat WHERE pkg_no = '".$arr['pkg_no']."'";
		$sql_pkg_pcs = sql_query ($query_pkg_pcs);
		$sql_pkg_pcs_arr = sql_fetch_array ($sql_pkg_pcs);			
	}
	return $sql_pkg_pcs_arr;
}


// PDF Download 함수
/*
function pdf_download($file_desc, $file_name) {

	return PCS_LIB_URL.'/PDF_down.php?flde='.$file_desc.'&flna='.$file_name;
}
*/

// PDF Report Download 함수
/*
function pdf_report_download($type, $name, $report, $date, $result='') {

	return '<a href="'.pdf_download($type, $name).'">'.$report.'<br>'.$date.'<br>'.$result.'</a>';
}
*/


// Select 옵션 생성함수 from server
function sel_option_enum($array, $selected) {
	for ($i=0; $array[$i]; $i++){
		$option = '<option value='.$array[$i];
		if($array[$i]==$selected) {$option .= ' selected ';}
		$option .= '>'.$array[$i].'</option>';
		
		echo $option;
	}
}


// Select 옵션 생성함수 from array
function sel_option_arr($array, $selected) {
	for ($i=0; $i<count($array); $i++){
		$option = '<option value='.($i+1);
		if($i==($selected-1)) {$option .= ' selected ';}
		$option .= '>'.$array[$i].'</option>';
		
		echo $option;
	}
}



// Submit 텍스트
function submit_text($sno, $fld_id, $dwg_no, $jnt_no, $nm, $tm, $r_c, $nde_tp) {

	$txt_key = $fld_id.$r_c.$sno.$nde_tp;

	echo '<form name="'.$txt_key.'" method="post" onSubmit="return doSumbit()">';
	echo '<input type="hidden" name="field_id" value="'.$fld_id.'">';
	echo '<input type="hidden" name="drawg_id" value="'.$dwg_no.'">';
	echo '<input type="hidden" name="joint_id" value="'.$jnt_no.'">';
	echo '<input type="hidden" name="chk_name" value="'.$nm.'">';
	echo '<input type="hidden" name="chk_time" value="'.$tm.'">';
	echo '<input type="hidden" name="btn_stat" value="'.$r_c.'">';
	echo '<input type="hidden" name="nde_type" value="'.$nde_tp.'">';
	echo '<input type="hidden" name="html_loc" value="'.$sno.'">';
	
	switch ($r_c)	{
		case 'request' :
			$a_color = 'green';
			if($nde_tp) {$a_txt = $nde_tp;}	else {$a_txt = 'REQUEST';};
			break;

		case 'confirm' :
			$a_color = 'blue';
			$a_txt = 'DONE';
			break;

		case 'Accept' :
			$a_color = 'blue';
			$a_txt = 'ACC';
			break;

		case 'Reject' :
			$a_color = 'red';
			$a_txt = 'REJ';
			break;

		Default  :
			$a_color = 'orange';
			$a_txt = 'BACK';
			break;
	}
	echo '<a href = "javascript:document.'.$fld_id.$r_c.$sno.$nde_tp.'.submit();"> <font color = '.$a_color.'><b>'.$a_txt.'</b></font></a></form>';
}


// spool_joint 체크 쿼리문
function spl_ins_qry($f_id, $b_st) {
	switch ($f_id)	{

		case 'Fit_up' :
		
			switch ($b_st)	{
		
				case 'request' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_fitup_rlt = 'Request' ,pcs_fitup_rlt_by = '$_POST[chk_name]', pcs_fitup_req_date = '$_POST[chk_time]', j_cng = '$_POST[chk_time]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				case 'cancle_req' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_fitup_rlt = '', pcs_fitup_req_date = '0000-00-00 00:00:00', pcs_fitup_rlt_by = '', j_cng = '$_POST[chk_time]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'Accept' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_fitup_rlt = 'Accept', pcs_fitup_rlt_date = '$_POST[chk_time]', j_cng = '$_POST[chk_time]', pcs_fitup_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'Reject' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_fitup_rlt = 'Reject', pcs_fitup_rlt_date = '$_POST[chk_time]', j_cng = '$_POST[chk_time]', pcs_fitup_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'cancle_eva' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_fitup_req_date = '0000-00-00 00:00:00', pcs_fitup_rlt = '' , pcs_fitup_rlt_by = '', pcs_fitup_rlt_date = '0000-00-00 00:00:00', j_cng = '$_POST[chk_time]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				Default  :	break;
			}
			break;
			
		case 'VI' :
		
			switch ($b_st)	{
			
				case 'request' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET  pcs_vi_rlt = 'Request', pcs_vi_rlt_by = '$_POST[chk_name]', pcs_vi_req_date = '$_POST[chk_time]', j_cng = '$_POST[chk_time]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				case 'cancle_req' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_vi_req_date = '0000-00-00 00:00:00', pcs_vi_rlt = '', pcs_vi_rlt_by = '', j_cng = '$_POST[chk_time]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'Accept' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_vi_rlt = 'Accept', pcs_vi_rlt_date = '$_POST[chk_time]', j_cng = '$_POST[chk_time]', pcs_vi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'Reject' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_vi_rlt = 'Reject', pcs_vi_rlt_date = '$_POST[chk_time]', j_cng = '$_POST[chk_time]', pcs_vi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'cancle_eva' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_vi_rlt_date = '0000-00-00 00:00:00', pcs_vi_rlt = 'Request', j_cng = '$_POST[chk_time]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				Default  :	break;
			}
			break;

		case 'PWHT' :
		
			switch ($b_st)	{
			
				case 'request' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_req_date = '$_POST[chk_time]', pcs_pwht_rlt = 'Request', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'cancle_req' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_req_date = '0000-00-00 00:00:00', pcs_pwht_rlt = '', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = '' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				case 'confirm' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_rlt_date = '$_POST[chk_time]', pcs_pwht_rlt = 'Done', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'cancle_job' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_rlt_date = '0000-00-00 00:00:00', pcs_pwht_rlt = 'Request', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = 'Rollback' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				case 'Accept' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_rlt = 'Accept', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'Reject' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_rlt = 'Reject', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'cancle_eva' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pwht_rlt = 'Done', j_cng = '$_POST[chk_time]', pcs_pwht_rlt_by = 'Rollback' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				Default  :	break;
			}
			break;			

		case 'PMI' :
		
			switch ($b_st)	{
			
				case 'request' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_req_date = '$_POST[chk_time]', pcs_pmi_rlt = 'Request', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'cancle_req' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_req_date = '0000-00-00 00:00:00', pcs_pmi_rlt = '', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = '' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'confirm' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_rlt_date = '$_POST[chk_time]', pcs_pmi_rlt = 'Done', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'cancle_job' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_rlt_date = '0000-00-00 00:00:00', pcs_pmi_rlt = 'Request', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = 'Rollback' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				case 'Accept' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_rlt = 'Accept', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'Reject' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_rlt = 'Reject', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'cancle_eva' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_pmi_rlt = 'Done', j_cng = '$_POST[chk_time]', pcs_pmi_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				Default  :	break;
			}
			break;			

		case 'NDE' :
		
			switch ($b_st)	{
			
				case 'request' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_req_date = '$_POST[chk_time]', pcs_nde_type = '$_POST[nde_type]', pcs_nde_rlt = 'Request', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'cancle_req' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_req_date = '0000-00-00 00:00:00', pcs_nde_type = '', pcs_nde_rlt = '', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = '' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
					
				case 'confirm' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_rlt_date = '$_POST[chk_time]', pcs_nde_rlt = 'Done', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'cancle_job' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_rlt_date = '0000-00-00 00:00:00', pcs_nde_rlt = 'Request', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = 'Rollback' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'Accept' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_rlt = 'Accept', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;
				
				case 'Reject' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_rlt = 'Reject', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = '$_POST[chk_name]' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				case 'cancle_eva' :
					$query_jnt_udt = 
					"UPDATE ".G5_TABLE_PREFIX."pcs_info_jnt_sbc 
					SET pcs_nde_rlt = 'Done', j_cng = '$_POST[chk_time]', pcs_nde_rlt_by = 'Rollback' 
					WHERE dwg_no = '$_POST[drawg_id]' AND j_no = '$_POST[joint_id]' ;";
					break;

				Default  :	break;
			}
			break;			

		Default  :	break;

	}
	sql_query ($query_jnt_udt);
}

// fitup
function insp_fitup($sno, $dwg, $jnt, $mem_insp, $mem_nick, $f_rqd, $f_rlt, $f_rld, $v_rld) {
	
	switch($mem_insp){

		case 1:
					
			switch ($f_rlt) {
							
				case '' :
							
					echo submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
								
				case 'Request' :

					echo $f_rqd.'<br>Fit_up : '.$f_rlt;
					if ($f_rqd == G5_TIME_YMD && !$v_rld){submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');}
					break;
								
				default :	echo $f_rqd.'<br>Fit_up : '.$f_rlt;	break;
						}
			break;
							
		case 2:
					
			switch ($f_rlt) {
							
				case '' :
								
					echo submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
								
				case 'Request' :
							
					echo "<div>";
					echo $f_rqd.'<br>Fit_up : '.$f_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;
								
				default :
							
					echo $f_rqd.'<br>Fit_up : '.$f_rlt;
					if ($f_rqd == G5_TIME_YMD && !$v_rld){submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;

			};
						
			break;

		case 3:
			switch ($f_rlt) {
							
				case '' :

					echo submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
							
				case 'Request' :
						
					echo "<div>";
					echo $f_rqd.'<br>Fit_up : '.$f_rlt;
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');
					echo "</div><div style='float:left;width:32%'>";
					submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;

				default :
							
					echo $f_rld.'<br>Fit_up : '.$f_rlt;
					if (!$v_rld){submit_text($sno, 'Fit_up', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;
			}
			break;
	
		default : break;
	}
}


// VI

function insp_vi($sno, $dwg, $jnt, $mem_insp, $mem_nick, $v_rqd, $v_rlt, $v_rld, $pwr, $pmr, $ndr) {

	switch($mem_insp){

		case 1:
					
			switch ($v_rlt) {
							
				case '' :
							
					echo 'Visual Inspection';	submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
								
				case 'Request' :

					echo $v_rqd.'<br>VI : '.$v_rlt;
					if ($v_rqd == G5_TIME_YMD){submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');}
					break;
								
				default :	echo $v_rld.'<br>VI : '.$v_rlt;	break;
						}
			break;
							
		case 2:
					
			switch ($v_rlt) {
							
				case '' :
								
					echo 'Visual Inspection';	submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
								
				case 'Request' :
							
					echo "<div>";
					echo $v_rqd.'<br>VI : '.$v_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;
								
				default :
							
					echo $v_rld.'<br>VI : '.$v_rlt;
					if ($v_rqd == G5_TIME_YMD && !$pwr&&!$pmr&&!$ndr){submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;

			};
						
			break;

		case 3:
			switch ($v_rlt) {
							
				case '' :

					echo 'Visual Inspection';	submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
							
				case 'Request' :
						
					echo "<div>";
					echo $v_rqd.'<br>VI : '.$v_rlt;
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');
					echo "</div><div style='float:left;width:32%'>";
					submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;

				default :
							
					echo $v_rld.'<br>VI : '.$v_rlt;
					if (!$pwr&&!$pmr&&!$ndr){submit_text($sno, 'VI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;
			}
			break;
	
		default : break;
	}
}


//PMI
function insp_pmi($sno, $dwg, $jnt, $mem_insp, $mem_nick, $pmi_rqd, $pmi_rlt, $pmi_rld, $ndr) {
	
	switch($mem_insp){

		case 1:
					
			switch ($pmi_rlt) {
							
				case '' :echo 'Not yet<br>PMI Requested';	break;
								
				case 'Request' :
							
					echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'confirm', '');
					break;
								
				case 'Done' :
					if ($pmi_rld == G5_TIME_YMD){
						echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;
						submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_job', '');
					}
					break;
								
				default :	echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;	break;
			}
			break;
							
		case 2:
					
			switch ($pmi_rlt) {
							
				case '' :
								
					echo 'PMI';submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
								
				case 'Request' :
							
					echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;
					if ($pmi_rqd == G5_TIME_YMD){submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');}
					break;
								
				case 'Done' :
							
					echo "<div>";
					echo $pmi_rld.'<br>PMI : '.$pmi_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;
								
				default :
							
					echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;
					if ($pmi_rqd == G5_TIME_YMD && !$ndr){submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;

			};
						
			break;

		case 3:
			switch ($pmi_rlt) {
							
				case '' :

					echo 'PMI';submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
							
				case 'Request' :
							
					echo "<div>";
					echo $pmi_rqd.'<br>PMI : '.$pmi_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'confirm', '');
					echo '</div>';
					break;
						
				case 'Done' :
					
					echo "<div>";
					echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_job', '');
					echo "</div><div style='float:left;width:32%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;
					
				default :
							
					echo $pmi_rqd.'<br>PMI : '.$pmi_rlt;
					if (!$ndr){submit_text($sno, 'PMI', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;
			}
			break;
		
		default : break;
	}
}


//PWHT

function insp_pwht($sno, $dwg, $jnt, $mem_insp, $mem_nick, $pw_rqd, $pw_rlt, $pw_rld, $ndr) {

	switch($mem_insp){
		
		case 1:
					
			switch ($pw_rlt) {
							
				case '' 	:echo 'Not yet<br>PWHT Requested';	break;
								
				case 'Request' :
							
					echo $pw_rqd.'<br>PWHT : '.$pw_rlt;
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'confirm', '');
					break;
								
				case 'Done' :
					if ($pw_rld == G5_TIME_YMD){
						echo $pw_rqd.'<br>PWHT : '.$pw_rlt;
						submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_job', '');
					}
					break;
								
				default :	echo $pw_rqd.'<br>PWHT : '.$pw_rlt;	break;
			}
			break;
							
		case 2:
					
			switch ($pw_rlt) {
							
				case '' :
								
					echo 'PWHT';submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
								
				case 'Request' :
							
					echo $pw_rqd.'<br>PWHT : '.$pw_rlt;
					if ($pw_rqd == G5_TIME_YMD){submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');}
					break;
								
				case 'Done' :
							
					echo "<div>";
					echo $pw_rld.'<br>PWHT : '.$pw_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;
								
				default :
							
					echo $pw_rqd.'<br>PWHT : '.$pw_rlt;
					if ($pw_rqd == G5_TIME_YMD && !$ndr){submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;

			};
						
			break;

		case 3:
			switch ($pw_rlt) {
							
				case '' :

					echo 'PWHT';submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', '');
					break;
							
				case 'Request' :
							
					echo "<div>";
					echo $pw_rqd.'<br>PWHT : '.$pw_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'confirm', '');
					echo '</div>';
					break;
						
				case 'Done' :
							
					echo "<div>";
					echo $pw_rqd.'<br>PWHT : '.$pw_rlt;
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_job', '');
					echo "</div><div style='float:left;width:32%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;

				default :
							
					echo $pw_rqd.'<br>PWHT : '.$pw_rlt;
					if (!$ndr){submit_text($sno, 'PWHT', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;
			}
			break;
					
		default : break;
	}
}



//NDE
function insp_nde($sno, $dwg, $jnt, $mem_insp, $mem_nick, $nde_type, $nde_rqd, $nde_rlt) {

	switch($mem_insp){

		case 1:

			switch ($nde_rlt) {
							
				case '' :	echo 'Not yet<br>NDE Requested'; break;
							
				case 'Request' :
							
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'confirm', '');
					break;

				case 'Done' :

					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_job', '');
					break;

				default :
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					break;
			}
			break;
							
		case 2:
					
			switch ($nde_rlt) {
							
				case '' :
								
					echo "<div>NDE Request</div><div style='float:left;width:25%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'RT');
					echo "</div><div style='float:left;width:24%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'MT');
					echo "</div><div style='float:left;width:24%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'PT');
					echo "</div><div style='float:left;width:25%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'PAUT');
					echo "</div>";
					break;
								
				case 'Request' :
							
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					if ($nde_rqd == G5_TIME_YMD){submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');}
					break;
								
				case 'Done' :
							
					echo "<div>";
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;
								
				default :
							
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					if ($nde_rqd == G5_TIME_YMD){submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');}
					break;
			};
						
			break;

		case 3:
			switch ($nde_rlt) {
							
				case '' :

					echo "<div>NDE Request</div><div style='float:left;width:25%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'RT');
					echo "</div><div style='float:left;width:24%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'MT');
					echo "</div><div style='float:left;width:24%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'PT');
					echo "</div><div style='float:left;width:25%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'request', 'PAUT');
					echo "</div>";
					break;
							
				case 'Request' :
							
					echo "<div>";
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt."</div><div style='float:left;width:50%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_req', '');
					echo "</div><div style='float:left;width:49%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'confirm', '');
					echo '</div>';
					break;
						
				case 'Done' :
							
					echo "<div>";
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_job', '');
					echo "</div><div style='float:left;width:32%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Accept', '');
					echo "</div><div style='float:left;width:33%'>";
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'Reject', '');
					echo '</div>';
					break;

				default :
							
					echo $nde_rqd.'<br>'.$nde_type.' : '.$nde_rlt;
					submit_text($sno, 'NDE', $dwg, $jnt, $mem_nick, G5_TIME_YMDHIS, 'cancle_eva', '');
					break;
			}
			break;
					
		default : break;
	}
}	



// file download
function pcs_file_download($type, $name) {

	switch ($type) {
		case 'dwg'	: $filepath = G5_URL.'/pcs_data/dwg/pdf/'.$name.'.pdf';		break;
		case 'pkg'	: $filepath = G5_URL.'/pcs_data/pkg/scaned/'.$name.'.pdf';		break;
		default		: $filepath = PCS_REP_PDF.'/'.$type.'/'.$name.'.pdf';	break;
	}


	$filesize = filesize($filepath);
	$path_parts = pathinfo($filepath);
	$filename = $path_parts['basename'];
	$extension = $path_parts['extension'];
 
	header("Pragma: public");
	header("Expires: 0");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $filesize");
 
	ob_clean();
	flush();
	readfile($filepath);
}


// 폴더 내 파일 리스트 불러오는 함수
function filesindir($tdir) {

	if($dh = opendir($tdir)) {
		$files = array();
		$in_files = array();
		
		while($a_file = readdir ($dh)){
			if($a_file[0] != '.'){
				if(is_dir($tdir.'/'.$a_file)){
					$in_files = filesindir($tdir.'/'.$a_file);
					if(is_array($in_files)) $files = array_merge ($files, $in_files);
				}
				else {
					array_push($files, $tdir.'/'.$a_file);
				}
			}
		}
		closedir($dh);
		return $files;
	}
}


// base64문자열에서 파일생성
function base64_to_img($base64_string, $image_fiilename) {
	$img_data = explode(',',$base64_string);

	$fp = fopen($image_fiilename,'wb');
	fwrite($fp, base64_decode($img_data[1]));
	fclose($fp);
	
	return $image_fiilename;
}


// 원격서버 파일유무 확인
function FileExitsCheck($Qt) 
{ 
	$tempo = curl_init(); 
	curl_setopt($tempo, CURLOPT_URL,$Qt); // don't download content 
	curl_setopt($tempo, CURLOPT_NOBODY, 1); 
	curl_setopt($tempo, CURLOPT_FAILONERROR, 1); 
	curl_setopt($tempo, CURLOPT_RETURNTRANSFER, 1); 
	return (curl_exec($tempo)!==FALSE)? true : false; 
} 


// PNG 파일크기 축소
function convertPNGto8bitPNG($sourcePath, $destPath) {
    $srcimage = imagecreatefrompng($sourcePath);
    list($width, $height) = getimagesize($sourcePath);
    $img = imagecreatetruecolor($width, $height);
    $bga = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagecolortransparent($img, $bga);
    imagefill($img, 0, 0, $bga);
    imagecopy($img, $srcimage, 0, 0, 0, 0, $width, $height);
    imagetruecolortopalette($img, false, 255);
    imagesavealpha($img, true);
    imagepng($img, $destPath);
    imagedestroy($img);
}


// 폴더 내 파일명 배열반환
function folder_file_array($f_path,$f_ext,$f_data) {

	$dir = $f_path;
	$handle  = opendir($dir);
	$files = array();
 
	while (false !== ($filename = readdir($handle))) {
		if($filename == "." || $filename == ".."){continue;}
		if(is_file($dir . "/" . $filename)){
			if(strtolower(substr(strrchr($filename, "."), 1))==$f_ext){
				$filename = substr($filename, 0, strrpos($filename, "."));
				$files[] = $filename;
			}
		}
	}
 	closedir($handle);
	return $files;
}


	function add_tr($item_arr, $item_color, $item_text, $item_field){
		echo '<tr>';
		$qty=count($item_arr);
		if($qty>0){ echo '<td class="main_td" colspan=6 style="background-color: '.$item_color.'; height:50px;"><b>'.$item_text.'</td></tr>';
	
			$j=0;
			for($i=0;$i<$qty-1;$i++){
				$item_name =  explode("_",$item_arr[$i]);
				if($item_field){
					$query_inc_dwg = "SELECT wr_id FROM ".G5_TABLE_PREFIX."write_".$item_field." WHERE wr_subject = '".$item_name[0]."'";
					$sql_inc_dwg = sql_query ($query_inc_dwg);
					$sql_inc_dwg_arr = sql_fetch_array ($sql_inc_dwg);
				}
				echo '<td class="jnt_td" style="height:80px;font-size:18px;">';

				if($item_arr[$i]){
					$j++;
			
					if($sql_inc_dwg_arr['wr_id']){ echo '<a href='.G5_BBS_URL.'/board.php?bo_table='.$item_field.'&wr_id='.$sql_inc_dwg_arr['wr_id'].'> <b>'.$item_arr[$i].'</b></a>';}
					else {echo '<mark>'.$item_arr[$i].'</mark>';}
				}
				echo'</a></td>';
				if($j%6==0){echo'</tr><tr>';}	
		
			}
			if($j%6){for($k=0;$k<6-($j%6);$k++){ echo '<td class="main_td" style="height:80px;font-size:18px;"></td>';}	}
		}
		echo '</tr>';
	}

function count_files($dir){
	clearstatcache();
	if (!is_dir($dir)) return -1;
	if (!preg_match("&/$&", $dir)) $dir .= "/"; // '/'로 끝나게 한다

	return count(glob($dir."*.pdf", GLOB_NOSORT)); - count(glob($dir."*", GLOB_ONLYDIR));
}

function rep_view($no,$pdf_folder,$insp_date,$rep_no){
	if(file_exists(PCS_DATA.'/'.$pdf_folder.'/'.$rep_no.'.pdf')) {
		echo $insp_date.'<br>';
		echo '<a href = "javascript:document.submit_for'.$no.'.submit()"> <b> '.$rep_no.' </b>';
		echo '<form name="submit_for'.$no.'" action="'.PCS_WPV_URL.'/viewer.php" method="post" target="'.$no.'" onSubmit="return doSumbit()">';
		echo '<input type="hidden" name="folder" value="'.$pdf_folder.'">';
		echo '<input type="hidden" name="file" value="'.$rep_no.'">';
		echo '</form>';
	}
	else {
		echo $insp_date.'<br>'.$rep_no;
	}

}

function z_rem_jno($j_no){
	preg_match('/[A-Z1-9][A-Z0-9]*/',$j_no,$mts);
	return $mts[0];
}

function remove_spe_char($spe_string){
	$spe_string = preg_replace('/[ #\&\+%@=\/\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i', '', $spe_string);
	return $spe_string;
}


function viewPDF($fmname,$folder,$drawing,$revision,$tb=''){
	$formstring = '<form name="'.$fmname.'" action="'.PCS_WPV_URL.'/viewer.php" method="post" target="'.$fmname.'" onSubmit="return doSumbit()"> ';
	if($folder=='pkg'){
		if(file_exists(PCS_DWG_PKG.'/'.$tb.'/pkg_'.$drawing.'_'.$revision.'.pdf')){
			$formstring .= '<input type="hidden" name="folder" value="dwg_pkg/'.$tb.'">';
			$formstring .= '<input type="hidden" name="file" value="'.$folder.'_'.$drawing.'">';
			$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
		}
		elseif(file_exists(PCS_DWG_ISO.'/'.$drawing.'/fab_'.$drawing.'_'.$revision.'.pdf')){
			$formstring .= '<input type="hidden" name="folder" value="dwg_iso/'.$drawing.'">';
			$formstring .= '<input type="hidden" name="file" value="fab_'.$drawing.'">';
			$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
		}
		else {
			$formstring .= '<input type="hidden" name="folder" value="dwg_iso/'.$drawing.'">';
			$formstring .= '<input type="hidden" name="file" value="'.$drawing.'">';
			$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
		}
	}
	elseif($folder=='plan'){
			$formstring .= '<input type="hidden" name="folder" value="plan/piping">';
			$formstring .= '<input type="hidden" name="file" value="'.$drawing.'">';
			$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
		
	}
	elseif($folder=='work'){
			$formstring .= '<input type="hidden" name="folder" value="plan/working">';
			$formstring .= '<input type="hidden" name="file" value="'.$drawing.'">';
	}
	elseif($folder=='shop'){
			$formstring .= '<input type="hidden" name="folder" value="dwg_iso/'.$drawing.'">';
			$formstring .= '<input type="hidden" name="file" value="'.$revision.'">';
	}
	elseif($folder=='pnid'){
		if(file_exists(PCS_PNID_MST.'/master_'.$drawing.'_'.$revision.'.pdf')){
			$formstring .= '<input type="hidden" name="folder" value="pnid/master">';
			$formstring .= '<input type="hidden" name="file" value="master_'.$drawing.'">';
			$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
		}
		else {
			$formstring .= '<input type="hidden" name="folder" value="pnid">';
			$formstring .= '<input type="hidden" name="file" value="'.$drawing.'">';
			$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
		}
	}
	elseif(file_exists(PCS_DWG_ISO.'/'.$drawing.'/'.$folder.'_'.$drawing.'_'.$revision.'.pdf')){
		$formstring .= '<input type="hidden" name="folder" value="dwg_iso/'.$drawing.'">';
		$formstring .= '<input type="hidden" name="file" value="'.$folder.'_'.$drawing.'">';
		$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
	}
	else {
		$formstring .= '<input type="hidden" name="folder" value="dwg_iso/'.$drawing.'">';
		$formstring .= '<input type="hidden" name="file" value="'.$drawing.'">';
		$formstring .= '<input type="hidden" name="rev" value="'.$revision.'">';
	}
	$formstring .= '</form>';
	echo $formstring;
}


function GenerateString($length)  
{  
    $characters  = "0123456789";  
    $characters .= "abcdefghijklmnopqrstuvwxyz";  
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
    $characters .= "_";  
      
    $string_generated = "";  
      
    $nmr_loops = $length;  
    while ($nmr_loops--)  
    {  
        $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];  
    }  
      
    return $string_generated;  
}


function compass($x,$y){
	if($x==0 ){ if($y>0){return 0;} else {return 180;} } 
	return ($x < 0)
	? rad2deg(atan2($x,$y)) + 360
	: rad2deg(atan2($x,$y));
}


	function jointCheck($qty,$jnt,$arr){
		
		for($i=0;$i<$qty;$i++){
			if($arr[$i]==$jnt) {return $i+1;}
		}
	}
	
	

function PDFjointmarking($PDF, $curr_dwg, $jointinfo, $jnt_desc, $jnt_arr){

	
	$color_green = array(141,253,115);
	$color_red = array(255,0,0);
	$color_blue = array(0,0,255);
	$color_violet = array(204,0,204);
	$color_black = array(0,0,0);
	
	if($jnt_arr){$jnt_qty = count($jnt_arr);}
	
	if($jnt_desc){
		$dwg_mark_line = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_black);
		$PDF->SetTextColor($color_black);
	}
	else {
		$dwg_mark_line = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_blue);
		$PDF->SetTextColor($color_blue);
	}

	$pkg_mark_line = array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_green);
	$pkg_page_line = array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_red);

		$jointcoor_val = explode(',',$jointinfo);
		
		$xf = round($jointcoor_val[1]/5);
		$yf = round($jointcoor_val[2]/5);
		$xt = round($jointcoor_val[3]/5);
		$yt = round($jointcoor_val[4]/5);
		
		$detail_mark = jointCheck($jnt_qty,$jointcoor_val[14],$jnt_arr);
	
		if($jointcoor_val[12]=='ACT'){
			
			

			
			if($detail_mark){
				$dwg_mark_line = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color_violet);
			}

				



			switch ($jointcoor_val[6]) {
				case 'shop' :
					$PDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line);
					$PDF->Circle($xt,$yt, 2.6,0,360,'F',$dwg_mark_line,array(255,255,255));
					$PDF->Circle($xt,$yt, 2.6);
					if(strlen($jointcoor_val[10])<2){
						$PDF->SetFont('helvetica', '', 10);
						$text_x = $xt-2;
						$text_y = $yt-2.2;
					}
					else if(strlen($jointcoor_val[10])<3){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt-2.7;
						$text_y = $yt-1.8;
					}
					else {
						$PDF->SetFont('helvetica', '', 6);
						$text_x = $xt-2.7;
						$text_y = $yt-1.4;
					}
					$PDF->Text($text_x,$text_y,$jointcoor_val[10]);
				break;

				case 'field' :
					$PDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line);
					$PDF->Circle(-100,-100, 2.6,0,360,'F',$dwg_mark_line,array(255,255,255));
					$PDF->RegularPolygon($xt,$yt,3.2,6,30, 1, 'DF', array('all' => $dwg_mark_line), array(255, 255, 255), 'F', array(255, 255, 255));
					if(strlen($jointcoor_val[10])<2){
						$PDF->SetFont('helvetica', '', 10);
						$text_x = $xt-2;
						$text_y = $yt-2.2;
					}
					else if(strlen($jointcoor_val[10])<3){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt-2.7;
						$text_y = $yt-1.8;
					}
					else {
						$PDF->SetFont('helvetica', '', 6);
						$text_x = $xt-2.7;
						$text_y = $yt-1.4;
					}
					$PDF->Text($text_x,$text_y,$jointcoor_val[10]);
				break;

				case 'bolt' :
					$PDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line);
					$PDF->RegularPolygon($xt,$yt,3,6,30, 1, 'DF', array('all' => $dwg_mark_line), array(255, 255, 255), 'F', array(255, 255, 255));
					if(strlen($jointcoor_val[10])<2){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt-2.7;
						$text_y = $yt-1.8;
					}
					else if(strlen($jointcoor_val[10])<3){
						$PDF->SetFont('helvetica', '', 6);
						$text_x = $xt-2.9;
						$text_y = $yt-1.4;
					}
					$PDF->Text($text_x,$text_y,'B'.$jointcoor_val[10]);
				break;
				
				case 'thread' :
					$PDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line);
					$PDF->RegularPolygon($xt,$yt,3,6,30, 1, 'DF', array('all' => $dwg_mark_line), array(255, 255, 255), 'F', array(255, 255, 255));
					if(strlen($jointcoor_val[10])<2){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt-2.7;
						$text_y = $yt-1.8;
					}
					else if(strlen($jointcoor_val[10])<3){
						$PDF->SetFont('helvetica', '', 6);
						$text_x = $xt-2.9;
						$text_y = $yt-1.4;
					}
					$PDF->Text($text_x,$text_y,'T'.$jointcoor_val[10]);
				break;
				
				case 'support' :
					$PDF->Line($xf,$yf,$xt,$yt,$dwg_mark_line);
					$PDF->RegularPolygon($xt,$yt,3,6,30, 1, 'DF', array('all' => $dwg_mark_line), array(255, 255, 255), 'F', array(255, 255, 255));
					if(strlen($jointcoor_val[10])<2){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt-2.7;
						$text_y = $yt-1.8;
					}
					else if(strlen($jointcoor_val[10])<3){
						$PDF->SetFont('helvetica', '', 6);
						$text_x = $xt-2.9;
						$text_y = $yt-1.4;
					}
					$PDF->Text($text_x,$text_y,'S'.$jointcoor_val[10]);
				break;
				
				case 'spool' :
					$angle = compass($xt-$xf,$yt-$yf);
					if($angle<90)		{$FX = $xt-18 ; $FY = $yt-3 ; $TX = $xt-18 ; $TY = $yt-3 ;}
					else if($angle<180)	{$FX = $xt-18 ; $FY = $yt+1 ; $TX = $xt-18 ; $TY = $yt-3 ;}
					else if($angle<270)	{$FX = $xt+22 ; $FY = $yt+1 ; $TX = $xt-18 ; $TY = $yt-3 ;}
					else if($angle<360)	{$FX = $xt+22 ; $FY = $yt-3 ; $TX = $xt-18 ; $TY = $yt-3 ;}
					

					if($jointcoor_val[10]=='R'){ $splno = 'RANDOM PIPE  <12000>';} else {$splno = $jointcoor_val[5].'_'.$jointcoor_val[10];}

					if(strlen($jointcoor_val[10])<2){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt-17;
						$text_y = $yt-3;
						$jointcoor_val[10] = '0'.$jointcoor_val[10];
					}
					else if(strlen($jointcoor_val[10])<3){
						$PDF->SetFont('helvetica', '', 8);
						$text_x = $xt;
						$text_y = $yt;
					}
					
					
					$PDF->Line($xf,$yf,$FX,$FY,$dwg_mark_line);
					if($curr_dwg==$jointcoor_val[5]){$PDF->Rect($TX,$TY,40,4, 'D', array('all' => $dwg_mark_line));}
					else {$PDF->Polygon(array($TX,$TY,$TX-2,$TY+2,$TX,$TY+4,$TX+40,$TY+4,$TX+42,$TY+2,$TX+40,$TY));}
					
					$PDF->Text($text_x,$text_y,$splno);
				break;

				default :	break;
			}
			if($detail_mark){
				$PDF->StartTransform();
				
				if(($yt-$yf)<0){
					$rota = compass($xt-$xf,$yt-$yf)+180;
					$rotY = -7;
				}
				else {
					$rota = compass($xt-$xf,$yt-$yf);
					$rotY = 2;
				}
				
				$PDF->Rotate($rota, $xt, $yt);
				$PDF->SetFont('helvetica', '', 10);
				$PDF->SetTextColor(204,0,204);
				$PDF->Translate(-6, $rotY);
				$PDF->Text($xt,$yt,$jnt_desc[$detail_mark-1]);
				$PDF->StopTransform();
				
//				$PDF->Annotation($xt, $yt, 10, 10, "Text annotation example\naccented letters test: aeeiou", array('Subtype'=>'Text', 'Name' => 'Comment', 'T' => 'title example', 'Subj' => 'example', 'C' => array(255, 255, 0)));
			}
		}
}


function pcsqrcode($PDF,$set,$dwg,$sd){
	$query_dwg_spec = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_dwgconfig';
	$sql_dwg_spec = sql_query ($query_dwg_spec);
	$sql_dwg_spec_array = sql_fetch_array ($sql_dwg_spec);
	$qr_x = $sql_dwg_spec_array['qr_x']/5;
	$qr_y = $sql_dwg_spec_array['qr_y']/5;
	$qr_size = $sql_dwg_spec_array['qr_size']/5;
	$sh_x = $sql_dwg_spec_array['sh_x']/5;
	$sh_y = $sql_dwg_spec_array['sh_y']/5;
	
	

	$style = array(
	'border' => false,
	'vpadding' => 'auto',
	'hpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false,
	'module_width' => 1,
	'module_height' => 1
	);
	
	$PDF->write2DBarcode($set.$dwg, 'QRCODE,L', $qr_x, $qr_y, $qr_size, $qr_size, $style, 'N');
	$style = array(
	'border' => false,
	'vpadding' => 'auto',
	'hpadding' => 'auto',
	'fgcolor' => array(255,255,255),
	'bgcolor' => false,
	'module_width' => 1,
	'module_height' => 1
	);
	
	$PDF->write2DBarcode($set.$dwg, 'QRCODE,L', $qr_x+30, $qr_y-12, 0, 0, $style, 'N');
	
	$url = G5_URL.'/bbs/board.php?bo_table=iso&stx='.$dwg;
	$html = <<<EOD
	<p><a href="$url">$sd</a></p>
EOD;


	$PDF->writeHTMLCell(0, 0, $sh_x, $sh_y, $html, 0, 1, 0, true, '', true);
	
	
}


function pcsthumbnail($filename, $source_path, $target_path, $thumb_width, $thumb_height, $is_create, $is_crop=false, $crop_mode='center', $is_sharpen=false, $um_value='80/0.5/3')
{
    global $g5;

    if(!$thumb_width && !$thumb_height)
        return;

    $source_file = "$source_path/$filename";

    if(!is_file($source_file)) // 원본 파일이 없다면
        return;

    $size = @getimagesize($source_file);
    if($size[2] < 1 || $size[2] > 3) // gif, jpg, png 에 대해서만 적용
        return;

    if (!is_dir($target_path)) {
        @mkdir($target_path, G5_DIR_PERMISSION);
        @chmod($target_path, G5_DIR_PERMISSION);
    }

    // 디렉토리가 존재하지 않거나 쓰기 권한이 없으면 썸네일 생성하지 않음
    if(!(is_dir($target_path) && is_writable($target_path)))
        return '';

    // Animated GIF는 썸네일 생성하지 않음
    if($size[2] == 1) {
        if(is_animated_gif($source_file))
            return basename($source_file);
    }

    $ext = array(1 => 'gif', 2 => 'jpg', 3 => 'png');

    $thumb_filename = preg_replace("/\.[^\.]+$/i", "", $filename); // 확장자제거
    $thumb_file = "$target_path/thumb_{$thumb_filename}.".$ext[$size[2]];

    $thumb_time = @filemtime($thumb_file);
    $source_time = @filemtime($source_file);

    if (file_exists($thumb_file)) {
        if ($is_create == false && $source_time < $thumb_time) {
            return basename($thumb_file);
        }
    }

    // 원본파일의 GD 이미지 생성
    $src = null;
    $degree = 0;

    if ($size[2] == 1) {
        $src = @imagecreatefromgif($source_file);
        $src_transparency = @imagecolortransparent($src);
    } else if ($size[2] == 2) {
        $src = @imagecreatefromjpeg($source_file);

        if(function_exists('exif_read_data')) {
            // exif 정보를 기준으로 회전각도 구함
            $exif = @exif_read_data($source_file);
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $degree = 90;
                        break;
                    case 3:
                        $degree = 180;
                        break;
                    case 6:
                        $degree = -90;
                        break;
                }

                // 회전각도 있으면 이미지 회전
                if($degree) {
                    $src = imagerotate($src, $degree, 0);

                    // 세로사진의 경우 가로, 세로 값 바꿈
                    if($degree == 90 || $degree == -90) {
                        $tmp = $size;
                        $size[0] = $tmp[1];
                        $size[1] = $tmp[0];
                    }
                }
            }
        }
    } else if ($size[2] == 3) {
        $src = @imagecreatefrompng($source_file);
        @imagealphablending($src, true);
    } else {
        return;
    }

    if(!$src)
        return;

    $is_large = true;
    // width, height 설정

    if($thumb_width) {
        if(!$thumb_height) {
            $thumb_height = round(($thumb_width * $size[1]) / $size[0]);
        } else {
            if($size[0] < $thumb_width || $size[1] < $thumb_height)
                $is_large = false;
        }
    } else {
        if($thumb_height) {
            $thumb_width = round(($thumb_height * $size[0]) / $size[1]);
        }
    }

    $dst_x = 0;
    $dst_y = 0;
    $src_x = 0;
    $src_y = 0;
    $dst_w = $thumb_width;
    $dst_h = $thumb_height;
    $src_w = $size[0];
    $src_h = $size[1];

    $ratio = $dst_h / $dst_w;

    if($is_large) {
        // 크롭처리
        if($is_crop) {
            switch($crop_mode)
            {
                case 'center':
                    if($size[1] / $size[0] >= $ratio) {
                        $src_h = round($src_w * $ratio);
                        $src_y = round(($size[1] - $src_h) / 2);
                    } else {
                        $src_w = round($size[1] / $ratio);
                        $src_x = round(($size[0] - $src_w) / 2);
                    }
                    break;
                default:
                    if($size[1] / $size[0] >= $ratio) {
                        $src_h = round($src_w * $ratio);
                    } else {
                        $src_w = round($size[1] / $ratio);
                    }
                    break;
            }

            $dst = imagecreatetruecolor($dst_w, $dst_h);

            if($size[2] == 3) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            } else if($size[2] == 1) {
                $palletsize = imagecolorstotal($src);
                if($src_transparency >= 0 && $src_transparency < $palletsize) {
                    $transparent_color   = imagecolorsforindex($src, $src_transparency);
                    $current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                    imagefill($dst, 0, 0, $current_transparent);
                    imagecolortransparent($dst, $current_transparent);
                }
            }
        } else { // 비율에 맞게 생성
            $dst = imagecreatetruecolor($dst_w, $dst_h);
            $bgcolor = imagecolorallocate($dst, 255, 255, 255); // 배경색

            if ( !((defined('G5_USE_THUMB_RATIO') && false === G5_USE_THUMB_RATIO) || (defined('G5_THEME_USE_THUMB_RATIO') && false === G5_THEME_USE_THUMB_RATIO)) ){
                if($src_w > $src_h) {
                    $tmp_h = round(($dst_w * $src_h) / $src_w);
                    $dst_y = round(($dst_h - $tmp_h) / 2);
                    $dst_h = $tmp_h;
                } else {
                    $tmp_w = round(($dst_h * $src_w) / $src_h);
                    $dst_x = round(($dst_w - $tmp_w) / 2);
                    $dst_w = $tmp_w;
                }
            }

            if($size[2] == 3) {
                $bgcolor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefill($dst, 0, 0, $bgcolor);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            } else if($size[2] == 1) {
                $palletsize = imagecolorstotal($src);
                if($src_transparency >= 0 && $src_transparency < $palletsize) {
                    $transparent_color   = imagecolorsforindex($src, $src_transparency);
                    $current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                    imagefill($dst, 0, 0, $current_transparent);
                    imagecolortransparent($dst, $current_transparent);
                } else {
                    imagefill($dst, 0, 0, $bgcolor);
                }
            } else {
                imagefill($dst, 0, 0, $bgcolor);
            }
        }
    } else {
        $dst = imagecreatetruecolor($dst_w, $dst_h);
        $bgcolor = imagecolorallocate($dst, 255, 255, 255); // 배경색

        if ( ((defined('G5_USE_THUMB_RATIO') && false === G5_USE_THUMB_RATIO) || (defined('G5_THEME_USE_THUMB_RATIO') && false === G5_THEME_USE_THUMB_RATIO)) ){
            //이미지 썸네일을 비율 유지하지 않습니다.  (5.2.6 버전 이하에서 처리된 부분과 같음)

            if($src_w < $dst_w) {
                if($src_h >= $dst_h) {
                    $dst_x = round(($dst_w - $src_w) / 2);
                    $src_h = $dst_h;
                    if( $dst_w > $src_w ){
                        $dst_w = $src_w;
                    }
                } else {
                    $dst_x = round(($dst_w - $src_w) / 2);
                    $dst_y = round(($dst_h - $src_h) / 2);
                    $dst_w = $src_w;
                    $dst_h = $src_h;
                }
            } else {
                if($src_h < $dst_h) {
                    $dst_y = round(($dst_h - $src_h) / 2);
                    $dst_h = $src_h;
                    $src_w = $dst_w;
                }
            }

        } else {
            //이미지 썸네일을 비율 유지하며 썸네일 생성합니다.
            if($src_w < $dst_w) {
                if($src_h >= $dst_h) {
                    if( $src_h > $src_w ){
                        $tmp_w = round(($dst_h * $src_w) / $src_h);
                        $dst_x = round(($dst_w - $tmp_w) / 2);
                        $dst_w = $tmp_w;
                    } else {
                        $dst_x = round(($dst_w - $src_w) / 2);
                        $src_h = $dst_h;
                        if( $dst_w > $src_w ){
                            $dst_w = $src_w;
                        }
                    }
                } else {
                    $dst_x = round(($dst_w - $src_w) / 2);
                    $dst_y = round(($dst_h - $src_h) / 2);
                    $dst_w = $src_w;
                    $dst_h = $src_h;
                }
            } else {
                if($src_h < $dst_h) {
                    if( $src_w > $dst_w ){
                        $tmp_h = round(($dst_w * $src_h) / $src_w);
                        $dst_y = round(($dst_h - $tmp_h) / 2);
                        $dst_h = $tmp_h;
                    } else {
                        $dst_y = round(($dst_h - $src_h) / 2);
                        $dst_h = $src_h;
                        $src_w = $dst_w;
                    }
                }
            }
        }

        if($size[2] == 3) {
            $bgcolor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $bgcolor);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        } else if($size[2] == 1) {
            $palletsize = imagecolorstotal($src);
            if($src_transparency >= 0 && $src_transparency < $palletsize) {
                $transparent_color   = imagecolorsforindex($src, $src_transparency);
                $current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($dst, 0, 0, $current_transparent);
                imagecolortransparent($dst, $current_transparent);
            } else {
                imagefill($dst, 0, 0, $bgcolor);
            }
        } else {
            imagefill($dst, 0, 0, $bgcolor);
        }
    }

    imagecopyresampled($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

    // sharpen 적용
    if($is_sharpen && $is_large) {
        $val = explode('/', $um_value);
        UnsharpMask($dst, $val[0], $val[1], $val[2]);
    }

    if($size[2] == 1) {
        imagegif($dst, $thumb_file);
    } else if($size[2] == 3) {
        if(!defined('G5_THUMB_PNG_COMPRESS'))
            $png_compress = 5;
        else
            $png_compress = G5_THUMB_PNG_COMPRESS;

        imagepng($dst, $thumb_file, $png_compress);
    } else {
        if(!defined('G5_THUMB_JPG_QUALITY'))
            $jpg_quality = 90;
        else
            $jpg_quality = G5_THUMB_JPG_QUALITY;

        imagejpeg($dst, $thumb_file, $jpg_quality);
    }

    chmod($thumb_file, G5_FILE_PERMISSION); // 추후 삭제를 위하여 파일모드 변경

    imagedestroy($src);
    imagedestroy($dst);

    return basename($thumb_file);
}

function pcs_sfl_select_options($sfl, $sel){

    $str = '';
    $str .= '<option value="wr_subject" '.get_selected($sfl, 'wr_subject', true).'>'.$sel.'</option>';
    if ($sel=='Drawing information'){$str .= '<option value="wr_content" '.get_selected($sfl, 'wr_content').'>Shop drawing no.</option>';}

    return run_replace('get_board_sfl_select_options', $str, $sfl);
}
?>