<?php 
ini_set('display_errors', '0');
	$query_ref_pkg = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_package WHERE pkg_no = "'.$view['wr_subject'].'"';
	$sql_ref_pkg = sql_query ($query_ref_pkg);
	$sql_ref_pkg_arr = sql_fetch_array ($sql_ref_pkg);
	
	$temptbl1 = GenerateString(15);
	
	$query_view_punch_create = 
		'CREATE VIEW '.$temptbl1.' AS SELECT *
		FROM '.G5_TABLE_PREFIX.'pcs_info_punch
		WHERE pkg_no = "'.$view['wr_subject'].'" ORDER BY s_no';
	sql_query ($query_view_punch_create);
			
	$query_p_count = 'SELECT MAX(s_no) FROM '.$temptbl1.' WHERE pkg_no = "'.$view['wr_subject'].'"';	
	$sql_p_count = pcs_sql_value($query_p_count);	

	if($_POST['pkg_cancle']){$query_step = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_pkg_stat SET '.$_POST['pkg_cancle'].' = "0000-00-00" WHERE pkg_no = "'.$view['wr_subject'].'"';	sql_query ($query_step);}
	if($_POST['pkg_step']){$query_step = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_pkg_stat SET '.$_POST['pkg_step'].' = "'.G5_TIME_YMD.'" WHERE pkg_no = "'.$view['wr_subject'].'"';	sql_query ($query_step);}

	
	
	$query_Apunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "A" AND dwg_no = "GENERAL PUNCH"';
	$gen_Apunch_issue = pcs_sql_value($query_Apunch_issue);

	$query_Bpunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "B" AND dwg_no = "GENERAL PUNCH"';
	$gen_Bpunch_issue = pcs_sql_value($query_Bpunch_issue);

	$query_Cpunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "C" AND dwg_no = "GENERAL PUNCH"';
	$gen_Cpunch_issue = pcs_sql_value($query_Cpunch_issue);

	$query_Apunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "A" AND dwg_no = "GENERAL PUNCH" AND cleared_date != "0000-00-00 00:00:00"';
	$gen_Apunch_clear = pcs_sql_value($query_Apunch_clear);
	
	$query_Bpunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "B" AND dwg_no = "GENERAL PUNCH" AND cleared_date != "0000-00-00 00:00:00"';
	$gen_Bpunch_clear = pcs_sql_value($query_Bpunch_clear);

	$query_Cpunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "C" AND dwg_no = "GENERAL PUNCH" AND cleared_date != "0000-00-00 00:00:00"';
	$gen_Cpunch_clear = pcs_sql_value($query_Cpunch_clear);
	
	$Apunch_issue = $gen_Apunch_issue;			$Apunch_clear = $gen_Apunch_clear;
	$Bpunch_issue = $gen_Bpunch_issue;			$Bpunch_clear = $gen_Bpunch_clear;
	$Cpunch_issue = $gen_Cpunch_issue;			$Cpunch_clear = $gen_Cpunch_clear;

if(!G5_IS_MOBILE) {
	
	if($_POST['num_app']){
		$query_approve = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_pkg_coor SET dwg_state = "'.$_POST['num_app'].'" WHERE dwg_no = "'.$_POST['dwg'].'" AND pkg_no = "'.$_POST['pkg'].'" AND latest = "Y"';
		sql_query ($query_approve);
	}

	$sql_pcs_pkg_arr = pcs_package_insert($sql_ref_pkg_arr);

?>

<div style="overflow:hidden;height:auto;">
<table class="main">
<caption> SPECIFICATION </caption>
<tbody>
<tr>
<td class="main_td td_sub_pkg1" style="width:400px;"> PKG No. </td>
<td class="main_td td_sub_pkg1"> Test 시점 </td>
<td class="main_td td_sub_pkg1"> 고압/일반 </td>
<td class="main_td td_sub_pkg1"> Test type </td>
<td class="main_td td_sub_pkg1"> Test pressure </td>
</tr>

<tr>
<td class="main_td"><?php echo $sql_ref_pkg_arr['pkg_no']; ?></td>
<td class="main_td"><?php echo $sql_ref_pkg_arr['unit']; ?></td>
<td class="main_td"><?php echo $sql_ref_pkg_arr['ag_ug']; ?></td>
<td class="main_td"><?php echo $sql_ref_pkg_arr['test_type']; ?></td>
<td class="main_td"><?php echo $sql_ref_pkg_arr['pressure']; ?></td>
</tr>
</tbody>
</table>

<p>&nbsp;<p>&nbsp;
<table class="main">
<caption> P&ID DRAWING STATUS </span> </caption>
<tbody>
<tr>
<td class="main_td td_sub_pkg2">No.</td>
<td class="main_td td_sub_pkg2">P&ID No.</td>
<td class="main_td td_sub_pkg2">View P&ID</td>
<td class="main_td td_sub_pkg2">Rev No.</td>
</tr>

<?php
	$pkg_th = array ('Review','Line check','A-punch clear','Test Accept');
	$pkg_db = array ('review','line_chk','punch_a','h_test','punch_b');
	$db_cnt = count($pkg_th);
	
	$query_pkg_state = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pkg_stat WHERE pkg_no = "'.$view['wr_subject'].'"';
	$sql_pkg_state = sql_query ($query_pkg_state);
	$sql_pkg_state_arr = sql_fetch_array ($sql_pkg_state);

	$query_ref_pnid = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_coor LIKE "%,'.$view['wr_subject'].',%" GROUP BY pnid_no';
	$sql_ref_pnid = sql_query ($query_ref_pnid);

	$pnidQty = 0;
	
	while ($sql_ref_pnid_arr = sql_fetch_array ($sql_ref_pnid)) {
		$pnidQty++;
		
		$query_pkgby_pnid_ref = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_pnid WHERE wr_subject = "'.$sql_ref_pnid_arr['pnid_no'].'"';
		$sql_pkgby_pnid_ref = sql_query ($query_pkgby_pnid_ref);
		$sql_pkgby_pnid_ref_arr = sql_fetch_array ($sql_pkgby_pnid_ref);

?>
<tr>
<td class="main_td"> <?php echo $pnidQty; ?> </td>
<td class="main_td"> <a href=<?php echo G5_URL.'/bbs/board.php?bo_table=pnid&wr_id='.$sql_pkgby_pnid_ref_arr['wr_id']; ?> target='_self'><?php echo $sql_ref_pnid_arr['pnid_no']; ?></a> </td>
<td class="main_td"> 
<a href = 'javascript:document.pnid<?php echo $pnidQty; ?>.submit()'><b>View</b></a>
	<form name="pnid<?php echo $pnidQty; ?>" action="<?php echo PCS_CORE_URL; ?>/pcs_mark_masterpnid.php" method="post" target="<?php echo $view['wr_subject']; ?>" onSubmit="return doSumbit()"> 
	<input type="hidden" name="mapped_pnid" value="<?php echo $sql_ref_pnid_arr['pnid_no']; ?>">
	<input type="hidden" name="rev_pnid" value="<?php echo $sql_ref_pnid_arr['rev_no']; ?>">
	<input type="hidden" name="pkg_no" value="<?php echo $view['wr_subject']; ?>">
	</form>
</td>
<td class="main_td"> <?php echo $sql_ref_pnid_arr['rev_no']; ?> </a> </td>
</tr>
<?php
	}
?>
</tbody>
</table>

<p>&nbsp;<p>&nbsp;

<table class="main">
<caption> PACKAGE PROGRESS </caption>
<tbody>
<tr>
<?
	for ($i=0;$i<$db_cnt;$i++){
		echo '<td class="main_td td_sub_pkg3">';
		if($member['mb_7']==3 && $sql_pkg_state_arr[$pkg_db[$i]]!='0000-00-00' && $sql_pkg_state_arr[$pkg_db[$i+1]]=='0000-00-00'){
			echo '<a href = "javascript:document.'.$pkg_db[$i].'_cancle.submit()"><b><font color = blue>
					<form name="'.$pkg_db[$i].'_cancle" method="post" target="_self" onSubmit="return doSumbit()"> 
					<input type="hidden" name="pkg_cancle" value="'.$pkg_db[$i].'">
					</form>
				';
		}
		echo $pkg_th[$i];
		echo '</td>';
	}
?>
</tr>

<tr>

<?php
	for ($i=0;$i<$db_cnt;$i++){
		echo '<td class="main_td">';
		if($member['mb_7']==3 && $sql_pkg_state_arr[$pkg_db[$i-1]]!='0000-00-00' && $sql_pkg_state_arr[$pkg_db[$i]]=='0000-00-00'){
			echo '<a href = "javascript:document.'.$pkg_db[$i].'.submit()"><b><font color = blue>
					<form name="'.$pkg_db[$i].'" method="post" target="_self" onSubmit="return doSumbit()"> 
					<input type="hidden" name="pkg_step" value="'.$pkg_db[$i].'">
					</form>
				';
		}
		if($sql_pkg_state_arr[$pkg_db[$i]]=='0000-00-00') {echo 'Not yet';}
		else {echo $sql_pkg_state_arr[$pkg_db[$i]];}
		echo '</td>';
	}
	
?>
</tr>
</tbody>
</table>

<p>&nbsp;<p>&nbsp;

<?php
	if($sql_ref_pkg_arr['joint_yn'] == 'YES') {
	
		$query_pkg_stat = 'SELECT DISTINCT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE (pkg_no1 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no2 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no3 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no4 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no5 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no6 = "'.$sql_ref_pkg_arr['pkg_no'].'") ORDER BY line_size DESC';
		$sql_pkg_stat = sql_query ($query_pkg_stat);

		$i = 0;
		$tb_sum = 0;

		$total_jnt = 0;			$total_DI = 0;
		$done_jnt = 0;			$done_DI = 0;
		$spt_total = 0;			$spt_done = 0;
		$pwht_total = 0;		$pwht_done = 0;
		$pmi_total = 0;			$pmi_done = 0;
		$bw_s_total = 0;		$rt_s_done = 0;
		$bw_f_total = 0;		$rt_f_done = 0;
		
		$pkg_table_array = array();

		$pkg_finish = 1;

		while ($sql_pkg_stat_arr = sql_fetch_array ($sql_pkg_stat)) {

			
/*	
			$query_s_bw = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND s_f = "S" AND (j_type = "BW" OR j_type = "WOL") AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_s_bw = pcs_sql_value($query_s_bw);
			$query_f_bw = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND s_f = "F" AND (j_type = "BW" OR j_type = "WOL") AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_f_bw = pcs_sql_value($query_f_bw);

			$query_f_rt = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND s_f = "S" AND nde_type = "RT" AND nde_rlt = "Accept" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_s_rt = pcs_sql_value($query_f_rt);
			$query_f_rt = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND s_f = "F" AND nde_type = "RT" AND nde_rlt = "Accept" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_f_rt = pcs_sql_value($query_f_rt);


			$query_workvolumn_total_jnt = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND j_type != "SP" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_total_jnt = pcs_sql_value($query_workvolumn_total_jnt);

			$query_workvolumn_total_DI = "SELECT SUM(nps) FROM ".G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND j_type != "SP" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_total_DI = pcs_sql_value($query_workvolumn_total_DI);

			$query_workvolumn_done_jnt = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND vi_rlt = "Accept" AND w_type = "WELD" AND j_type != "SP" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_done_jnt = pcs_sql_value($query_workvolumn_done_jnt);

			$query_workvolumn_done_DI = "SELECT SUM(nps) FROM ".G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND vi_rlt = "Accept" AND w_type = "WELD" AND j_type != "SP" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_done_DI = pcs_sql_value($query_workvolumn_done_DI);

			$query_workvolumn_total_spt = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND j_type = "SP" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_total_spt = pcs_sql_value($query_workvolumn_total_spt);

			$query_workvolumn_done_spt = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND vi_rlt = "Accept" AND w_type = "WELD" AND j_type = "SP" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_done_spt = pcs_sql_value($query_workvolumn_done_spt);


			$query_workvolumn_total_pwht = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND pwht_yn = "Y" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_total_pwht = pcs_sql_value($query_workvolumn_total_pwht);

			$query_workvolumn_done_pwht = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND pwht_rlt = "Accept" AND pwht_yn = "Y" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_done_pwht = pcs_sql_value($query_workvolumn_done_pwht);


			$query_workvolumn_total_pmi = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND pmi_yn = "Y" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_total_pmi = pcs_sql_value($query_workvolumn_total_pmi);

			$query_workvolumn_done_pmi = 'SELECT COUNT(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND w_type = "WELD" AND pcs_pmi_rlt = "Accept" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_workvolumn_done_pmi = pcs_sql_value($query_workvolumn_done_pmi);
*/

			$temptbl2 = GenerateString(15);

			$query_view_dwg_create = 
				'CREATE VIEW '.$temptbl2.' AS SELECT *
				FROM '.G5_TABLE_PREFIX.'pcs_info_joint
				WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" ORDER BY j_no';
			sql_query ($query_view_dwg_create);
			
			
			$query_s_bw = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND s_f = "S" AND j_type = "BW"';
			$sql_s_bw = pcs_sql_value($query_s_bw);
			$query_f_bw = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND s_f = "F" AND j_type = "BW"';
			$sql_f_bw = pcs_sql_value($query_f_bw);

			$query_s_rt = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND s_f = "S" AND nde_type = "RT" AND nde_rlt = "Accept"';
			$sql_s_rt = pcs_sql_value($query_s_rt);
			$query_f_rt = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND s_f = "F" AND nde_type = "RT" AND nde_rlt = "Accept"';
			$sql_f_rt = pcs_sql_value($query_f_rt);


			$query_workvolumn_total_jnt = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND j_type != "SP"';
			$sql_workvolumn_total_jnt = pcs_sql_value($query_workvolumn_total_jnt);

			$query_workvolumn_total_DI = 'SELECT SUM(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND j_type != "SP"';
			$sql_workvolumn_total_DI = pcs_sql_value($query_workvolumn_total_DI);

			$query_workvolumn_done_jnt = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND vi_rlt = "Accept" AND w_type = "WELD" AND j_type != "SP"';
			$sql_workvolumn_done_jnt = pcs_sql_value($query_workvolumn_done_jnt);

			$query_workvolumn_done_DI = 'SELECT SUM(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND vi_rlt = "Accept" AND w_type = "WELD" AND j_type != "SP"';
			$sql_workvolumn_done_DI = pcs_sql_value($query_workvolumn_done_DI);

			$query_workvolumn_total_spt = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND j_type = "SP"';
			$sql_workvolumn_total_spt = pcs_sql_value($query_workvolumn_total_spt);

			$query_workvolumn_done_spt = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND vi_rlt = "Accept" AND w_type = "WELD" AND j_type = "SP"';
			$sql_workvolumn_done_spt = pcs_sql_value($query_workvolumn_done_spt);


			$query_workvolumn_total_pwht = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND pwht_yn = "YES"';
			$sql_workvolumn_total_pwht = pcs_sql_value($query_workvolumn_total_pwht);

			$query_workvolumn_done_pwht = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND pwht_rlt = "Accept" AND pwht_yn = "YES"';
			$sql_workvolumn_done_pwht = pcs_sql_value($query_workvolumn_done_pwht);


			$query_workvolumn_total_pmi = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND pmi_yn = "YES"';
			$sql_workvolumn_total_pmi = pcs_sql_value($query_workvolumn_total_pmi);

			$query_workvolumn_done_pmi = 'SELECT COUNT(nps) FROM '.$temptbl2.' WHERE j_stat = "ACT" AND w_type = "WELD" AND pmi_rlt = "Accept"';
			$sql_workvolumn_done_pmi = pcs_sql_value($query_workvolumn_done_pmi);
			
	
			$total_jnt 	+= $sql_workvolumn_total_jnt;			$total_DI 	+= $sql_workvolumn_total_DI;
			$done_jnt 	+= $sql_workvolumn_done_jnt;			$done_DI 	+= $sql_workvolumn_done_DI;
			$spt_total 	+= $sql_workvolumn_total_spt;			$spt_done 	+= $sql_workvolumn_done_spt;
			$pwht_total += $sql_workvolumn_total_pwht;			$pwht_done 	+= $sql_workvolumn_done_pwht;
			$pmi_total 	+= $sql_workvolumn_total_pmi;			$pmi_done 	+= $sql_workvolumn_done_pmi;
	
			$query_pkgby_dwg_ref = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_iso WHERE wr_subject = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_pkgby_dwg_ref = sql_query ($query_pkgby_dwg_ref);
			$sql_pkgby_dwg_ref_arr = sql_fetch_array ($sql_pkgby_dwg_ref);

			$query_pkgby_dwg = 'SELECT nde_rate, line_size, paint_code, material FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
			$sql_pkgby_dwg = sql_query ($query_pkgby_dwg);
			$sql_pkgby_dwg_arr = sql_fetch_array ($sql_pkgby_dwg);
	
			$bw_s_total += $sql_s_bw;				$rt_s_done += $sql_s_rt;
			$bw_f_total += $sql_f_bw;				$rt_f_done += $sql_f_rt;
			$bw_total += $sql_s_bw + $sql_f_bw;		$rt_done += $sql_s_rt + $sql_f_rt;
	
	
			$query_Apunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND category = "A"';
			$sql_Apunch_issue = pcs_sql_value($query_Apunch_issue);
	
			$query_Bpunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND category = "B"';
			$sql_Bpunch_issue = pcs_sql_value($query_Bpunch_issue);

			$query_Cpunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND category = "C"';
			$sql_Cpunch_issue = pcs_sql_value($query_Cpunch_issue);

			$query_Apunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND category = "A" AND cleared_date != "0000-00-00 00:00:00"';
			$sql_Apunch_clear = pcs_sql_value($query_Apunch_clear);
	
			$query_Bpunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND category = "B" AND cleared_date != "0000-00-00 00:00:00"';
			$sql_Bpunch_clear = pcs_sql_value($query_Bpunch_clear);

			$query_Cpunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND category = "C" AND cleared_date != "0000-00-00 00:00:00"';
			$sql_Cpunch_clear = pcs_sql_value($query_Cpunch_clear);	
	
	
			$Apunch_issue += $sql_Apunch_issue;			$Apunch_clear += $sql_Apunch_clear;
			$Bpunch_issue += $sql_Bpunch_issue;			$Bpunch_clear += $sql_Bpunch_clear;
			$Cpunch_issue += $sql_Cpunch_issue;			$Cpunch_clear += $sql_Cpunch_clear;
	
	
	
/*			$query_dwg = 'SELECT dwg_no, rev_no FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';		// 쿼리문
			$sql_dwg = sql_query ($query_dwg);
			$sql_dwg_arr = sql_fetch_array ($sql_dwg);*/
			

			$query_pkg_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pkg_coor WHERE pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND latest = "Y"';
			$sql_pkg_coor_check = sql_query ($query_pkg_coor_check);
			$sql_pkg_coor_array = sql_fetch_array ($sql_pkg_coor_check);

			
			$sno_inPKG[$sql_pkg_stat_arr['dwg_no']] = $i + 1;
			
			$pkg_table_array[$i][0] = $i + 1;
			$pkg_table_array[$i][1] = $sql_pkg_stat_arr['dwg_no'];
			$pkg_table_array[$i][2] = $sql_pkg_stat_arr['rev_no'];
			$pkg_table_array[$i][3] = $sql_pkgby_dwg_arr['line_size'];
			$pkg_table_array[$i][4] = $sql_pkgby_dwg_arr['nde_rate'];
			
			$pkg_table_array[$i][21] = $sql_pkg_stat_arr['shop_dwg'];
			$pkg_table_array[$i][22] = $sql_pkg_stat_arr['material'];
			$pkg_table_array[$i][23] = $sql_pkg_stat_arr['pressure'];
			
			if($sql_pkg_coor_array['tb_qty']){$pkg_table_array[$i][5] = $sql_pkg_coor_array['tb_qty'];} else{ $pkg_table_array[$i][5] = '-';}

			$pkg_txt_arr = explode(';',$sql_pkg_coor_array['joint_info']);
			
			$temp_j = count($pkg_txt_arr);
			$count_pageNo = 0;
			$count_markNo = 0;
			for($j=0;$j<$temp_j;$j++){
				$pkg_jnt_arr = explode(',',$pkg_txt_arr[$j]);
				if($pkg_jnt_arr[5]=='page' && $pkg_jnt_arr[8]=='Act'){$count_pageNo++;$pkg_table_array[$i][6][$count_pageNo] = $pkg_jnt_arr[7]*1;}
				if($pkg_jnt_arr[5]=='mark' && $pkg_jnt_arr[8]=='Act'){$count_markNo++;$pkg_table_array[$i][7][$count_markNo] = $pkg_jnt_arr[7];}
			}
			sort($pkg_table_array[$i][6]);

			if( ($sql_workvolumn_total_jnt)==0) {
				$pkg_table_array[$i][8][0] = 0;
				$pkg_table_array[$i][8][1] = '-';
			}
			else {
				if(($sql_workvolumn_done_jnt) == ($sql_workvolumn_total_jnt)){$pkg_table_array[$i][8][0] = 0;} else {$pkg_table_array[$i][8][0] = 1;}
				$pkg_table_array[$i][8][1] = ($sql_workvolumn_done_jnt).' / '. ($sql_workvolumn_total_jnt);
			}
			
			if( ($sql_workvolumn_total_spt)==0) {
				$pkg_table_array[$i][9][0] = 0;
				$pkg_table_array[$i][9][1] = '-';
			}
			else {
				if(($sql_workvolumn_done_spt) == ($sql_workvolumn_total_spt)){$pkg_table_array[$i][9][0] = 0;} else {$pkg_table_array[$i][9][0] = 1;}
				$pkg_table_array[$i][9][1] = ($sql_workvolumn_done_spt).' / '. ($sql_workvolumn_total_spt);
			}
			
			if( ($sql_workvolumn_total_pmi)==0) {
				$pkg_table_array[$i][10][0] = 0;
				$pkg_table_array[$i][10][1] = '-';
			}
			else {
				if(($sql_workvolumn_done_pmi) == ($sql_workvolumn_total_pmi)){$pkg_table_array[$i][10][0] = 0;} else {$pkg_table_array[$i][10][0] = 1;}
				$pkg_table_array[$i][10][1] = ($sql_workvolumn_done_pmi).' / '. ($sql_workvolumn_total_pmi);
			}
			
			if( ($sql_workvolumn_total_pwht)==0) {
				$pkg_table_array[$i][11][0] = 0;
				$pkg_table_array[$i][11][1] = '-';
			}
			else {
				if(($sql_workvolumn_done_pwht) == ($sql_workvolumn_total_pwht)){$pkg_table_array[$i][11][0] = 0;} else {$pkg_table_array[$i][11][0] = 1;}
				$pkg_table_array[$i][11][1] = ($sql_workvolumn_done_pwht).' / '. ($sql_workvolumn_total_pwht);
			}
			
			if(($sql_s_bw+$sql_f_bw)==0) {$pkg_table_array[$i][12] = '-';}
			else {
				if($sql_s_bw==0) {$pkg_table_array[$i][12] = '-';}		else {$pkg_table_array[$i][12] = 'S : '.($sql_s_rt).' / '. ($sql_s_bw);}
				if($sql_f_bw==0) {$pkg_table_array[$i][12] .= '<br>-';}	else {$pkg_table_array[$i][12] .= '<br>F : '.($sql_f_rt).' / '. ($sql_f_bw);}
			}
			
			if( ($sql_s_bw+$sql_f_bw)==0) {$pkg_table_array[$i][13] = 'N/A';} 	else {$pkg_table_array[$i][13] = sprintf("%2.0f", ($sql_s_rt+$sql_f_rt)/($sql_s_bw+$sql_f_bw)*100).' %';}

			if ( ($sql_Apunch_issue) == 0) {$pkg_table_array[$i][15][0] = '-';$pkg_table_array[$i][15][1] = false;}
			else {$pkg_table_array[$i][15][0] = ($sql_Apunch_clear).' / '. ($sql_Apunch_issue); if($sql_Apunch_clear==$sql_Apunch_issue){$pkg_table_array[$i][15][1] = false;}else{$pkg_table_array[$i][15][1] = true;}}
			if ( ($sql_Bpunch_issue) == 0) {$pkg_table_array[$i][16][0] = '-';$pkg_table_array[$i][16][1] = false;}
			else {$pkg_table_array[$i][16][0] = ($sql_Bpunch_clear).' / '. ($sql_Bpunch_issue); if($sql_Bpunch_clear==$sql_Bpunch_issue){$pkg_table_array[$i][16][1] = false;}else{$pkg_table_array[$i][16][1] = true;}}
			if ( ($sql_Cpunch_issue) == 0) {$pkg_table_array[$i][17][0] = '-';$pkg_table_array[$i][17][1] = false;}
			else {$pkg_table_array[$i][17][0] = ($sql_Cpunch_clear).' / '. ($sql_Cpunch_issue); if($sql_Cpunch_clear==$sql_Cpunch_issue){$pkg_table_array[$i][17][1] = false;}else{$pkg_table_array[$i][17][1] = true;}}
			
			
			$pkg_table_array[$i][18] = $sql_pkgby_dwg_ref_arr['wr_id'];
			$pkg_table_array[$i][19] = $tb_sum;
			$tb_sum = $tb_sum + $sql_pkg_coor_array['tb_qty'];
			$pkg_table_array[$i][20] = $sql_pkg_coor_array['dwg_state'];
			if($sql_pkg_coor_array['dwg_state']){$pkg_finish = $pkg_finish*1;} else {$pkg_finish = $pkg_finish*0;}
			
			$i++;
			
			$pkg_sort .= $i.','.$sql_pkg_stat_arr['dwg_no'].';';
			
			
			
			$query_view_dwg_drop = 'DROP VIEW IF EXISTS '.$temptbl2; 
			sql_query ($query_view_dwg_drop);
		}
		
		$k = $i;
		
		$pkg_page_no = array();
		
		for($i=0;$i<$k;$i++){
			$temp_l = count($pkg_table_array[$i][6]);
			for($l=0;$l<$temp_l;$l++){
				$tmp_x = $pkg_table_array[$i][6][$l]-1;
				$tmp_m = count($pkg_table_array[$tmp_x][6]);
				for($m=0;$m<$tmp_m;$m++){
					if($pkg_table_array[$tmp_x][6][$m]==$i+1){$pkg_page_no[$i][$l] = 1;break;}	else{$pkg_page_no[$i][$l] = 0;}
				}
			}
		}
	
		if($Apunch_issue==0){$G_total_A = '-';} 	else {$G_total_A = $Apunch_clear.' / '.$Apunch_issue;}
		if($Bpunch_issue==0){$G_total_B = '-';} 	else {$G_total_B = $Bpunch_clear.' / '.$Bpunch_issue;}
		if($Cpunch_issue==0){$G_total_C = '-';} 	else {$G_total_C = $Cpunch_clear.' / '.$Cpunch_issue;}
		if($total_jnt==0) 	{$G_total_jnt = 'N/A';} else {$G_total_jnt = $done_jnt.' / '.$total_jnt;}
		if($spt_total==0) 	{$G_total_supt = 'N/A';}else {$G_total_supt = $spt_done.' / '.$spt_total;}
		if($pwht_total==0) 	{$G_total_PWHT = 'N/A';}else {$G_total_PWHT = $pwht_done.' / '.$pwht_total;}
		if($pmi_total==0) 	{$G_total_PMI = 'N/A';} else {$G_total_PMI = $pmi_done.' / '.$pmi_total;}
		
		
	$query_sort = 'SELECT dwg_qty, dwg_order FROM '.G5_TABLE_PREFIX.'pcs_info_pkg_stat WHERE pkg_no = "'.$view['wr_subject'].'"';
	$sql_sort = sql_query ($query_sort);
	$sql_sort_arr = sql_fetch_array ($sql_sort);

?>
<table class="main">
<caption> 
<?php
	if($sql_sort_arr['dwg_qty'] && ($sql_sort_arr['dwg_qty'] != $k) ){
		echo '	<a href = "javascript:document.submit_for_sort.submit()"><font color="red"><b>Sort PKG drawing</b></font></a>
				<form name="submit_for_sort" action="'.PCS_CORE_URL.'/pcs_sortPKG.php" method="post" target="pkg" onSubmit="return doSumbit()"> 
				<input type="hidden" name="pkg" value="'.$view['wr_subject'].'">
				<input type="hidden" name="post_order" value="'.$sql_sort_arr['dwg_order'].'">
				<input type="hidden" name="post_qty" value="'.$sql_sort_arr['dwg_qty'].'">
				<input type="hidden" name="current_order" value="'.$pkg_sort.'">
				<input type="hidden" name="current_qty" value="'.$k.'">
				</form>
				';
	}
	else { echo 'PACKAGE STATUS';}
?>
</caption>
<tbody>
<tr>
<td class="jnt_td jnt_th" style="width:3%" rowspan="2"> No.</td>
<td class="jnt_td jnt_th" style="width:14%" rowspan="2"> Dwg No.</td>
<td class="jnt_td jnt_th" style="width:3%" rowspan="2"> Rev No.<br>& <br>State</td>
<td class="jnt_td jnt_th" style="width:4%" rowspan="2"> NPS<br>& <br>Mark</td>
<td class="jnt_td jnt_th" style="width:4%" rowspan="2"> NDE<br>( % )</td>
<td class="jnt_td jnt_th" style="width:4%" rowspan="2"> Blind<br>Qty</td>
<td class="jnt_td jnt_th" style="width:7%" rowspan="2"> Continued<br>Dwg no.</td>
<td class="jnt_td jnt_th" style="width:7%" rowspan="2"> Package<br>comment</td>
<td class="jnt_td jnt_th" style="width:7%" rowspan="2"> Material</td>
<td class="jnt_td jnt_th" style="width:7%" rowspan="2"> Test<br>Pressure</td>
<td class="jnt_td jnt_th" style="width:7%" rowspan="2"> Welding</td>
<td class="jnt_td jnt_th" style="width:7%" rowspan="2"> Support</td>
<td class="jnt_td jnt_th" colspan="2"> RT</td>
<td class="jnt_td jnt_th" style="width:4%" rowspan="2"> Punch<br>Issue</td>
<td class="jnt_td jnt_th" colspan="3">Punch<br>(cleared / issued)</a></td>
</tr>

<tr>
<td class="jnt_td jnt_th" style="width:4%; height:30px">Joint</td>
<td class="jnt_td jnt_th" style="width:4%; height:30px">Rate</td>
<td class="jnt_td jnt_th" style="width:4%; height:30px">A</td>
<td class="jnt_td jnt_th" style="width:4%; height:30px">B</td>
<td class="jnt_td jnt_th" style="width:4%; height:30px">C</td>
</tr>
<tr>
<td class="jnt_td" style="background-color:gold; font-size:25px;" colspan="5">Total Package Status</td>
<td class="jnt_td" style="background-color:gold"><?php echo $tb_sum; ?></td>
<td class="jnt_td" style="background-color:gold">-</td>
<td class="jnt_td" style="background-color:gold">-</td>

<td class="jnt_td" style="background-color:
	<?php if($pmi_total==0) {echo 'gold';} else {if($pmi_done/$pmi_total == 1 ) {echo 'gold';} else {echo 'red';}} ?>;">
	
	<?php if($pmi_total==0) {echo 'N/A';} else {  ?>
	<?php if($pmi_done == $pmi_total) {echo $pmi_done.' / '.$pmi_total;} else {echo $G_total_PMI; }} ?></td>

<td class="jnt_td" style="background-color:
	<?php if($pwht_total==0) {echo 'gold';} else {if($pwht_done/$pwht_total == 1 ) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($pwht_total==0) {echo 'N/A';} else { ?>
	<?php if($pwht_done == $pwht_total) {echo $pwht_done.' / '.$pwht_total;} else {echo $G_total_PWHT; }} ?></td>

<td class="jnt_td" style="background-color:<?php if($done_jnt == $total_jnt) {echo 'gold';} else {echo 'red';} ?>;">
	<?php if($done_jnt == $total_jnt) {echo $done_jnt.' / '.$total_jnt;} else { ?>
	<?php echo $G_total_jnt.'<br>'.$done_DI; } ?></td>


<td class="jnt_td" style="background-color:
	<?php if($spt_total==0) {echo 'gold';} else {if($spt_done/$spt_total == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($spt_total==0) {echo 'N/A';} else { if ($spt_done == $spt_total) {echo $spt_done.' / '.$spt_total;} else {echo $G_total_supt; }} ?></td>


<td class="jnt_td" style="background-color:
	<?php if($bw_total==0) {echo 'gold';} else {if($rt_done/$bw_total < $sql_ref_pkg_arr['nde']/100 ) {echo 'red';} else {echo 'gold';}} ?>; font-size:12px" colspan=2>
	<?php echo 'S : '.$rt_s_done.' / '.$bw_s_total; if($bw_s_total){echo ' ( '.sprintf("%2.1f", ($rt_s_done)/($bw_s_total)*100).' % )<br>';}	else {echo ' ( 0.0 %)<br>';} ?>
	<?php echo 'F : '.$rt_f_done.' / '.$bw_f_total; if($bw_f_total){echo ' ( '.sprintf("%2.1f", ($rt_f_done)/($bw_f_total)*100).' % )<br>';}	else {echo ' ( 0.0 %)<br>';}  ?>
	<?php echo 'T : '.$rt_done.' / '.$bw_total;		if($bw_total){echo ' ( '.sprintf("%2.1f", ($rt_done)/($bw_total)*100).' % )';}	else {echo ' ( 0.0 %)<br>';}  ?>
	</td>

<td class="jnt_td" style="background-color:gold">-</td>

<td class="jnt_td" style="background-color:
	<?php if($Apunch_issue==0) {echo 'gold';} else {if($Apunch_clear/$Apunch_issue == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($Apunch_issue==0) {echo 'N/A';} else {echo $G_total_A;} ?></td>

<td class="jnt_td" style="background-color:
	<?php if($Bpunch_issue==0) {echo 'gold';} else {if($Bpunch_clear/$Bpunch_issue == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($Bpunch_issue==0) {echo 'N/A';} else {echo $G_total_B;} ?></td>

<td class="jnt_td" style="background-color:
	<?php if($Cpunch_issue==0) {echo 'gold';} else {if($Cpunch_clear/$Cpunch_issue == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($Cpunch_issue==0) {echo 'N/A';} else {echo $G_total_C;} ?></td>

</tr>

<tr>
<td class="jnt_td">0</td>
<td class="jnt_td" style="text-align:center; font-size:25px;" colspan="6">
<?php
	if(1) {
?>
	<a href = 'javascript:document.submit_for_pkg.submit()'><b>Download Full Package PDF</b></a>
	<form name='submit_for_pkg' action="<?php echo PCS_CORE_URL; ?>/pcs_viewPKG_pdf2.php" method="post" target="pkg" onSubmit="return doSumbit()"> 
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	</form>
<?php
	}
	else {
		echo 'Not yet fully marked';
	}
?>
</td>
<td class="jnt_td" style="text-align:center; font-size:25px;" colspan="7">General Punch Issue</td>
<td class="jnt_td" >
	<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.submit_punch_<?php echo $i; ?>.submit()'><img src="<?php echo PCS_CORE_URL; ?>/punch_icon.png" width="35px"></a>
	<form name='submit_punch_<?php echo $i; ?>' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="GENERAL PUNCH">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	</form>
	<?php } ?>
</td>

<td class="jnt_td" <?php if($gen_Apunch_clear!=$gen_Apunch_issue){echo 'style="background-color:red"';} ?>>	<?php if ($gen_Apunch_issue == 0) {echo '-';} else {echo $gen_Apunch_clear.' / '.$gen_Apunch_issue;} ?> </td>
<td class="jnt_td" <?php if($gen_Bpunch_clear!=$gen_Bpunch_issue){echo 'style="background-color:red"';} ?>>	<?php if ($gen_Bpunch_issue == 0) {echo '-';} else {echo $gen_Bpunch_clear.' / '.$gen_Bpunch_issue;} ?> </td>
<td class="jnt_td" <?php if($gen_Cpunch_clear!=$gen_Cpunch_issue){echo 'style="background-color:red"';} ?>>	<?php if ($gen_Cpunch_issue == 0) {echo '-';} else {echo $gen_Cpunch_clear.' / '.$gen_Cpunch_issue;} ?> </td>
</tr>	


<?php
		for($i=0;$i<$k;$i++){
			$query_WMstate = 'SELECT dwg_state FROM '.G5_TABLE_PREFIX.'pcs_info_dwg_coor WHERE dwg_no = "'.$pkg_table_array[$i][1].'"';
			$sql_WMstate = sql_query ($query_WMstate);
			$sql_WMstate_arr = sql_fetch_array ($sql_WMstate);
			
?>


<tr id='tr<?php echo $i;?>'>
<td class="jnt_td"> <a href = 'javascript:document.marked<?php echo $pkg_table_array[$i][0]; ?>.submit()'><font style='font-size:20px;'><?php echo $pkg_table_array[$i][0]; ?> </font></a>
<?php viewPDF('marked'.$pkg_table_array[$i][0],'fab',$pkg_table_array[$i][1],$pkg_table_array[$i][2],$view['wr_subject']);?>

</td>
<td class="jnt_td">	<a href=<?php echo G5_URL.'/bbs/board.php?bo_table=iso&wr_id='.$pkg_table_array[$i][18]; ?> target='_self'><?php echo $pkg_table_array[$i][1]; ?></a><BR><b> <?php echo $pkg_table_array[$i][21]; ?></b></td>

<td class="jnt_td"> 
<?php
	echo $pkg_table_array[$i][2].'<br>';
	if($pkg_table_array[$i][20]=='Approved'){
		$txt_color = 'green';
		$txt_value = 'Marked';
	}
	else if($pkg_table_array[$i][20]=='Marked'){
		$txt_color = 'blue';
		$txt_value = 'Approved';
	}
if($member['mb_3']==3){
	echo '
		<a href = "javascript:submit_mark'.$pkg_table_array[$i][0].'.submit()"><font color = "'.$txt_color.'">'.$pkg_table_array[$i][20].'</font></a>
		<form name="submit_mark'.$pkg_table_array[$i][0].'" method="post"  target="_self" onSubmit="return doSumbit()">
		<input type="hidden" name="num_app" value="'.$txt_value.'">
		<input type="hidden" name="pkg" value="'.$view['wr_subject'].'">
		<input type="hidden" name="dwg" value="'.$pkg_table_array[$i][1].'">
		<input type="hidden" name="html_loc" value="'.$i.'">
		</form>';
}
else {
	echo '<font color = "'.$txt_color.'">'.$pkg_table_array[$i][20].'</font>';
}
?>

</td>

<td class="jnt_td">
<?php
		if($pkg_table_array[$i][20]!='Approved'&& $member['mb_3']>0){
?>
	<a href = 'javascript:document.numbering_form_<?php echo $pkg_table_array[$i][0]; ?>.submit()'><font style='font-size:20px;'><b><?php echo $pkg_table_array[$i][3].'"'; ?></a>
	<form name="numbering_form_<?php echo $pkg_table_array[$i][0]; ?>" action="<?php echo PCS_CORE_URL; ?>/pcs_mark_pkg_pdf.php" method="post" target="_blank" onSubmit="return doSumbit()"> 
	<input type="hidden" name="key" value="key">
	<input type="hidden" name="fn" value="<?php echo $pkg_table_array[$i][1]; ?>">
	<input type="hidden" name="sd" value="<?php echo $pkg_table_array[$i][21]; ?>">
	<input type="hidden" name="rev" value="<?php echo $pkg_table_array[$i][2]; ?>">
	<input type="hidden" name="pn" value="<?php echo $sql_ref_pkg_arr['pkg_no']; ?>">
	<input type="hidden" name="sn" value="<?php echo $pkg_table_array[$i][0]; ?>">
	<input type="hidden" name="tb" value="<?php echo $pkg_table_array[$i][19]; ?>">
	<input type="hidden" name="dwg_qty" value="<?php echo $k; ?>">
	<input type="hidden" name="dwg_order" value="<?php echo $pkg_sort; ?>">
	</form>
<?php 	}
		else{echo $pkg_table_array[$i][3].'"';}
?>
</td>

<td class="jnt_td">	<?php echo $pkg_table_array[$i][4].' %'; ?> </td>




<td class="jnt_td"> <?php if($pkg_table_array[$i][5]){echo $pkg_table_array[$i][5];} else{echo '-';} ?></td>

<td class="jnt_td">
<?php 
		$tmp_count = count($pkg_table_array[$i][6]);
		for($j=0;$j<$tmp_count;$j++){
			if($pkg_page_no[$i][$j]){echo $pkg_table_array[$i][6][$j].' ';} else {
				$idx_red = $pkg_table_array[$i][6][$j]-1;
?>
	<form name="numbering_form2_<?php echo $pkg_table_array[$idx_red][0]; ?>" action="<?php echo PCS_CORE_URL; ?>/pcs_mark_pkg_pdf.php" method="post" target="result" onSubmit="return doSumbit()"> 
	<input type="hidden" name="key" value="key">
	<input type="hidden" name="fn" value="<?php echo $pkg_table_array[$idx_red][1]; ?>">
	<input type="hidden" name="rev" value="<?php echo $pkg_table_array[$idx_red][2]; ?>">
	<input type="hidden" name="pn" value="<?php echo $sql_ref_pkg_arr['pkg_no']; ?>">
	<input type="hidden" name="sn" value="<?php echo $pkg_table_array[$idx_red][0]; ?>">
	<input type="hidden" name="tb" value="<?php echo $pkg_table_array[$idx_red][19]; ?>">
	<a href = 'javascript:document.numbering_form2_<?php echo $pkg_table_array[$idx_red][0]; ?>.submit()'><?php echo '<font color=red><B>'.$pkg_table_array[$i][6][$j].'</B></font> '; ?></a>
	</form>

<?php
			}
		}
?>
</td>

<td class="jnt_td">
<?php 
		$tmp_count = count($pkg_table_array[$i][7]);
		for($j=1;$j<$tmp_count ;$j++){
			echo $pkg_table_array[$i][7][$j].' , ';
		}
		echo $pkg_table_array[$i][7][$tmp_count];
?>
</td>

<td class="jnt_td" >
	<?php if($pkg_table_array[$i][22]=='S/S'){echo '<font color = "blue"><b>'.$pkg_table_array[$i][22].'</b></font>';}
			else if ($pkg_table_array[$i][22]=='A/S'){echo '<font color = "red"><b>'.$pkg_table_array[$i][22].'</b></font>';}
			else {echo $pkg_table_array[$i][22];}
	?>
</td>

<td class="jnt_td" ><?php echo $pkg_table_array[$i][23]; ?></td>

<td class="jnt_td" <?php if($pkg_table_array[$i][8][0]){echo 'style="background-color:red"';} ?>><?php echo $pkg_table_array[$i][8][1]; ?></td>

<td class="jnt_td" <?php if($pkg_table_array[$i][9][0]){echo 'style="background-color:red"';} ?>><?php echo $pkg_table_array[$i][9][1]; ?></td>

<td class="jnt_td" style="font-size:12px">	<?php echo $pkg_table_array[$i][12]; ?></td>

<td class="jnt_td">	<?php echo $pkg_table_array[$i][13]; ?></td>

<td class="jnt_td"> 
	<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.submit_punch_<?php echo $i; ?>.submit()'><img src="<?php echo PCS_CORE_URL; ?>/punch_icon.png" width="35px"></a>
	<form name='submit_punch_<?php echo $i; ?>' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="<?php echo $pkg_table_array[$i][1];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	</form>
	<?php } ?>
</td>

<td class="jnt_td" <?php if($pkg_table_array[$i][15][1]){echo 'style="background-color:red"';} ?>><?php echo $pkg_table_array[$i][15][0]; ?></td>

<td class="jnt_td" <?php if($pkg_table_array[$i][16][1]){echo 'style="background-color:red"';} ?>><?php echo $pkg_table_array[$i][16][0]; ?></td>

<td class="jnt_td" <?php if($pkg_table_array[$i][17][1]){echo 'style="background-color:red"';} ?>><?php echo $pkg_table_array[$i][17][0]; ?></td>

</tr>	


<?php	}		?>	



</tbody>
</table>
<p>&nbsp;<p>&nbsp;





<div style="width:35%;float:left; padding:10px;">

<table class="main" style="width:100%">
<caption> TEST BLIND LIST </caption>
<tbody>
<tr>
<td class="main_td td_sub_pkg3"> no.</td>
<td class="main_td td_sub_pkg3" style="height:20px;width:40%;"> Dwg no.</td>
<td class="main_td td_sub_pkg3"> Rev.</td>
<td class="main_td td_sub_pkg3"> Blind</td>
<td class="main_td td_sub_pkg3"> size</td>
</tr>
<?php
		$i=0;

		$query_pkg_tbcheck = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pkg_coor WHERE pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND latest = "Y" ORDER BY dwg_no';
		$sql_pkg_tbcheck = sql_query ($query_pkg_tbcheck);
		$old_qty = 0;
		while ($sql_pkg_tbarray = sql_fetch_array ($sql_pkg_tbcheck)) {
//			$pkg_dwg = $sql_pkg_tbarray['dwg_no'];
			$pkg_txt_arr = explode(';',$sql_pkg_tbarray['joint_info']);
			$temp_j = count($pkg_txt_arr);
			for($j=0;$j<$temp_j;$j++){
				$pkg_jnt_arr = explode(',',$pkg_txt_arr[$j]);
		
				if($pkg_jnt_arr[5]=='tbno'){
					$i++;
?>
<tr>
<td class="jnt_td" style="height:40px;"><?php echo $i; ?></td>
<td class="jnt_td" style="height:40px;"><?php echo $sql_pkg_tbarray['dwg_no']; ?></td>
<td class="jnt_td" style="height:40px;"><?php echo $sql_pkg_tbarray['rev_no']; ?></td>
<td class="jnt_td" style="height:40px;"><?php if(($pkg_jnt_arr[7]+$old_qty)<10){echo 'TB-0'.($pkg_jnt_arr[7]+$old_qty);} else {echo 'TB-'.($pkg_jnt_arr[7]+$old_qty);} ; ?></td>
<td class="jnt_td" style="height:40px;"><?php echo $pkg_jnt_arr[6]; ?></td>
</tr>

<?php			
				}
			}
			$old_qty += $sql_pkg_tbarray['tb_qty'];
		}
?>

</tbody>
</table>

<p>&nbsp;<p>&nbsp;
</div>

<div style='width:65%;float:right;padding:10px;'>

<table class="main">
<caption>PUNCH LIST</caption>
<tbody>
<tr>
<td class="jnt_td td_sub_pkg1" style="width:4%"> no.</td>
<td class="jnt_td td_sub_pkg1" style="width:22%" colspan="2"> Dwg no.</td>
<td class="jnt_td td_sub_pkg1" style="width:5%"> Cate<br>gory </td>
<td class="jnt_td td_sub_pkg1" > Punch Description </td>
<td class="jnt_td td_sub_pkg1" style="width:15%"> ISSUE </td>
<td class="jnt_td td_sub_pkg1" style="width:15%"> CLEAR </td>
</tr>
<?php

		$query_pkg_Pchk = 'SELECT * FROM '.$temptbl1;
		$sql_pkg_Pchk = sql_query ($query_pkg_Pchk);
		while ($sql_pkg_Pchk_arr = sql_fetch_array ($sql_pkg_Pchk)) {
			if($sql_pkg_Pchk_arr['s_no']*1<10){$jpgfile = PCS_PKG_URL.'/'.$sql_pkg_Pchk_arr['pkg_no'].'/'.$sql_pkg_Pchk_arr['pkg_no'].'_00'.$sql_pkg_Pchk_arr['s_no'];}
			else if($sql_pkg_Pchk_arr['s_no']*1<100){$jpgfile = PCS_PKG_URL.'/'.$sql_pkg_Pchk_arr['pkg_no'].'/'.$sql_pkg_Pchk_arr['pkg_no'].'_0'.$sql_pkg_Pchk_arr['s_no'];}
			else {$jpgfile = PCS_PKG_URL.'/'.$sql_pkg_Pchk_arr['pkg_no'].'/'.$sql_pkg_Pchk_arr['pkg_no'].'_'.$sql_pkg_Pchk_arr['s_no'];}

?>
<tr>
<td class="jnt_td"><?php echo $sql_pkg_Pchk_arr['s_no']; ?></td>
<td class="jnt_td" style="width:4%"><?php if($sno_inPKG[$sql_pkg_Pchk_arr['dwg_no']]){echo $sno_inPKG[$sql_pkg_Pchk_arr['dwg_no']];} else{echo '0';} ?></td>
<td class="jnt_td"><?php echo $sql_pkg_Pchk_arr['dwg_no']; ?></td>
<td class="main_td">
	<a href = 'javascript:document.punch_modi<?php echo $sql_pkg_Pchk_arr['s_no']; ?>.submit()'><?php echo $sql_pkg_Pchk_arr['category']; ?></a>
	<form name='punch_modi<?php echo $sql_pkg_Pchk_arr['s_no']; ?>' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="remove">
	<input type="hidden" name="t_no" value="<?php echo $sql_pkg_Pchk_arr['s_no']; ?>">
	<input type="hidden" name="dwg" value="<?php echo $sql_pkg_Pchk_arr['dwg_no'];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	</form>
</td>
<td class="jnt_td" style="padding:0px 0px 0px 15px; text-align:left;">
<?php
	if(!$sql_pkg_Pchk_arr['punch_desc'] || $member['mb_7']==3){ 
		if($sql_pkg_Pchk_arr['punch_desc']){ $punch_desc = $sql_pkg_Pchk_arr['punch_desc'];}
		else { $punch_desc = '<p style="text-align:center;font-size:25px;"><font color = red><b> No description </b></font></p>';}
?>
	<a href = 'javascript:document.desc_modi<?php echo $sql_pkg_Pchk_arr['s_no']; ?>.submit()'><?php echo $punch_desc; ?></a>
	<form name='desc_modi<?php echo $sql_pkg_Pchk_arr['s_no']; ?>' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="u_desc">
	<input type="hidden" name="t_no" value="<?php echo $sql_pkg_Pchk_arr['s_no']; ?>">
	<input type="hidden" name="dwg" value="<?php echo $sql_pkg_Pchk_arr['dwg_no'];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	</form>
<?php
	}
	else {echo $sql_pkg_Pchk_arr['punch_desc'];}
?>
</td>
<td class="jnt_td">
<?php
	if($sql_pkg_Pchk_arr['issued_by']){
		echo '<a onclick=\'window.open("'.$jpgfile.'_BF.jpg","'.$jn.$photoType.'","width=650, height=500, left=200, top=100");\'>';
		echo $sql_pkg_Pchk_arr['issued_by'].'<br>'.$sql_pkg_Pchk_arr['issued_date'].'</a>';
	}
?>
</td>
<td class="jnt_td">
<?php
	if($sql_pkg_Pchk_arr['cleared_by']){
		echo '<a onclick=\'window.open("'.$jpgfile.'_AF.jpg","'.$jn.$photoType.'","width=650, height=500, left=200, top=100");\'>';
		echo $sql_pkg_Pchk_arr['cleared_by'].'<br>'.$sql_pkg_Pchk_arr['cleared_date'].'</a>';
	}
	else if($member['mb_7']>1){
?>
	<a href = 'javascript:document.punch_clear<?php echo $sql_pkg_Pchk_arr['s_no']; ?>.submit()'><font color = blue><b>CLEAR PUNCH</b></font></a>
	<form name='punch_clear<?php echo $sql_pkg_Pchk_arr['s_no']; ?>' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="clear">
	<input type="hidden" name="t_no" value="<?php echo $sql_pkg_Pchk_arr['s_no']; ?>">
	<input type="hidden" name="dwg" value="<?php echo $sql_pkg_Pchk_arr['dwg_no'];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	</form>
<?php
	}
	else {echo '<font color = red><b> Not yet cleared </b></font>';}
?>
</td>
</tr>
			
<?php
		}
?>
</tbody>
</table>

<p>&nbsp;<p>&nbsp;
</div>

</div>

<script language="javascript">
	$('html, body').stop().animate({scrollTop : $("#tr<?php echo $_POST['html_loc'];?>").offset().top - screen.height/2},300);
</script>

<?php 
		if(($bw_s_total+$bw_f_total)==0) {$G_total_rt = 'N/A';} else {$G_total_rt = ($rt_s_done+$rt_f_done).' / '.($bw_s_total+$bw_f_total);}

		$query_pkg = '
			UPDATE '.G5_TABLE_PREFIX.'pcs_info_pkg_stat SET 
				total_wd = "'.$G_total_jnt.'", 
				total_spt = "'.$G_total_supt.'", 
				total_pwht = "'.$G_total_PWHT.'", 
				total_pmi = "'.$G_total_PMI.'", 
				total_a = "'.$G_total_A.'", 
				total_b = "'.$G_total_B.'", 
				total_c = "'.$G_total_C.'", 
				total_rt = "'.$G_total_rt.'", 
				last_chk = "'.G5_TIME_YMD.'" 
			WHERE pkg_no = "'.$view['wr_subject'].'"
		';	sql_query ($query_pkg);
	}
}


else {	//////////////////////////////////////////////////////// mobile

	$query_pkg_stat = 'SELECT DISTINCT dwg_no, rev_no FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE (pkg_no1 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no2 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no3 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no4 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no5 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no6 = "'.$sql_ref_pkg_arr['pkg_no'].'")';
	$sql_pkg_stat = sql_query ($query_pkg_stat);
?>

<table class="main">
<caption> PUNCH STATUS </caption>
<tbody>
<tr>
<td class="jnt_td jnt_th" style="width:5%" rowspan="2"> No.</td>
<td class="jnt_td jnt_th" style="width:30%" rowspan="2"> Dwg No.</td>
<td class="jnt_td jnt_th" colspan="3">Cleared / Issued
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_list.submit()'><b>VIEW LIST</b></a>
	<form name='punch_list' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="m_list">
	</form>
<?php } ?>
</td>
</tr>

<tr>
<td class="jnt_td jnt_th" style="width:7%; height:30px">A</td>
<td class="jnt_td jnt_th" style="width:7%; height:30px">B</td>
<td class="jnt_td jnt_th" style="width:7%; height:30px">C</td>
</tr>

<tr>
<td class="jnt_td" colspan="2">General Punch Issued</td>
<td class="jnt_td">	
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_A.submit()'><?php if ( ($gen_Apunch_issue) == 0) {echo '-';} else {echo $gen_Apunch_clear.'/'.$gen_Apunch_issue;} ?> </a>
	<form name='punch_A' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="GENERAL PUNCH">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="cat" value="A">
	</form>
<?php }
	else if ( ($gen_Apunch_issue) == 0) {echo '-';} 
	else {echo $gen_Apunch_clear.'/'.$gen_Apunch_issue;}
	
?>
</td>
<td class="jnt_td">	
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_B.submit()'><?php if ( ($gen_Bpunch_issue) == 0) {echo '-';} else {echo $gen_Bpunch_clear.'/'.$gen_Bpunch_issue;} ?> </a>
	<form name='punch_B' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="GENERAL PUNCH">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="cat" value="B">
	</form>
<?php }
	else if ( ($gen_Bpunch_issue) == 0) {echo '-';} 
	else {echo $gen_Bpunch_clear.'/'.$gen_Bpunch_issue;}
 ?>
</td>
<td class="jnt_td">	
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_C.submit()'><?php if ( ($gen_Cpunch_issue) == 0) {echo '-';} else {echo $gen_Cpunch_clear.'/'.$gen_Cpunch_issue;} ?> </a>
	<form name='punch_C' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="GENERAL PUNCH">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="cat" value="C">
	</form>
<?php }
	else if ( ($gen_Cpunch_issue) == 0) {echo '-';} 
	else {echo $gen_Cpunch_clear.'/'.$gen_Cpunch_issue;}
 ?>
</td>
</tr>	

<?php

	$i = 0;

	while ($sql_pkg_stat_arr = sql_fetch_array ($sql_pkg_stat)) {

		$i++;
		
		$query_Apunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "A" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
		$sql_Apunch_issue = pcs_sql_value($query_Apunch_issue);
	
		$query_Bpunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "B" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
		$sql_Bpunch_issue = pcs_sql_value($query_Bpunch_issue);

		$query_Cpunch_issue = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "C" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
		$sql_Cpunch_issue = pcs_sql_value($query_Cpunch_issue);

		$query_Apunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "A" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND cleared_date != "0000-00-00 00:00:00"';
		$sql_Apunch_clear = pcs_sql_value($query_Apunch_clear);
	
		$query_Bpunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "B" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND cleared_date != "0000-00-00 00:00:00"';
		$sql_Bpunch_clear = pcs_sql_value($query_Bpunch_clear);

		$query_Cpunch_clear = 'SELECT COUNT(s_no) FROM '.$temptbl1.' WHERE category = "C" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND cleared_date != "0000-00-00 00:00:00"';
		$sql_Cpunch_clear = pcs_sql_value($query_Cpunch_clear);	
	
		$query_pkgby_dwg_ref = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_iso WHERE wr_subject = "'.$sql_pkg_stat_arr['dwg_no'].'"';
		$sql_pkgby_dwg_ref = sql_query ($query_pkgby_dwg_ref);
		$sql_pkgby_dwg_ref_arr = sql_fetch_array ($sql_pkgby_dwg_ref);
	
		$Apunch_issue += $sql_Apunch_issue;			$Apunch_clear += $sql_Apunch_clear;
		$Bpunch_issue += $sql_Bpunch_issue;			$Bpunch_clear += $sql_Bpunch_clear;
		$Cpunch_issue += $sql_Cpunch_issue;			$Cpunch_clear += $sql_Cpunch_clear;

		$query_pkg_coor_check = 'SELECT tb_qty FROM '.G5_TABLE_PREFIX.'pcs_info_pkg_coor WHERE pkg_no = "'.$sql_ref_pkg_arr['pkg_no'].'" AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND latest = "Y"';
		$sql_pkg_coor_check = sql_query ($query_pkg_coor_check);
		$sql_pkg_coor_array = sql_fetch_array ($sql_pkg_coor_check);
		
		$query_dwg_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_dwg_coor WHERE dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'" AND rev_no = "'.$sql_pkg_stat_arr['rev_no'].'"';
		$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
		$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);

?>

<tr>
<td class="jnt_td"> <a href = 'javascript:document.submit_for_marked<?php echo $i; ?>.submit()'><font style='font-size:20px;'><?php echo $i; ?> </font></a>
<?php viewPDF('submit_for_marked'.$i,'pkg',$sql_pkg_stat_arr['dwg_no'],$sql_pkg_stat_arr['rev_no'],'pkg');?>
</td>
<td class="jnt_td">	<a href=<?php echo G5_BBS_URL.'/board.php?bo_table=iso&wr_id='.$sql_pkgby_dwg_ref_arr[wr_id]; ?> target='_self'><?php echo $sql_pkg_stat_arr['dwg_no']; ?></a></td>

	<?php if($member['mb_7']>0) {} ?>

<td class="jnt_td">	
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_<?php echo $i; ?>A.submit()'><?php if ( ($sql_Apunch_issue) == 0) {echo '-';} else {echo $sql_Apunch_clear.'/'.$sql_Apunch_issue;} ?> </a>
	<form name='punch_<?php echo $i; ?>A' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="<?php echo $sql_pkg_stat_arr['dwg_no'];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="cat" value="A">
	</form>
<?php }
	else if ( ($sql_Apunch_issue) == 0) {echo '-';} 
	else {echo $sql_Apunch_clear.'/'.$sql_Apunch_issue;}
 ?>
</td>

<td class="jnt_td">
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_<?php echo $i; ?>B.submit()'><?php if ( ($sql_Bpunch_issue) == 0) {echo '-';} else {echo $sql_Bpunch_clear.'/'.$sql_Bpunch_issue;} ?> </a>
	<form name='punch_<?php echo $i; ?>B' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="<?php echo $sql_pkg_stat_arr['dwg_no'];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="cat" value="B">
	</form>
<?php }
	else if ( ($sql_Bpunch_issue) == 0) {echo '-';} 
	else {echo $sql_Bpunch_clear.'/'.$sql_Bpunch_issue;}
 ?>
</td>

<td class="jnt_td">
<?php if($member['mb_7']>0) { ?>
	<a href = 'javascript:document.punch_<?php echo $i; ?>C.submit()'><?php if ( ($sql_Cpunch_issue) == 0) {echo '-';} else {echo $sql_Cpunch_clear.'/'.$sql_Cpunch_issue;} ?> </a>
	<form name='punch_<?php echo $i; ?>C' method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="p_page" value="p_cont">
	<input type="hidden" name="mode" value="issue">
	<input type="hidden" name="t_no" value="<?php echo $sql_p_count;?>">
	<input type="hidden" name="dwg" value="<?php echo $sql_pkg_stat_arr['dwg_no'];?>">
	<input type="hidden" name="pkg" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="cat" value="C">
	</form>
<?php }
	else if ( ($sql_Cpunch_issue) == 0) {echo '-';} 
	else {echo $sql_Cpunch_clear.'/'.$sql_Cpunch_issue;}
 ?>
</td>

</tr>	
<?php
	}
		if($Apunch_issue==0){$G_total_A = '-';} 	else {$G_total_A = $Apunch_clear.'/'.$Apunch_issue;}
		if($Bpunch_issue==0){$G_total_B = '-';} 	else {$G_total_B = $Bpunch_clear.'/'.$Bpunch_issue;}
		if($Cpunch_issue==0){$G_total_C = '-';} 	else {$G_total_C = $Cpunch_clear.'/'.$Cpunch_issue;}
?>

<tr>
<td class="jnt_td" style="background-color:gold" colspan="2">Grand-Total</td>

<td class="jnt_td" style="background-color:
	<?php if($Apunch_issue==0) {echo 'gold';} else {if($Apunch_clear/$Apunch_issue == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($Apunch_issue==0) {echo '-';} else {echo $G_total_A;} ?></td>

<td class="jnt_td" style="background-color:
	<?php if($Bpunch_issue==0) {echo 'gold';} else {if($Bpunch_clear/$Bpunch_issue == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($Bpunch_issue==0) {echo '-';} else {echo $G_total_B;} ?></td>

<td class="jnt_td" style="background-color:
	<?php if($Cpunch_issue==0) {echo 'gold';} else {if($Cpunch_clear/$Cpunch_issue == 1) {echo 'gold';} else {echo 'red';}} ?>;">
	<?php if($Cpunch_issue==0) {echo '-';} else {echo $G_total_C;} ?></td>

</tr>

</tbody>
</table>

<?php

}
	$query_view_punch_drop = 'DROP VIEW IF EXISTS '.$temptbl1; 
	sql_query ($query_view_punch_drop);

?>
