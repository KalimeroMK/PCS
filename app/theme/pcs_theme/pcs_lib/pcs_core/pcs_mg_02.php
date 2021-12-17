<?php
if($_POST['spl_select']=='yes'){

	$table_field_array = array('No.','Spool no.','AG/UG','Unit','Welding','PWHT','PMI','State','Location');
	$table_width_array = array(40,150,60,60,80,80,80,80,80,80,80);
	$sql_field_array = array('ag_ug','unit','st_weld','st_pwht','st_pmi','state');
?>
<table class="main">
<caption> SPOOL DETAIL STATUS </caption>
<tbody>
<tr>
<?php 
	for($i=0;$i<count($table_field_array);$i++){echo '<td class="jnt_td jnt_th" style="width: '.$table_width_array[$i].'px">'.$table_field_array[$i].'</td>';} ?>
</tr>

<?php
	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_spool';
	$field_val = enum_value($field_query);

	$query_spool = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_spool';

//	$query_spool =  'SELECT A.*, B.* FROM '.G5_TABLE_PREFIX.'pcs_info_spool AS A LEFT JOIN '.G5_TABLE_PREFIX.'pcs_info_spl_stat AS B ON A.spool_no = B.spool_no';

		if($_POST['spl_a_u'])	{$query_spool .= ' WHERE ag_ug = "'.$_POST['spl_a_u'].'"';}
		if($_POST['spl_unit'])	{$query_spool .= ' AND unit = "'.$_POST['spl_unit'].'"';}
		if($_POST['spl_stat'] || $_POST['spl_loca']) {
			if($_POST['spl_stat']) {$query_spool .= ' AND state = "'.$_POST['spl_stat'].'"';}	else {$query_spool .= ' AND state = "Finished"';}
			if($_POST['spl_loca']) {$query_spool .= ' AND location = "'.$_POST['spl_loca'].'"';}
		}

	
	$sql_spool = sql_query ($query_spool);

	$no = 0;

	while ($sql_spool_arr = sql_fetch_array ($sql_spool))	{	
		$no++;
		$ref_dwg = substr($sql_spool_arr['spool_no'],0,strpos($sql_spool_arr['spool_no'],'-SP'));
		$query_g5_spool = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_spool WHERE wr_subject = "'.$sql_spool_arr['spool_no'].'"';
		$sql_g5_spool = sql_query ($query_g5_spool);
		$sql_g5_spool_arr = sql_fetch_array ($sql_g5_spool);	
		
		$query_g5_dwg = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$ref_dwg.'"';
		$sql_g5_dwg = sql_query ($query_g5_dwg);
		$sql_g5_dwg_arr = sql_fetch_array ($sql_g5_dwg);

?>

<tr>
<td class="jnt_td">
	<a href = 'javascript:document.smt_<?php echo $no; ?>.submit()'><font style='font-size:25px;'> <b> <?php echo $no; ?> </b> </font></a>
<?php viewPDF('smt_'.$no,'fab',$ref_dwg,$sql_g5_dwg_arr['rev_no']); ?>
</td>
<td class="jnt_td">
	<a href=<?php echo G5_URL.'/bbs/board.php?bo_table=spool&wr_id='.$sql_g5_spool_arr['wr_id']; ?> target='_self'> <font style='font-size:25px;'> <b> <?php echo $sql_spool_arr['spool_no']; ?></b></font></a>
</td>

<?php for($i=0;$i<count($sql_field_array);$i++){echo '<td class="jnt_td">'.str_replace('_',' ',$sql_spool_arr[$sql_field_array[$i]]).'</td>';}?>
<td class="jnt_td"> <?php echo $sql_spool_arr['location']; ?></a></td>
</tr>

<?php
	}
?>

</tbody>
</table>
<p>&nbsp;

<?php
}
else {
	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_iso';
	$field_val = enum_value($field_query);

	$row_agug = $field_val['ag_ug'];
	$row_unit = $field_val['unit'];

	
	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_spl_stat';
	$field_val = enum_value($field_query);

	$col_stat = $field_val['state'];
	$col_loca = $field_val['location'];
?>

<table class="main">
<caption> Spool Status </caption>
<tbody>
<tr>
<td class="main_td jnt_th" rowspan="2" colspan="2">Unit</td>
<td class="main_td jnt_th" rowspan="2">Total</td>
<td class="main_td jnt_th" colspan="3">Fabrication</td>
<td class="main_td jnt_th" colspan="6">Location</td>
</tr>
<tr>
<?php
	for ($i=0;$col_stat[$i];$i++)	{ echo '<td class="main_td jnt_th" style="font-size:20px;">'.str_replace('_',' ',$col_stat[$i]).'</td>';}
	for ($i=0;$col_loca[$i];$i++)	{ echo '<td class="main_td jnt_th" style="font-size:20px;">'.$col_loca[$i].'</td>';}
?>
</tr>

<?php
	for ( $k = 0 ; $row_agug[$k] ; $k++ ) {
?>
<tr>
<td class="main_td" rowspan="<?php echo count($row_unit)+1; ?>"><?php echo $row_agug[$k]; ?></td>
</tr>
<?php
		for ( $j = 0 ; $row_unit[$j] ; $j++ )	{
?>	
<tr>
<td class="main_td"><?php echo $row_unit[$j]; ?></td>
<?php
		spl_fab_stat($row_agug[$k],$row_unit[$j],'','');
		
		for ($i=0;$col_stat[$i];$i++)	{spl_fab_stat($row_agug[$k],$row_unit[$j],$col_stat[$i],'');}

		for ($i=0;$col_loca[$i];$i++)	{spl_loc_stat($row_agug[$k],$row_unit[$j],'',$col_loca[$i]);}
	}
?>
</tr>
<tr>
<td class="main_td td_S_total" colspan="2">S-Total</td>
<?php
	spl_fab_stat($row_agug[$k],'','','','td_S_total');
	for ($i=0;$col_stat[$i];$i++)	{spl_fab_stat($row_agug[$k],'',$col_stat[$i],'','td_S_total');}
	for ($i=0;$col_loca[$i];$i++)	{spl_loc_stat($row_agug[$k],'','',$col_loca[$i],'td_S_total');}
}
?>
</tr>
<tr>
<td class="main_td td_G_total" colspan="2">G-Total</td>
<?php
spl_fab_stat('','','','','td_G_total');
for ($i=0;$col_stat[$i];$i++)	{spl_fab_stat('','',$col_stat[$i],'','td_G_total');}
for ($i=0;$col_loca[$i];$i++)	{spl_loc_stat('','','',$col_loca[$i],'td_G_total');}
?>
</tr>
</tbody>
</table>
<p>&nbsp;
<?php
}
function spl_fab_stat($au, $un, $st, $lo, $co='') {
	
	$query_spl_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_spool';
	if($au) {$query_spl_qty .= ' WHERE ag_ug = "'.$au.'"';}
	if($un) {$query_spl_qty .= ' AND unit = "'.$un.'"';}
	if($st||$lo) {
		if($st) {$query_spl_qty .= ' AND state = "'.$st.'"';}	else {$query_spl_qty .= ' AND state = "Finished"';}
		if($lo) {$query_spl_qty .= ' AND location = "'.$lo.'"';}
	}
	if(pcs_sql_value ($query_spl_qty)){
		echo '<td class="main_td '.$co.'">
			<a href = "javascript:document.'.$au.$un.$st.$lo.'.submit()">'.pcs_sql_value ($query_spl_qty).'</a>
			<form name="'.$au.$un.$st.$lo.'" method="post" target="_self" onSubmit="return doSumbit()"> 
			<input type="hidden" name="spl_select" value="yes">
			<input type="hidden" name="spl_a_u" value="'.$au.'">
			<input type="hidden" name="spl_unit" value="'.$un.'">
			<input type="hidden" name="spl_stat" value="'.$st.'">
			<input type="hidden" name="spl_loca" value="'.$lo.'">
			</form>';
	}
	else{echo '<td class="main_td '.$co.'"> - </td>';}
}

function spl_loc_stat($au, $un, $st, $lo, $co='') {
	
	$query_spl_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_spool';
	if($au) {$query_spl_qty .= ' WHERE ag_ug = "'.$au.'"';}
	if($un) {$query_spl_qty .= ' AND unit = "'.$un.'"';}
	if($st||$lo) {
		if($st) {$query_spl_qty .= ' AND state = "'.$st.'"';}	else {$query_spl_qty .= ' AND state = "Finished"';}
		if($lo) {$query_spl_qty .= ' AND location = "'.$lo.'"';}
	}
	if(pcs_sql_value ($query_spl_qty)){
		echo '<td class="main_td '.$co.'">
			<a href = "javascript:document.'.$au.$un.$st.$lo.'.submit()">'.pcs_sql_value ($query_spl_qty).'</a>
			<form name="'.$au.$un.$st.$lo.'" action="'.G5_URL.'/bbs/board.php?bo_table=inspection&wr_id=2" method="post" target="_self" onSubmit="return doSumbit()"> 
			<input type="hidden" name="btn_check" value="yes">
			<input type="hidden" name="ag_ug" value="'.$au.'">
			<input type="hidden" name="unit" value="'.$un.'">
			<input type="hidden" name="location" value="'.$lo.'">
			</form>';
	}
	else{echo '<td class="main_td '.$co.'"> - </td>';}
}
?>

