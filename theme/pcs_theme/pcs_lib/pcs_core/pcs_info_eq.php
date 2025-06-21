<?php 

	$query_equipment = "SELECT * FROM ".G5_TABLE_PREFIX."pcs_info_equipment WHERE eq_no = '".$view['wr_subject']."'";
	$sql_equipment = sql_query ($query_equipment);
	$sql_equipment_arr = sql_fetch_array ($sql_equipment);


function pdf_v(string $sq,string $eqdw,string $eqcn): void{
	echo '<a href = "javascript:document.submit_'.$sq.'.submit()"> '.$eqcn.' </a>';
	echo '<form name="submit_'.$sq.'" action="'.PCS_WPV_URL.'/viewer.php" method="post" target="result" onSubmit="return doSumbit()">';
	echo '<input type="hidden" name="folder" value="equipment">';
	echo '<input type="hidden" name="file" value="'.$eqdw.'">';
	echo '<input type="hidden" name="rev" value=""></form>';
}
?>

<table class="main" >
<caption> SPECIFICATION </caption>
<tbody>
<tr>
<td class="main_td td_sub" style="height:80px;" colspan="6"> <b> <?php echo $sql_equipment_arr['description'];?> </b> </td>

</tr>
<tr>
<td class="main_td" style="height:80px;" > <?php pdf_v(1,$sql_equipment_arr['dwg_1'],'Foundation');?></td>
<td class="main_td" style="height:80px; font-size:25px;" > <?php pdf_v(2,$sql_equipment_arr['dwg_2'],'General Arrangement');?></td>
<td class="main_td" style="height:80px;" > <?php pdf_v(3,$sql_equipment_arr['dwg_3'],'M/H, NOZZLE');?></td>
<td class="main_td" style="height:80px;" > <?php pdf_v(4,$sql_equipment_arr['dwg_4'],'P/F, LADDER');?></td>
<td class="main_td" style="height:80px;" > <?php pdf_v(5,$sql_equipment_arr['dwg_5'],'INTERNAL');?></td>
<td class="main_td" style="height:80px;" > <?php pdf_v(6,$sql_equipment_arr['dwg_6'],'ETC.');?></td>
</tr>

<tr>
<?php
	$queryISO = "SELECT dwg_no, con_eq FROM ".G5_TABLE_PREFIX."pcs_info_drawing WHERE con_eq LIKE '%".$view['wr_subject']."%'";
	$sqlISO = sql_query ($queryISO);

	if($sqlISO){
		echo '<td class="main_td" colspan=6 style="background-color: #F6D8CE; height:50px;"><b>CONNECTED DRAWING</td></tr>';
	
		$j=0;

		while ($sqlISO_arr = sql_fetch_array ($sqlISO)){
			$query_con_dwg = "SELECT wr_id FROM ".G5_TABLE_PREFIX."write_drawing WHERE wr_subject = '".$sqlISO_arr['dwg_no']."'";
			$sql_con_dwg = sql_query ($query_con_dwg);
			$sql_con_dwg_arr = sql_fetch_array ($sql_con_dwg);

			echo '<td class="jnt_td" style="height:80px;font-size:18px;">';

			$j++;
			$noz_arr = explode(";",$sqlISO_arr['con_eq']);
			for($noz=0;$noz<count($noz_arr)-1;$noz++){ $eq_nozzle = str_replace($view['wr_subject'].'_',"",$noz_arr[$noz]); }

			if($sql_con_dwg_arr['wr_id']){ echo '<a href='.G5_BBS_URL.'/board.php?bo_table=drawing&wr_id='.$sql_con_dwg_arr['wr_id'].'> <b>'.$eq_nozzle.' : '.$sqlISO_arr['dwg_no'].'</b></a></td>';}
			else {echo '<mark>'.$sqlISO_arr['dwg_no'].'</mark></td>';}
		
			if($j%6==0){echo'</tr><tr>';}	
		}
		if($j % 6 !== 0){for($k=0;$k<6-($j%6);$k++){echo '<td class="main_td"></td>';}}
	}
?>
</tr>

</tbody></table>

<p>&nbsp;</p>