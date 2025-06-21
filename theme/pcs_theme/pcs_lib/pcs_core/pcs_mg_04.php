<?php

	if (!empty($_POST['tp_select']) && $_POST['tp_select']=='yes') {
        $table_field_array = array('No.','Dwg no.','Rev no.','AG/UG','Unit','j_size','Class','Material','NDE','PMI','PWHT','Paint','Insulation');
        $table_width_array = array(60,200,60,60,60,60,60,60,60,60,60,60,60);
        $sql_field_array = array('rev_no','ag_ug','unit','line_size','line_class','material','nde_rate','pmi','pwht','paint_code','line_insul');
        ?>
<table class="main">
<caption> Tie-in Point STATUS </caption>
<tbody>
<tr>
	<td class="jnt_td jnt_th" style="width: 5%"> No. </td>
	<td class="jnt_td jnt_th" style="width: 20%"> TP No. </td>
	<td class="jnt_td jnt_th" style="width: 10%"> Photo 1 by /<br> Photo 1 time </td>
	<td class="jnt_td jnt_th" style="width: 15%"> Photo 1 </td>
	<td class="jnt_td jnt_th" style="width: 10%"> Photo 2 by /<br> Photo 2 time </td>
	<td class="jnt_td jnt_th" style="width: 15%"> Photo 2 </td>
	<td class="jnt_td jnt_th" style="width: 10%"> Photo 3 by /<br> Photo 3 time </td>
	<td class="jnt_td jnt_th" style="width: 15%"> Photo 3 </td>
</tr>

<?php 
        $query_tp_spl = 'SELECT A.*, B.* FROM '.G5_TABLE_PREFIX.'pcs_info_tp AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_tp_stat AS B ON A.tp_no = B.tp_no WHERE tp_photo'.$_POST['tp_num'].'_tm != "0000-00-00 00:00:00" AND unit = "'.$_POST['tp_unit'].'"';
        $sql_tp_spl = sql_query ($query_tp_spl);
        while ($sql_tp_spl_arr = sql_fetch_array ($sql_tp_spl))	{
    	
    		$no++;
    	
    		$query_g5_spl = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_tp WHERE wr_subject = "'.$sql_tp_spl_arr['tp_no'].'"';
    		$sql_g5_spl = sql_query ($query_g5_spl);
    		$sql_g5_spl_arr = sql_fetch_array ($sql_g5_spl);
    ?>
    <tr>
    <td class="jnt_td"><?php echo $no; ?></td>
    <td class="jnt_td"><a href=<?php echo G5_URL.'/bbs/board.php?bo_table=tp&wr_id='.$sql_g5_spl_arr['wr_id']; ?> target='_self'> <font style='font-size:25px;'> <b> <?php echo $sql_tp_spl_arr['tp_no']; ?></b></font></a></td>
    <td class="jnt_td"><?php if($sql_tp_spl_arr['tp_photo1_by']){echo $sql_tp_spl_arr['tp_photo1_by'].'<br>'.$sql_tp_spl_arr['tp_photo1_tm'];} ?></td>
    <td class="jnt_td"><?php if($sql_tp_spl_arr['tp_photo1_by']){photo_thumb('tp', $sql_tp_spl_arr['tp_photo1'], '', 150);} ?></td>
    <td class="jnt_td"><?php if($sql_tp_spl_arr['tp_photo2_by']){echo $sql_tp_spl_arr['tp_photo2_by'].'<br>'.$sql_tp_spl_arr['tp_photo2_tm'];} ?></td>
    <td class="jnt_td"><?php if($sql_tp_spl_arr['tp_photo2_by']){photo_thumb('tp', $sql_tp_spl_arr['tp_photo2'], '', 150);} ?></td>
    <td class="jnt_td"><?php if($sql_tp_spl_arr['tp_photo3_by']){echo $sql_tp_spl_arr['tp_photo3_by'].'<br>'.$sql_tp_spl_arr['tp_photo3_tm'];} ?></td>
    <td class="jnt_td"><?php if($sql_tp_spl_arr['tp_photo3_by']){photo_thumb('tp', $sql_tp_spl_arr['tp_photo3'], '', 150);} ?></td>
    </tr>
    
    <?php 
     	}
        ?>
</tbody>
</table>

<p>&nbsp;


<?php 
    } else {


	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_tp';
	$field_val = enum_value($field_query);

	$j_area = $field_val['unit'];
?>


<table class="main">
<caption> Tie-in Work Status </caption>
<tbody>
<tr>
<td class="main_td jnt_th" rowspan="2"> AREA </td>
<td class="main_td jnt_th" rowspan="2"> TOTAL </td>
<td class="main_td jnt_th" colspan="3"> PHOTO </td>
</tr>
<tr>
<td class="main_td jnt_th" > 3D Model </td>
<td class="main_td jnt_th" > Tie-In Place </td>
<td class="main_td jnt_th" > Work Done </td>
</tr>


<?php
	$temp_j = count($j_area);
	for ($j=0; $j<$temp_j; $j++ )	{	?>	

<tr>
<td class="main_td" ="width:120px;"><?php echo $j_area[$j]; ?></td>

<?php
		$query_tp_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_tp WHERE unit = "'.$j_area[$j].'"';
		$sql_tp_qty = pcs_sql_value ($query_tp_qty);
?>
<td class="main_td" ="width:120px;"><?php echo $sql_tp_qty; ?></td>


<?php
		for ( $i=1 ; $i<4 ; $i++ )	{
				
			$query_tp_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_tp AS A LEFT JOIN '.G5_TABLE_PREFIX.'pcs_info_tp_stat AS B ON A.tp_no = B.tp_no WHERE tp_photo'.$i.'_tm != "0000-00-00 00:00:00" AND unit = "'.$j_area[$j].'"';
			$sql_tp_qty = pcs_sql_value ($query_tp_qty);
			tp_chk_stat($j_area[$j],$i);
		}
	} ?>
	
</tr>

<tr>
<td class="main_td td_S_total" > S-Total </td>

<?php	
		$query_tp_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_tp ';
		$sql_tp_qty = pcs_sql_value ($query_tp_qty);

?>
<td class="main_td td_S_total" ><?php echo $sql_tp_qty; ?> </td>
<?php
		for ( $i=1 ; $i<4 ; $i++ )	{
				
			$query_tp_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_tp AS A LEFT JOIN '.G5_TABLE_PREFIX.'pcs_info_tp_stat AS B ON A.tp_no = B.tp_no WHERE tp_photo'.$i.'_tm != "0000-00-00 00:00:00"';
			$sql_tp_qty = pcs_sql_value ($query_tp_qty);
			echo '<td class="main_td td_S_total" ="width:120px;">'.$sql_tp_qty.'</td>';
		}
?>
</tr>



</tbody>
</table>
<p>&nbsp;<p>&nbsp;


<p>&nbsp;<p>&nbsp;


<?php }

function tp_chk_stat(string $un, string $num): void {
	
	$query_tp_qty = 'SELECT COUNT(*) FROM '.G5_TABLE_PREFIX.'pcs_info_tp AS A LEFT JOIN '.G5_TABLE_PREFIX.'pcs_info_tp_stat AS B ON A.tp_no = B.tp_no WHERE tp_photo'.$num.'_tm != "0000-00-00 00:00:00" AND unit = "'.$un.'"';
	
	if(pcs_sql_value ($query_tp_qty)){
		echo '
		<td class="main_td ">
		<a href = "javascript:A'.str_replace('-','_',$un).$num.'.submit()" >'.pcs_sql_value ($query_tp_qty).'</a>
		<form name="A'.str_replace('-','_',$un).$num.'" method="post" onSubmit="return doSumbit()">
		<input type="hidden" name="tp_select" value="yes">
		<input type="hidden" name="tp_unit" value="'.$un.'">
		<input type="hidden" name="tp_num" value="'.$num.'">
		</form></td>';
	}
	else{echo '<td class="main_td "> - </td>';}
}
?>