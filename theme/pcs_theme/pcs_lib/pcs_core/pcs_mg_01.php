<?php

	if ($_POST['dwg_select']=='yes')	{
	
	$table_field_array = array('No.','Dwg no.','Rev no.','AG/UG','Unit','j_size','Class','Material','NDE','PMI','PWHT','Paint','Insulation');
	$table_width_array = array(60,200,60,60,60,60,60,60,60,60,60,60,60);
	$sql_field_array = array('rev_no','ag_ug','unit','line_size','line_class','material','nde_rate','pmi','pwht','paint_code','line_insul');
?>
<table class="main">
<caption> DRAWING DETAIL STATUS </caption>
<tbody>
<tr>
<?php for($i=0;$i<count($table_field_array);$i++){echo '<td class="jnt_td jnt_th">'.$table_field_array[$i].'</td>';} ?>
</tr>

<?php
	$query_dwg = 'SELECT A.* FROM '.G5_TABLE_PREFIX.'pcs_info_iso AS A';
		if($_POST['dwg_stat']!='ACT'){$query_dwg .= ' LEFT';}
	$query_dwg .= ' JOIN '.G5_TABLE_PREFIX.'pcs_info_iso_coor AS B ON A.dwg_no  = B.dwg_no';
		if($_POST['dwg_a_u'])	{$query_dwg .= ' WHERE ag_ug = "'.$_POST['dwg_a_u'].'"';}
		if($_POST['dwg_unit'])	{$query_dwg .= ' AND unit = "'.$_POST['dwg_unit'].'"';}
		$query_dwg .= ' AND state = "'.$_POST['dwg_stat'].'"';
//		if($_GET['dwg_stat']) 	{$query_dwg .= ' AND numbering = ''.str_replace('_',' ',$_GET['dwg_stat']).''';}


	$sql_dwg = sql_query ($query_dwg);

	$no = 0;

		while ($sql_dwg_arr = sql_fetch_array ($sql_dwg))	{
	
			$query_g5_dwg = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_drawing WHERE wr_subject = "'.$sql_dwg_arr['dwg_no'].'"';
			$sql_g5_dwg = sql_query ($query_g5_dwg);
			$sql_g5_dwg_arr = sql_fetch_array ($sql_g5_dwg);
			
			$no++;
?>

<tr>
<td class="jnt_td">	<?php echo $no; ?></td>
<td class="jnt_td"><a href=<?php echo G5_URL.'/bbs/board.php?bo_table=drawing&wr_id='.$sql_g5_dwg_arr['wr_id']; ?> target='_self'> <font style='font-size:25px;'> <b><?php echo $sql_dwg_arr['dwg_no']; ?></b></font></a></td>
<td class="jnt_td">
<font style='font-size:25px;'> <b> <a href = 'javascript:document.submit_for_marked<?php echo $no; ?>.submit()'><b><?php echo $sql_dwg_arr['rev_no']; ?></b> </font> </a> 
<?php viewPDF('submit_for_marked'.$no,'fab',$sql_dwg_arr['dwg_no'],$sql_dwg_arr['rev_no']);?>
</td>

<?php for($i=1;$i<count($sql_field_array);$i++){echo '<td class="jnt_td">'.$sql_dwg_arr[$sql_field_array[$i]].'</td>';}?>
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

	$j_agug = $field_val['ag_ug'];
	$j_material = $field_val['material'];
	$j_unit = $field_val['unit'];
?>


<table class="main">
<caption> WELDING WORK VOLUME </caption>
<tbody>
<tr>
<td class="main_td jnt_th" rowspan="2" colspan="2"> Unit </td>
<td class="main_td jnt_th" rowspan="2"> Total </td>
<td class="main_td jnt_th" colspan="<?php echo count($j_material); ?>"> Material </td>
</tr>

<tr>
<?php
$temp_j = count($j_material);
for($j=0;$j<$temp_j;$j++) { ?>
<td class="main_td jnt_th"><?php echo $j_material[$j]; ?></td>
<?php 	} ?>
</tr>


<?php
$temp_k = count($j_agug);
for ( $k=0 ; $k<$temp_k; $k++ ) {	?>

<tr>
<td class="main_td" style="width: 80px;" rowspan="<?php echo count($j_unit)+1; ?>"><?php echo $j_agug[$k]; ?></td>
</tr>

<?php
	$temp_j = count($j_unit);
	for ($j=0; $j<$temp_j; $j++ )	{	?>	

<tr>
<td class="main_td" ="width:120px;"><?php echo $j_unit[$j]; ?></td>

<?php
		$query_dwg_qty = 'SELECT COUNT(dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'"';
		$sql_dwg_qty = pcs_sql_value ($query_dwg_qty);
		$query_done_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'"';
		$sql_done_SDI = pcs_sql_value ($query_done_SDI);
		$query_total_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'"';
		$sql_total_SDI = pcs_sql_value ($query_total_SDI);
		$query_done_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'"';
		$sql_done_FDI = pcs_sql_value ($query_done_FDI);
		$query_total_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'"';
		$sql_total_FDI = pcs_sql_value ($query_total_FDI);
?>
<td class="jnt_td">
	<div class="stat_left"><?php if($sql_dwg_qty==0) {echo '';} else {echo $sql_dwg_qty.'<sub> Dwgs.';} ?></div>
	<div class="stat_center"><?php if($sql_total_SDI==0) {echo '-';} else {echo sprintf("%.0f",$sql_done_SDI).' / '.sprintf("%.0f",$sql_total_SDI);} ?></div>
	<div class="stat_center"><?php if($sql_total_FDI==0) {;} else {echo sprintf("%.0f",$sql_done_FDI).' / '.sprintf("%.0f",$sql_total_FDI);} ?></div>
	<div class="stat_right"><?php if(($sql_total_SDI + $sql_total_FDI)==0) {echo '';} else {echo '('.sprintf("%.1f",($sql_done_SDI + $sql_done_FDI)/($sql_total_SDI + $sql_total_FDI)*100).'%)';} ?></div>
</td>


<?php
		$temp_i = count($j_material);
		for ( $i=0 ; $i<$temp_i ; $i++ )	{
				
		$query_dwg_qty = 'SELECT COUNT(dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'" AND material = "'.$j_material[$i].'"';
		$sql_dwg_qty = pcs_sql_value ($query_dwg_qty);
		$query_done_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'" AND material = "'.$j_material[$i].'"';
		$sql_done_SDI = pcs_sql_value ($query_done_SDI);
		$query_total_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'" AND material = "'.$j_material[$i].'"';
		$sql_total_SDI = pcs_sql_value ($query_total_SDI);
		$query_done_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'" AND material = "'.$j_material[$i].'"';
		$sql_done_FDI = pcs_sql_value ($query_done_FDI);
		$query_total_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'" AND material = "'.$j_material[$i].'"';
		$sql_total_FDI = pcs_sql_value ($query_total_FDI);
?>

<td class="jnt_td">
	<div class="stat_left"><?php if($sql_dwg_qty==0) {echo '';} else {echo $sql_dwg_qty.'<sub> Dwgs.';} ?></div>
	<div class="stat_center"><?php if($sql_total_SDI==0) {echo '-';} else {echo sprintf("%.0f",$sql_done_SDI).' / '.sprintf("%.0f",$sql_total_SDI);} ?></div>
	<div class="stat_center"><?php if($sql_total_FDI==0) {;} else {echo sprintf("%.0f",$sql_done_FDI).' / '.sprintf("%.0f",$sql_total_FDI);} ?></div>
	<div class="stat_right"><?php if(($sql_total_SDI + $sql_total_FDI)==0) {echo '';} else {echo '('.sprintf("%.1f",($sql_done_SDI + $sql_done_FDI)/($sql_total_SDI + $sql_total_FDI)*100).'%)';} ?></div>
</td>

<?php	}
	} ?>
	
</tr>

<tr>
<td class="main_td td_S_total" colspan="2"> S-Total </td>

<?php	
		$query_dwg_qty = 'SELECT COUNT(dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE ag_ug = "'.$j_agug[$k].'"';
		$sql_dwg_qty = pcs_sql_value ($query_dwg_qty);
		$query_done_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'"';
		$sql_done_SDI = pcs_sql_value ($query_done_SDI);
		$query_total_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'"';
		$sql_total_SDI = pcs_sql_value ($query_total_SDI);
		$query_done_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'"';
		$sql_done_FDI = pcs_sql_value ($query_done_FDI);
		$query_total_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'"';
		$sql_total_FDI = pcs_sql_value ($query_total_FDI);
?>
	<td class="jnt_td td_S_total">
	<div class="stat_left"><?php if($sql_dwg_qty==0) {echo '';} else {echo $sql_dwg_qty.'<sub> Dwgs.';} ?></div>
	<div class="stat_center"><?php if($sql_total_SDI==0) {echo '-';} else {echo sprintf("%.0f",$sql_done_SDI).' / '.sprintf("%.0f",$sql_total_SDI);} ?></div>
	<div class="stat_center"><?php if($sql_total_FDI==0) {;} else {echo sprintf("%.0f",$sql_done_FDI).' / '.sprintf("%.0f",$sql_total_FDI);} ?></div>
	<div class="stat_right"><?php if(($sql_total_SDI + $sql_total_FDI)==0) {echo '';} else {echo '('.sprintf("%.1f",($sql_done_SDI + $sql_done_FDI)/($sql_total_SDI + $sql_total_FDI)*100).'%)';} ?></div>

	</td>
<?php
	$temp_i = count($j_material);
	for ( $i = 0 ; $i < $temp_i ; $i++ )	{	
		$query_dwg_qty = 'SELECT COUNT(dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE ag_ug = "'.$j_agug[$k].'" AND material = "'.$j_material[$i].'"';
		$sql_dwg_qty = pcs_sql_value ($query_dwg_qty);
		$query_done_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'" AND material = "'.$j_material[$i].'"';
		$sql_done_SDI = pcs_sql_value ($query_done_SDI);
		$query_total_SDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "S" AND ag_ug = "'.$j_agug[$k].'" AND material = "'.$j_material[$i].'"';
		$sql_total_SDI = pcs_sql_value ($query_total_SDI);
		$query_done_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND vi_rlt = "Accept" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'" AND material = "'.$j_material[$i].'"';
		$sql_done_FDI = pcs_sql_value ($query_done_FDI);
		$query_total_FDI = 'SELECT SUM(nps) FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE w_type = "WELD" AND s_f = "F" AND ag_ug = "'.$j_agug[$k].'" AND material = "'.$j_material[$i].'"';
		$sql_total_FDI = pcs_sql_value ($query_total_FDI);
?>

	<td class="jnt_td td_S_total">
	<div class="stat_left"><?php if($sql_dwg_qty==0) {echo '';} else {echo $sql_dwg_qty.'<sub> Dwgs.';} ?></div>
	<div class="stat_center"><?php if($sql_total_SDI==0) {echo '-';} else {echo sprintf("%.0f",$sql_done_SDI).' / '.sprintf("%.0f",$sql_total_SDI);} ?></div>
	<div class="stat_center"><?php if($sql_total_FDI==0) {;} else {echo sprintf("%.0f",$sql_done_FDI).' / '.sprintf("%.0f",$sql_total_FDI);} ?></div>
	<div class="stat_right"><?php if(($sql_total_SDI + $sql_total_FDI)==0) {echo '';} else {echo '('.sprintf("%.1f",($sql_done_SDI + $sql_done_FDI)/($sql_total_SDI + $sql_total_FDI)*100).'%)';} ?></div>
	</td>

<?php
	}
}
?>
</tr>



</tbody>
</table>
<p>&nbsp;<p>&nbsp;

<?php
	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_iso';
	$field_val = enum_value($field_query);
	$o_state = $field_val['state'];

	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_iso_coor';
	$field_val = enum_value($field_query);
	$j_state = $field_val['dwg_state'];

	$j_qty = count($j_state)+count($o_state)-1;
?>



<table class="main" style="width:100%">
<caption> JOINT NUMBERING STATUS </caption>
<tbody>
<tr>
<td class="main_td td_sub_pkg1" rowspan="2" colspan="2"> Unit </td>
<td class="main_td td_sub_pkg1" rowspan="2"> Total </td>
<td class="main_td td_sub_pkg1" colspan="<?php echo $j_qty; ?>"> STATE </td>
</tr>

<tr>
<?php
	for($j=0;$j<$j_qty;$j++) {
		if($j<2){$state_j = $j_state[$j];}
		else {$state_j = $o_state[$j-1];}
		echo '<td class="main_td td_sub_pkg1">'.$state_j.'</td>';
	}
?>
</tr>


<?php
$temp_k = count($j_agug);
for ( $k=0 ; $k<$temp_k  ; $k++ ) {	?>

<tr>
<td class="main_td" style="width: 80px;" rowspan="<?php echo count($j_unit)+1; ?>"><?php echo $j_agug[$k]; ?></td>
</tr>

<?php
	$temp_j = count($j_unit);
	for ($j=0;$j<$temp_j;$j++)	{	?>	

<tr>
<td class="main_td" ="width:120px;"><?php echo $j_unit[$j]; ?></td>

<?php
		$sql_dwg_total = 0;
		$query_dwg_total = 'SELECT COUNT(dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE ag_ug = "'.$j_agug[$k].'" AND unit = "'.$j_unit[$j].'"';
		$sql_dwg_total = pcs_sql_value ($query_dwg_total);
?>

<td class="main_td"><?php if($sql_dwg_total==0) {echo '-';} else {echo $sql_dwg_total;} ?></td>


<?php	
//		$temp_i = count($j_state);
		for ($i=0;$i<$j_qty;$i++) {
			if($i<2){dwg_chk_stat($j_agug[$k], $j_unit[$j], $j_state[$i],'ACT');}
			else{dwg_chk_stat($j_agug[$k], $j_unit[$j], $o_state[$i-1]);}
		}
	} ?>
	
</tr>

<tr>
<td class="main_td td_S_total" colspan="2"> S-Total </td>

<?php
		$sql_dwg_total = 0;
		$query_dwg_total = 'SELECT COUNT(dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE ag_ug = "'.$j_agug[$k].'"';
		$sql_dwg_total = pcs_sql_value ($query_dwg_total);

/*		$sql_pdf_total = 0;
		$query_pdf_total = 'SELECT COUNT(A.dwg_no) FROM '.G5_TABLE_PREFIX.'pcs_info_iso AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_iso_coor AS B ON A.dwg_no = B.dwg_no AND A.rev_no = B.rev_no WHERE ag_ug = "'.$j_agug[$k].'" AND B.png_chk <> ''";
		$sql_pdf_total = pcs_sql_value ($query_pdf_total);
		$sql_dwg_total -= $sql_dwg_qty;*/
?>


<td class="main_td td_S_total"><?php if($sql_dwg_total==0) {echo '-';} else {echo $sql_dwg_total;} ?></td>

<?php	for ($i=0;$i<$j_qty;$i++)	{
			if($i<2){$query_dwg_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_iso AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_iso_coor AS B ON A.dwg_no  = B.dwg_no AND B.dwg_state = "'.$j_state[$i].'" WHERE';}
			else{$query_dwg_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE state = "'.$o_state[$i-1].'" AND';}

			$query_dwg_qty .= ' ag_ug = "'.$j_agug[$k].'"';
			$sql_dwg_qty = pcs_sql_value ($query_dwg_qty);
			$sql_dwg_total -= $sql_dwg_qty;
?>

<td class="main_td td_S_total"><?php if($sql_dwg_qty==0) {echo '-';} else {echo $sql_dwg_qty;} ?></td>

<?php	}	?>

<?php
}
?>
</tr>



</tbody>
</table>
<?php
?>
<p>&nbsp;<p>&nbsp;


<?php }

function dwg_chk_stat($au, $un, $num, $st='') {
	if($st=='ACT'){$query_dwg_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_iso AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_iso_coor AS B ON A.dwg_no  = B.dwg_no AND B.dwg_state = "'.$num.'" WHERE';$dwg_st=$st;}
	else{$query_dwg_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE state = "'.$num.'" AND';$dwg_st=$num;}
	
	if($au) {$query_dwg_qty .= ' ag_ug = "'.$au.'"';}
	if($un) {$query_dwg_qty .= ' AND unit = "'.$un.'"';}
	$t_value = pcs_sql_value ($query_dwg_qty);
	
	if($t_value){
		echo '
		<td class="main_td ">
		<a href = "javascript:'.$au.$un.$num.'.submit()" >'.$t_value.'</a>
		<form name="'.$au.$un.$num.'" method="post" onSubmit="return doSumbit()">
		<input type="hidden" name="dwg_select" value="yes">
		<input type="hidden" name="dwg_a_u" value="'.$au.'">
		<input type="hidden" name="dwg_unit" value="'.$un.'">
		<input type="hidden" name="dwg_stat" value="'.$dwg_st.'">
		</form></td>';
	}
	else{echo '<td class="main_td "> - </td>';}
}
?>