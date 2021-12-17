<?php
if($_POST['pkg_select']){
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
}
else {
	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_package';
	$field_val = enum_value($field_query);

	$pkg_stat = array ('Total','review','line_chk','punch_a','h_test','punch_b');
	$cnt_stat = count($pkg_stat)-1;
	$pkg_unit = $field_val['unit'];
	$cnt_unit = count($pkg_unit);
	$pkg_type = $field_val['test_type'];
	$cnt_type = count($pkg_type);
	$pkg_ag_ug = $field_val['ag_ug'];
	$cnt_agug = count($pkg_ag_ug);

	$query_view_pkg_create = 
		'CREATE VIEW pkgState AS SELECT A.pkg_no, A.ag_ug, A.unit, A.test_type, B.review, B.line_chk, B.punch_a, B.h_test, B.punch_b
		FROM '.G5_TABLE_PREFIX.'pcs_info_package AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_pkg_stat AS B ON A.pkg_no = B.pkg_no';
	sql_query ($query_view_pkg_create);
?>


<table class="main">
<caption> HYDRO-TEST STATUS </caption>
<tbody>
<tr>
<td class="main_td jnt_th">AG/UG</td>
<td class="main_td jnt_th">Unit</td>
<td class="main_td jnt_th">Test type</td>
<td class="main_td jnt_th">Total</td>
<td class="main_td jnt_th">Review</td>
<td class="main_td jnt_th">Line<br>check</td>
<td class="main_td jnt_th">A-punch<br>clear</td>
<td class="main_td jnt_th">Test<br>accepted</td>
<td class="main_td jnt_th">Progress</td>
</tr>

<?php
$rs_agug = ($cnt_type+1)*$cnt_unit;
for ( $l = 0 ; $l < $cnt_agug ; $l++)	{
?>

<tr>
<td class="main_td" rowspan="<?php echo $rs_agug;?>"><?php echo $pkg_ag_ug[$l]; ?></td>

<?php	

	for ( $k = 0 ; $k < $cnt_unit ; $k++)	{
		if($k){echo '<tr>';}
?>

<td class="main_td" rowspan="<?php echo count($pkg_type);?>"><?php echo $pkg_unit[$k]; ?></td>

<?php
		for ( $j = 0 ; $j < $cnt_type ; $j++ )	{
			if($j){echo '<tr>';}
?>	

<td class="main_td"><?php	echo $pkg_type[$j]; ?></td>

<?php
			for ( $i = 0 ; $i < $cnt_stat ; $i++ )	{
				$query_pkg = 'SELECT count(pkg_no) FROM pkgState WHERE ag_ug = "'.$pkg_ag_ug[$l].'" AND test_type = "'.$pkg_type[$j].'" AND unit = "'.$pkg_unit[$k].'"';

				if($i) {$query_pkg .= ' AND '.$pkg_stat[$i].' != "0000-00-00"  AND '.$pkg_stat[$i+1].' = "0000-00-00" ';}

				$sql_pkg = pcs_sql_value ($query_pkg);
				$pkg_qty_ag[$l][$k][$j][$i] = $sql_pkg;
?>

<td class="main_td">

<?php
				if($pkg_qty_ag[$l][$k][$j][$i]==0) {
					if($pkg_qty_ag[$l][$k][$j][$i]==0) {echo '-';}
					else {echo $pkg_qty_ag[$l][$k][$j][$i];} } 
			
				else { 
?>

	<a href = 'javascript:document.pkg<?php echo $l.$k.$j.$i; ?>.submit()'> <?php echo $pkg_qty_ag[$l][$k][$j][$i]; ?> </a>
	<form name="pkg<?php echo $l.$k.$j.$i; ?>" method="post" target="_self" onSubmit="return doSumbit()"> 
	<input type="hidden" name="pkg_select" value="yes">
	<input type="hidden" name="pkg_seq" value="<?php echo $i; ?>">
	<input type="hidden" name="pkg_typ" value="<?php echo $j; ?>">
	<input type="hidden" name="pkg_unit" value="<?php echo $k; ?>">
	<input type="hidden" name="pkg_a_u" value="<?php echo $l; ?>">
	</form>

<?PHP
				}
			}
?>

<td class="main_td"><?php if($pkg_qty_ag[$l][$k][$j][0]==0) {echo 'N/A';} else {echo sprintf("%2.1f",$pkg_qty_ag[$l][$k][$j][4] / $pkg_qty_ag[$l][$k][$j][0] * 100)." %";} ?></td>
</tr>

<?PHP
		}
?>

<tr>
<td class="main_td td_total" colspan="2"> Total </td>

<?php
		for ( $i = 0 ; $i < $cnt_stat ; $i++ )	{

			$sum_unit[$l][$k][$i] = $pkg_qty_ag[$l][$k][0][$i]+$pkg_qty_ag[$l][$k][1][$i]+$pkg_qty_ag[$l][$k][2][$i];
?>

<td class="main_td td_total"><?php if ($sum_unit[$l][$k][$i]==0) {echo '-';} else {echo $sum_unit[$l][$k][$i];} ?></td>

<?PHP	
		}	?>

<td class="main_td td_total"><?php if($sum_unit[$l][$k][0]==0) {echo 'N/A';} else {echo sprintf("%2.1f",$sum_unit[$l][$k][4] / $sum_unit[$l][$k][0] * 100)." %";} ?></td>
</tr>

<?PHP
	}
?>

<tr>
<td class="main_td td_S_total" colspan="3">S-Total</td>

<?php	
	for ( $i = 0 ; $i < $cnt_stat ; $i++ )	{

		$sum_ag_ug[$l][$i] = $sum_unit[$l][0][$i]+$sum_unit[$l][1][$i]+$sum_unit[$l][2][$i];
?>

<td class="main_td td_S_total">	<?php if($sum_ag_ug[$l][$i]==0) {echo '-';} else {echo $sum_ag_ug[$l][$i];} ?></td>

<?PHP
	}
?>

<td class="main_td td_S_total">	<?php if($sum_ag_ug[$l][0]==0) {echo 'N/A';} else {echo sprintf("%2.1f",$sum_ag_ug[$l][4] / $sum_ag_ug[$l][0] * 100)." %";} ?></td></tr>

<?PHP	
}
?>


<tr>
<td class="main_td td_G_total" colspan="3">Grand Total</td>

<?php
for ( $i = 0 ; $i < $cnt_stat ; $i++ )	{

	$sum_total[$i] = $sum_ag_ug[0][$i]+$sum_ag_ug[1][$i];
?>

<td class="main_td td_G_total">	<?php if($sum_total[$i]==0) {echo '-';} else {echo $sum_total[$i];} ?></td>

<?PHP
}
	$query_view_pkg_drop = 'DROP VIEW IF EXISTS pkgState'; 
	sql_query ($query_view_pkg_drop);

?>

<td class="main_td td_G_total"><?php if($sum_total[0]==0) {echo 'N/A';} else {echo sprintf("%2.1f",$sum_total[4] / $sum_total[0] * 100)." %";} ?></td>
</tr>
</tbody>
</table>
<p>&nbsp;
<?PHP
}
?>