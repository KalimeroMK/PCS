<?php

if ($_GET['wdr_select']=='welder_by')	{

	$rt_arr = array(5,20,100);

?>

<p style="text-align:center; font-size:50px;" >WELDER RT STATUS</p>
<table class="main">
<tbody>
<tr>
<td class="jnt_td jnt_th" style="width:200px" rowspan='2'>Welder no</td>

$counter = count($rt_arr);<?php
	for($i=0;$i<$counter;$i++){
?>
<td class="jnt_td jnt_th" colspan='5'><?php echo $rt_arr[$i]; ?>%</td>
<?php
	}
?>
</tr>
<tr>

$counter = count($rt_arr);<?php
	for($i=0;$i<$counter;$i++){
?>
<td class="jnt_td jnt_th" style="width:100px">Welded</td>
<td class="jnt_td jnt_th" style="width:100px">RT Req</td>
<td class="jnt_td jnt_th" style="width:100px">Accept</td>
<td class="jnt_td jnt_th" style="width:100px">Reject</td>
<td class="jnt_td jnt_th" style="width:100px">Rate</td>

<?php
	}
?>
</tr>
<tr>
<td class="jnt_td" style="width:200px"><font style="font-size:25px;"> <?php echo $_GET['wdno']; ?> </font></td>



$counter = count($rt_arr);<?php
	for($i=0;$i<$counter;$i++){
		$query_rt = "SELECT count(j_no) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$_GET['wdno']."' AND j_type = 'BW' AND rt_rate = '".$rt_arr[$i]."' ";
		$rt_shoot = pcs_sql_value ($query_rt);
		echo '<td class="jnt_td" >'.$rt_shoot.'</td>';

		echo '<td class="jnt_td" >'.ceil(pcs_sql_value ($query_rt)*$rt_arr[$i]/100).'</td>';

		$query_rt_acc = $query_rt."AND rt_desc = '' AND nde_rlt = 'Accept'";
		echo '<td class="jnt_td" >'.pcs_sql_value ($query_rt_acc).'</td>';

		$query_rt_rej = $query_rt."AND nde_rlt = 'Reject'";
		$rt_reject = pcs_sql_value ($query_rt_rej);
		echo '<td class="jnt_td" >'.$rt_reject.'</td>';

		echo '<td class="jnt_td" >'.round($rt_reject*100/$rt_shoot,2).'</td>';
	}
}


if ($_GET['wdr_select']=='daily_by')	{

?>

<p style="text-align:center; font-size:50px;" >JOINT STATUS</p>
<table class="main">
<tbody>
<tr>
	<td class="jnt_td jnt_th" style="width: 50px"> No </td>
	<td class="jnt_td jnt_th" style="width:300px"> Drawing No </td>
	<td class="jnt_td jnt_th" style="width:100px"> J.No </td>
	<td class="jnt_td jnt_th" style="width:100px"> Type </td>
	<td class="jnt_td jnt_th" style="width:100px"> S / F </td>
	<td class="jnt_td jnt_th" style="width:100px"> NPS </td>
	<td class="jnt_td jnt_th" style="width:100px"> Welder </td>
	<td class="jnt_td jnt_th" style="width:200px"> Item 1 </td>
	<td class="jnt_td jnt_th" style="width:200px"> Item 2 </td>
</tr>

<?php
	$query_daily_list = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE s_f = "F" AND ft_date = "'.$_GET['wdate'].'" ORDER BY welder_1';
	$sql_daily_list = sql_query ($query_daily_list);

	while ($sql_daily_list_arr = sql_fetch_array ($sql_daily_list))	{
		$jnt_qty++;
		$query_pcs_list = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_jnt_krp WHERE j_key="'.$sql_daily_list_arr['j_key'].'"';
		$sql_pcs_list = sql_query ($query_pcs_list);
		$sql_sql_pcs_list_arr = sql_fetch_array ($sql_pcs_list);

		$query_rev = "SELECT rev_no FROM ".G5_TABLE_PREFIX."pcs_info_drawing WHERE dwg_no = '".$sql_daily_list_arr['dwg_no']."' ";
		$sql_rev = sql_query ($query_rev);
		$sql_rev_arr = sql_fetch_array ($sql_rev);

		$query_g5_dwg = "SELECT wr_id FROM ".G5_TABLE_PREFIX."write_drawing WHERE wr_subject = '".$sql_daily_list_arr['dwg_no']."'";
		$sql_g5_dwg = sql_query ($query_g5_dwg);
		$sql_g5_dwg_arr = sql_fetch_array ($sql_g5_dwg);

?>
<tr>
	<td class="jnt_td" style="width: 50px"> <a href = 'javascript:document.submit_for<?php echo $jnt_qty;?>.submit()'> <font style='font-size:25px;'> <b> <?php echo $jnt_qty; ?> </b></font></a></td>
		<form name='submit_for<?php echo $jnt_qty;?>' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post" target="<?php echo $sql_daily_list_arr['dwg_no'];?>" onSubmit="return doSumbit()"> 
		<input type="hidden" name="folder" value="dwg_pdf">
		<input type="hidden" name="file" value="<?php echo $sql_daily_list_arr['dwg_no'];?>">
		<input type="hidden" name="rev" value="<?php echo $sql_rev_arr['rev_no'];?>">
		</form>
	<td class="jnt_td" style="width:300px"> <a href=<?php echo G5_URL.'/app/board/board.php?bo_table=drawing&wr_id='.$sql_g5_dwg_arr[wr_id]; ?> target='_self'> <font style='font-size:25px;'> <b> <?php echo $sql_daily_list_arr['dwg_no']; ?> </b></font></a></td>
	<td class="jnt_td" style="width:100px"> <?php preg_match('/[A-Z1-9][A-Z0-9]*/',$sql_daily_list_arr['j_no'],$mts);	echo $mts[0]; ?> </td>
	<td class="jnt_td" style="width:100px"> <?php echo $sql_daily_list_arr['j_type']; ?> </td>
	<td class="jnt_td" style="width:100px"> <?php echo $sql_daily_list_arr['s_f']; ?> </td>
	<td class="jnt_td" style="width:100px"> <?php echo $sql_daily_list_arr['nps']; ?> </td>
	<td class="jnt_td" style="width:100px"> <?php echo $sql_daily_list_arr['welder_1']; ?> </td>
	<td class="jnt_td" style="width:200px"> <?php if($sql_sql_pcs_list_arr['item_1_photo']){echo "<a onclick=\"window.open('".PCS_THUMB_URL.'/'.$sql_sql_pcs_list_arr['item_1_photo'].".jpg','w','width=600');\">".'<img src="'.PCS_THUMB_URL.'/thumb-'.$sql_sql_pcs_list_arr['item_1_photo'].'_120x90.jpg"><br>';} ?> </td>
	<td class="jnt_td" style="width:200px"> <?php if($sql_sql_pcs_list_arr['item_2_photo']){echo "<a onclick=\"window.open('".PCS_THUMB_URL.'/'.$sql_sql_pcs_list_arr['item_2_photo'].".jpg','w','width=600');\">".'<img src="'.PCS_THUMB_URL.'/thumb-'.$sql_sql_pcs_list_arr['item_2_photo'].'_120x90.jpg"><br>';} ?> </td>
</tr>

<?php		
	}	
?>


<?php
}

if (!$_GET['wdr_select']) {
	$bf_1day = substr(date('Y-m-d H:i:s', time() - (3600 * 6) ), 0, 10);
	$bf_week = substr(date('Y-m-d H:i:s', time() - (3600 * 6) - (3600 * 24 * 7) ), 0, 10);
	$bf_month = substr(date('Y-m-d H:i:s', time() - (3600 * 6) - (3600 * 24 * 30) ), 0, 10);

	for($i=0;$i<8;$i++){$wdate[$i] = substr(date('Y-m-d H:i:s', time() - (3600 * 6) - (3600 * 24 * (7-$i)) ), 0, 10);}
?>
<table class="main">
<caption> WELDER STATUS </caption>
<tbody>
<tr>
<td class="jnt_td jnt_th" rowspan='2'>Welder</td>
<td class="jnt_td jnt_th" colspan='7'>Last 7 Days</td>
<td class="jnt_td jnt_th" colspan='2'>1 Week</td>
<td class="jnt_td jnt_th" colspan='2'>1 Month</td>
<td class="jnt_td jnt_th" colspan='2'>Accumulation</td>
</tr>
<tr>
<?php for($i=0;$i<7;$i++){echo '<td class="jnt_td jnt_th" >'.$wdate[$i].'</td>';} ?>
<td class="jnt_td jnt_th">Total</td>
<td class="jnt_td jnt_th">Average</td>
<td class="jnt_td jnt_th">Total</td>
<td class="jnt_td jnt_th">Average</td>
<td class="jnt_td jnt_th">Total</td>
<td class="jnt_td jnt_th">Average</td>
</tr>
<?php
	$query_welder_list = 'SELECT DISTINCT welder_1 FROM '.G5_TABLE_PREFIX.'pcs_info_joint WHERE s_f = "F" ORDER BY welder_1';
	$sql_welder_list = sql_query ($query_welder_list);
	$sql_welder_list_arr = sql_fetch_array ($sql_welder_list);

	while ($sql_welder_list_arr = sql_fetch_array ($sql_welder_list))	{
?>
<tr>
<td class="jnt_td"><?php echo '<a href='.G5_URL.'/app/board/board.php?bo_table=status&wr_id=3&wdr_select=welder_by&wdno='.$sql_welder_list_arr['welder_1'].' target="_self"><font style="font-size:25px;"> <b>'.$sql_welder_list_arr['welder_1'].'</b></font></a>'; ?></td>
<?php
	$welder_qty1++; 
	$welder_qty2++; 
	$welder_qty3++; 
	$weak_acc_di = 0;
	$weak_acc_day = 0;

		for($i=0;$i<7;$i++){

			$query_welder_di = "SELECT SUM(nps) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND ft_date = '".$wdate[$i-1]."'";
			$weak_acc_di += pcs_sql_value ($query_welder_di);
			$G_total_val[$i] += pcs_sql_value ($query_welder_di);

			if(pcs_sql_value ($query_welder_di)){$weak_acc_day++;}

			echo '<td class="jnt_td" >'.pcs_sql_value ($query_welder_di).'</td>';

		}

		$query_welder_di = "SELECT SUM(nps) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND w_type = 'WELD' AND ('".$bf_week."' <= ft_date AND ft_date <= '".$bf_1day."')";
		$query_welder_day = "SELECT count(DISTINCT ft_date) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND w_type = 'WELD' AND ('".$bf_week."' <= ft_date AND ft_date <= '".$bf_1day."')";
		if(pcs_sql_value ($query_welder_day)){$welder_day_avr = round(pcs_sql_value ($query_welder_di)/pcs_sql_value ($query_welder_day),1);}else{$welder_day_avr = 0;$welder_qty1--;}

		echo '<td class="jnt_td" >'.pcs_sql_value ($query_welder_di).'</td>';
		$G_total_val[$i+1] += pcs_sql_value ($query_welder_di);
		echo '<td class="jnt_td" >'.$welder_day_avr.'</td>';
		$G_total_val[$i+2] += $welder_day_avr;

		$query_welder_di = "SELECT SUM(nps) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND w_type = 'WELD' AND ('".$bf_month."' <= ft_date AND ft_date <= '".$bf_1day."')";
		$query_welder_day = "SELECT count(DISTINCT ft_date) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND w_type = 'WELD' AND ('".$bf_month."' <= ft_date AND ft_date <= '".$bf_1day."')";
		if(pcs_sql_value ($query_welder_day)){$welder_day_avr = round(pcs_sql_value ($query_welder_di)/pcs_sql_value ($query_welder_day),1);}else{$welder_day_avr = 0;$welder_qty2--;}

		echo '<td class="jnt_td" >'.pcs_sql_value ($query_welder_di).'</td>';
		$G_total_val[$i+3] += pcs_sql_value ($query_welder_di);
		echo '<td class="jnt_td" >'.round(pcs_sql_value ($query_welder_di)/pcs_sql_value ($query_welder_day),1).'</td>';
		$G_total_val[$i+4] += $welder_day_avr;

		$query_welder_di = "SELECT SUM(nps) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND w_type = 'WELD'";
		$query_welder_day = "SELECT count(DISTINCT ft_date) FROM ".G5_TABLE_PREFIX."pcs_info_joint WHERE welder_1 = '".$sql_welder_list_arr['welder_1']."' AND w_type = 'WELD'";
		$welder_day_avr = round(pcs_sql_value ($query_welder_di)/pcs_sql_value ($query_welder_day),1);

		echo '<td class="jnt_td" >'.pcs_sql_value ($query_welder_di).'</td>';
		$G_total_val[$i+5] += pcs_sql_value ($query_welder_di);
		echo '<td class="jnt_td" >'.round(pcs_sql_value ($query_welder_di)/pcs_sql_value ($query_welder_day),1).'</td>';
		$G_total_val[$i+6] += $welder_day_avr;


?>

</tr>


<?php		
	}	

?>

<tr>
<td class="jnt_td td_S_total">G-TOTAL</td>
<?php
		for($i=0;$i<7;$i++){echo '<td class="jnt_td td_S_total" ><a href='.G5_URL.'/app/board/board.php?bo_table=status&wr_id=3&wdr_select=daily_by&wdate='.$wdate[$i-1].' target="_self"><font style="font-size:25px;"> <b>'.$G_total_val[$i].'</b></font></a></td>';}

		echo '<td class="jnt_td td_S_total" >'.$G_total_val[$i+1].'</td>';

		echo '<td class="jnt_td td_S_total" >'.round($G_total_val[$i+2]/$welder_qty1,1).'</td>';

		echo '<td class="jnt_td td_S_total" >'.$G_total_val[$i+3].'</td>';

		echo '<td class="jnt_td td_S_total" >'.round($G_total_val[$i+4]/$welder_qty2,1).'</td>';

		echo '<td class="jnt_td td_S_total" >'.$G_total_val[$i+5].'</td>';

		echo '<td class="jnt_td td_S_total" >'.round($G_total_val[$i+6]/$welder_qty3,1).'</td>';


?>
</tr>

<?php
}	
?>

</tbody>
</table>
<p>&nbsp;
