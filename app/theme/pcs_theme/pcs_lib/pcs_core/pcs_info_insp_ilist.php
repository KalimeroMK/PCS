<?php
	$insp_type = $_POST['inspection'];

	if($insp_type=='RT'||$insp_type=='MT'||$insp_type=='PT'||$insp_type=='PAUT') {$insp_type='nde';}

	$date_fr = $_POST['sel_fr'];
	$date_to = $_POST['sel_to'];

	$temptbl = GenerateString(15);
	
	$query_view_insp_jnt = 'CREATE VIEW '.$temptbl.' AS SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc WHERE ';
	
	$tempcnti = count($mysql_field_array)-1;
	for($i=$tempcnti; $mysql_field_array[$i]; $i--){

		switch($i) {
			case 7 :
				if($_POST[$mysql_field_array[6]]=='Request') {$query_view_insp_jnt .= '( "'.$date_fr.' 00:00:00" <= pcs_'.$insp_type.'_req_'.$mysql_field_array[$i].' AND pcs_'.$insp_type.'_req_'.$mysql_field_array[$i].' <= "'.$date_to.' 23:59:59" )';}
				else {$query_view_insp_jnt .= '( "'.$date_fr.' 00:00:00" <= pcs_'.$insp_type.'_rlt_'.$mysql_field_array[$i].' AND pcs_'.$insp_type.'_rlt_'.$mysql_field_array[$i].' <= "'.$date_to.' 23:59:59" )';}
				break;

			case 6 :
				if($_POST[$mysql_field_array[$i]]) {$query_view_insp_jnt .= ' AND pcs_'.$insp_type.'_'.$mysql_field_array[$i].' = "'.$_POST[$mysql_field_array[$i]].'"';}
				break;

			case 5 :
				if($_POST[$mysql_field_array[$i]]) {$query_view_insp_jnt .= ' AND pcs_'.$insp_type.'_'.$mysql_field_array[$i].' = "'.$_POST[$mysql_field_array[$i]].'"';}
				break;

			case 1 :
				if($_POST[$mysql_field_array[$i]]) {$query_view_insp_jnt .= ' AND '.$mysql_field_array[$i].' LIKE "%'.$_POST[$mysql_field_array[$i]].'%"';}
				break;

			case 0 : break;

			default:
				if($_POST[$mysql_field_array[$i]]) {$query_view_insp_jnt .= ' AND '.$mysql_field_array[$i].' = "'.$_POST[$mysql_field_array[$i]].'"';}
				break;
		}
	}
	$query_view_insp_jnt .= ' ORDER BY dwg_no, j_no';
	
	sql_query ($query_view_insp_jnt);
	
	
	$sql_insp_dwg = sql_query ('SELECT DISTINCT dwg_no FROM '.$temptbl);
	echo '	<form name="dwglist" method="post" action="'.PCS_CORE_URL.'/pcs_mark_inspISO.php" target="temp" onSubmit="return doSumbit()">';
	
	while ($sql_insp_dwg_arr = sql_fetch_array ($sql_insp_dwg))	{echo '<input type="hidden" name="sel_dwg[]" value="'.$sql_insp_dwg_arr['dwg_no'].'"/>';}
	
	echo '	<input type="hidden" name="insp_tp" value="'.strtoupper($insp_type).'"/>
			<input type="hidden" name="insp_rlt" value="'.strtoupper($_POST[$mysql_field_array[6]]).'"/>
	';

?>
<table class="main">
<caption> <a href = 'javascript:document.dwglist.submit()'><?php echo strtoupper($insp_type); ?> INSPECTION STATUS </a></caption>
<tbody>
<tr>
	<td class="jnt_td jnt_th"> No. </td>
	<td class="jnt_td jnt_th"> ISO Dwg No. </td>
	<td class="jnt_td jnt_th"> Photo 1 </td>
	<td class="jnt_td jnt_th"> Photo 2 </td>
	<td class="jnt_td jnt_th"> Joint </td>
	<td class="jnt_td jnt_th"> Size </td>
	<td class="jnt_td jnt_th"> Schedule </td>
	<td class="jnt_td jnt_th"> S/F </td>
	<td class="jnt_td jnt_th"> Type </td>
	<td class="jnt_td jnt_th"> Material 1 </td>
	<td class="jnt_td jnt_th"> Material 2 </td>
	<td class="jnt_td jnt_th"> Result </td>
	<td class="jnt_td jnt_th"> Date </td>
</tr>

<?php

	$no == 0;
	
	$query_insp_jnt = 'SELECT * FROM '.$temptbl;

	$sql_insp_jnt = sql_query ($query_insp_jnt);
	
	

	while ($sql_insp_jnt_arr = sql_fetch_array ($sql_insp_jnt))	{
	
		$query_rev = 'SELECT rev_no FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$sql_insp_jnt_arr['dwg_no'].'"';
		$sql_rev = sql_query ($query_rev);
		$sql_rev_arr = sql_fetch_array ($sql_rev);
		
		$query_g5_dwg = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_iso WHERE wr_subject = "'.$sql_insp_jnt_arr['dwg_no'].'"';
		$sql_g5_dwg = sql_query ($query_g5_dwg);
		$sql_g5_dwg_arr = sql_fetch_array ($sql_g5_dwg);
		
		$photo_path = PCS_ISO_URL.'/'.$sql_insp_jnt_arr['dwg_no'].'/';
		
		preg_match('/[A-Z1-9][A-Z0-9]*/',$sql_insp_jnt_arr['j_no'],$mts);
		if($_POST['rlt']=='Request'){
			$insp_date = $sql_insp_jnt_arr['pcs_'.$insp_type.'_req_date'];
		}
		else {
			$insp_date = $sql_insp_jnt_arr['pcs_'.$insp_type.'_rlt_date'];
		}

		
		echo '<input type="hidden" name="curr_jnt['.$sql_insp_jnt_arr['dwg_no'].'][]" value="'.$mts[0].'"/>
			  <input type="hidden" name="chk_by['.$sql_insp_jnt_arr['dwg_no'].'][]" value="'.$sql_insp_jnt_arr['pcs_'.$insp_type.'_rlt_by'].'"/>';
		
		$no++;
?>

	
<tr>

<td class="jnt_td">	<font style='font-size:25px;'> <?php echo $no; ?> </td>
<td class="jnt_td"><a href=<?php echo G5_URL.'/bbs/board.php?bo_table=iso&wr_id='.$sql_g5_dwg_arr['wr_id']; ?> target='_self'> <font style='font-size:25px;'> <b><?php echo $sql_insp_jnt_arr['dwg_no']; ?></b></font></a></td>

<td class="jnt_td"><?php if($sql_insp_jnt_arr['photo_1']){echo '<a onclick=\'window.open("'.$photo_path.'/'.$sql_insp_jnt_arr['photo_1'].'.jpg","'.$sql_insp_jnt_arr['photo_1'].'","width=650, height=500, left=200, top=100");\'><img src="'.$photo_path.'/'.$sql_insp_jnt_arr['photo_1'].'.jpg" width="120px"><br>';} ?></td>
<td class="jnt_td"><?php if($sql_insp_jnt_arr['photo_2']){echo '<a onclick=\'window.open("'.$photo_path.'/'.$sql_insp_jnt_arr['photo_2'].'.jpg","'.$sql_insp_jnt_arr['photo_2'].'","width=650, height=500, left=200, top=100");\'><img src="'.$photo_path.'/'.$sql_insp_jnt_arr['photo_2'].'.jpg" width="120px"><br>';} ?></td>
<td class="jnt_td"><?php echo $mts[0]; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['j_size']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['j_sche']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['s_f']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['j_type']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['item_1_type']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['item_2_type']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_jnt_arr['pcs_'.$insp_type.'_rlt']; ?></td>
<td class="jnt_td">
<?php
echo $sql_insp_jnt_arr['pcs_'.$insp_type.'_rlt_by'].'<br>'.substr($insp_date, 0, 16)
?>
</td>
</tr>

<?php 
 	}
	echo '</form>';
	
	
	$query_view_insp_jnt = 'DROP VIEW IF EXISTS '.$temptbl; 
	sql_query ($query_view_insp_jnt);

?>
</tbody>
</table>
<p>&nbsp;
