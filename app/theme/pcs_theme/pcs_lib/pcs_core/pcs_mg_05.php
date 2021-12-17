<?php
	$table_field_array = array('No.','Package no.','Test type','Line<br>size','Pressure','Welding','Support','PWHT','PMI','Punch A','Punch B','Punch C','RT','Last Check');
	$sql_field_array = array('test_type','line_size','pressure','total_wd','total_spt','total_pwht','total_pmi','total_a','total_b','total_c','total_rt','last_chk');
	echo '
	<table class="main">
	<caption> PACKAGE STATUS </caption>
	<tbody>
	<tr>';
	for($i=0;$i<count($table_field_array);$i++){echo '<td class="jnt_td jnt_th">'.$table_field_array[$i].'</td>';}
	echo '</tr>';

	switch ($pkg_seq)	{
		case 0 : $seq = ""; break;
		case 1 : $seq = "B.review <> '0000-00-00' AND "; break;
		case 2 : $seq = "B.line_chk <> '0000-00-00' AND "; break;
		case 3 : $seq = "B.punch_A <> '0000-00-00' AND "; break;
		case 4 : $seq = "B.h_test <> '0000-00-00' AND "; break;
		default : break;
	}

	$field_query = "DESCRIBE ".G5_TABLE_PREFIX."pcs_info_package";
	$field_val = enum_value($field_query);

	$j_unit = $field_val['unit'];
	$j_agug = $field_val['ag_ug'];
	$j_type = $field_val['test_type'];

	$unit = "A.unit = '".$j_unit[$pkg_unit]."' and ";
	$a_u = "A.ag_ug = '".$j_agug[$pkg_a_u]."' and ";
	$typ = "A.test_type = '".$j_type[$pkg_typ]."'";

	$query_pkg = "SELECT A.*, B.* FROM ".G5_TABLE_PREFIX."pcs_info_package AS A JOIN ".G5_TABLE_PREFIX."pcs_info_pkg_stat AS B ON A.pkg_no = B.pkg_no WHERE ".$seq.$unit.$a_u.$typ;
	$sql_pkg = sql_query ($query_pkg);
	

	$no = 0;

	while ($sql_pkg_arr = sql_fetch_array ($sql_pkg))	{
		$no++;
		$query_g5_pkg1 = "SELECT wr_id FROM ".G5_TABLE_PREFIX."write_package WHERE wr_subject = '".$sql_pkg_arr['pkg_no']."'";
		$sql_g5_pkg1 = sql_query ($query_g5_pkg1);
		$sql_g5_pkg1_arr = sql_fetch_array ($sql_g5_pkg1);	

		echo '<tr>
			<td class="jnt_td">'.$no.'</td>
			<td class="jnt_td">
			<a href='.G5_URL.'/bbs/board.php?bo_table=package&wr_id='.$sql_g5_pkg1_arr[wr_id].'target="_self">'.$sql_pkg_arr['pkg_no'].'</td>';

		for($i=0;$i<count($sql_field_array)-1;$i++)	{echo '<td class="jnt_td">'.$sql_pkg_arr[$sql_field_array[$i]].'</td>';}
		echo '<td class="jnt_td">';
		if($sql_pkg_arr[last_chk]!='0000-00-00'){echo $sql_pkg_arr['last_chk'];} else{echo 'Not yet';}
		echo '</td>
			</tr>';
	}	
	echo '</tbody>
		</table>
		<p>&nbsp;';
?>