<?php	

	switch ($prg_sel)	{
	
		case PMI 	:
			$pkg_rep = 'pwht_rep';
			$query_pkg = 'SELECT DISTINCT dwg_no, j_no, '.$pkg_rep.' FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND pwht_yn = "Y" AND pwht_rep <> "" ';
			break;

		case PWHT 	:
			$pkg_rep = 'pwht_rep';
			$query_pkg = 'SELECT DISTINCT dwg_no, j_no, '.$pkg_rep.' FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND pwht_yn = "Y" AND pwht_rep <> "" ';
			break;

		case RT 	:
			$pkg_rep = 'nde_rep';
			$query_pkg = 'SELECT DISTINCT dwg_no, j_no, '.$pkg_rep.' FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND nde_type = "RT" ';
			break;

		case MT 	:
			$pkg_rep = 'nde_rep';
			$query_pkg = 'SELECT DISTINCT dwg_no, j_no, '.$pkg_rep.' FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND nde_type = "MT" ';
			break;


		default 	: 	break;
	}

//	$query_pkg = 'SELECT DISTINCT dwg_no, j_no, '.$prg.' FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE j_stat = "ACT" AND '.$prg.' <> "" AND pkg_no = "'.$_GET['pkg_sel'].'"';
//echo $query_pkg;
?>

<table class="main">
<caption> REPORT DOWNLOAD </caption>
<tbody>
<tr>
<td class="main_td jnt_th">No</td>
<td class="main_td jnt_th">Dwg. No</td>
<td class="main_td jnt_th">joint</td>
<td class="main_td jnt_th">report</td>
</tr>

<?php
///////////////////////	$sql_pkg = sql_query ($query_pkg);

	$no = 1;

	$field_query = 'DESC '.G5_TABLE_PREFIX.'pcs_info_joint';
	$field_name = field_name_array($field_query);

	
///////////////////////////////////////////////////////////////////////////
	$query_pkg_stat = 'SELECT DISTINCT dwg_no, rev_no FROM '.G5_TABLE_PREFIX.'pcs_info_drawing WHERE (pkg_no1 = "'.$_GET['pkg_sel'].'" OR pkg_no2 = "'.$_GET['pkg_sel'].'")';
	$sql_pkg_stat = sql_query ($query_pkg_stat);
	
while ($sql_pkg_stat_arr = sql_fetch_array ($sql_pkg_stat)) {
	$query_pkg1 = $query_pkg.' AND dwg_no = "'.$sql_pkg_stat_arr['dwg_no'].'"';
//	echo $query_pkg1.'<br>';
	$sql_pkg = sql_query ($query_pkg1);
//////////////////////////////////////////////////////////////////////////	

	while ($sql_pkg_arr = sql_fetch_array ($sql_pkg))	{
		$temp_count = count($field_name);
		for ($i=0; $i<$temp_count; $i++) {
			if ($sql_pkg_arr[$field_name[$i]] == '0000-00-00') {$sql_pkg_arr[$field_name[$i]] = false ;}
		}
?>

<tr>
<td class="main_td" style="width:10%;"> <?php echo $no++ ?> </td>
<td class="main_td">

<?php
		if($prev_dwg==$sql_pkg_arr['dwg_no']) {echo '';}
		else {echo $sql_pkg_arr['dwg_no']; }
?>

</td>
<td class="main_td" style="width:10%;"><?php z_rem_jno($sql_pkg_arr['j_no']); ?></td>
<td class="main_td"><?php echo remove_spe_char($sql_pkg_arr[$pkg_rep]); ?></td>


<?php
		$prev_dwg = $sql_pkg_arr['dwg_no'];
	}
?>

</tr>
<?php }	/////////////////////////////////////////////////////////////// ?>
</tbody>
</table>




<p>&nbsp;<p>&nbsp;
