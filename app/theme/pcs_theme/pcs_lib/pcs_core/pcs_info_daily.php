<?php
if($_POST['folder'] || $_POST['ph']){include_once (PCS_LIB.'/pcs_photo.php');}
else if($_POST['mode']=='edit'){



	$query_daily = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_subject = "'.$view['wr_subject'].'"';
	$sql_daily = sql_query ($query_daily);
	$sql_daily_arr = sql_fetch_array ($sql_daily);
	
	$daily_cont = explode(';',$sql_daily_arr['wr_content']);
	$daily_isur = explode(';',$sql_daily_arr['wr_2']);
	$daily_time = explode(';',$sql_daily_arr['wr_3']);
	
	$issue_idx = $sql_daily_arr['wr_1'];
	
	$desc_no = $_POST['no'];

?>
<form name="desc" method="post" onSubmit="return doSumbit()">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="desc_before" value="<?php echo $daily_cont[$desc_no];?>">
<table class="main">
<caption>
<p style="text-align:center; font-size:50px;" ><a href = "javascript:document.desc.submit()" >REPORT Description Edit</a></p>

</caption><tbody>
<tr>
	<td class="main_td td_sub" style="width:5%"> No. </td>
	<td class="main_td td_sub" style="width:60%"> Description </td>
	<td class="main_td td_sub" style="width:20%"> Photo </td>
	<td class="main_td td_sub" style="width:15%"> Issued by/time </td>
</tr>
<tr>
<td class="jnt_td"><?php echo $desc_no; ?></td>
<td class="jnt_td"><input type="text" name="desc_after" autocomplete="off" style="padding:0px 0px 0px 15px; text-align:left;width:98%;height:50px;font-size:20px;border:none;" value="<?php echo $daily_cont[$desc_no];?>"></td>
<td class="jnt_td">
<?php
			photo_thumb('daily', $view['wr_subject'], $desc_no-1, 180);
			
?>
</td>
<td class="jnt_td"><?php echo $daily_isur[$desc_no].'<br>/<br>'.$daily_time[$desc_no]; ?></td>

</tr>
			
<?php
		

?>
</tbody>
</table>
</form>

<?php
/*
				<input type="hidden" name="mode" value="edit">
				<input type="hidden" name="no" value="'.$desc_count.'">
				<input type="hidden" name="page" value="'.$_GET['wr_id'].'">
				</form>
				';
*/

}
else{
	if($_POST['mode'] == 'update'){
		$query_daily = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_subject = "'.$view['wr_subject'].'"';
		$sql_daily = sql_query ($query_daily);
		$sql_daily_arr = sql_fetch_array ($sql_daily);
		
		$query_daily = 'UPDATE '.G5_TABLE_PREFIX.'write_daily SET wr_content="'.str_replace(';'.$_POST['desc_before'].';',';'.$_POST['desc_after'].';',$sql_daily_arr['wr_content']).'" WHERE wr_subject = "'.$view['wr_subject'].'"';
		sql_query ($query_daily);
		
	}
	$query_daily = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_subject = "'.$view['wr_subject'].'"';
	$sql_daily = sql_query ($query_daily);
	$sql_daily_arr = sql_fetch_array ($sql_daily);
	
	$daily_cont = explode(';',$sql_daily_arr['wr_content']);
	$daily_isur = explode(';',$sql_daily_arr['wr_2']);
	$daily_time = explode(';',$sql_daily_arr['wr_3']);
	
	$issue_idx = $sql_daily_arr['wr_1'];
	if($view['wr_subject']==G5_TIME_YMD && $member['mb_2']>1){$issue_idx++;}

	if(!G5_IS_MOBILE) { /////////// PC 버전 시작

?>

<table class="main">
<caption>
<p style="text-align:center; font-size:50px;" >DAILY REPORT</p>

</caption><tbody>
<tr>
	<td class="main_td td_sub" style="width:5%"> No. </td>
	<td class="main_td td_sub" style="width:60%"> Description </td>
	<td class="main_td td_sub" style="width:20%"> Photo </td>
	<td class="main_td td_sub" style="width:15%"> Issued by/time </td>
</tr>
<?php
		for($i=0;$i<$issue_idx;$i++){
			$desc_count = $i+1;
?>
<tr>
<td class="jnt_td"><?php echo $i+1; ?></td>
<td class="jnt_td">
<?php
			if($view['wr_subject']==G5_TIME_YMD && $member['mb_nick'] == $daily_isur[$i+1]){
				echo '
					<a href = "javascript:desc'.$desc_count.'.submit()" ><b>'.$daily_cont[$i+1].'</b></a>
					<form name="desc'.$desc_count.'" method="post" onSubmit="return doSumbit()">
					<input type="hidden" name="mode" value="edit">
					<input type="hidden" name="no" value="'.$desc_count.'">
					<input type="hidden" name="page" value="'.$_GET['wr_id'].'">
					</form>
				';
			}
			else {echo $daily_cont[$i+1];}
?>
</td>
<td class="jnt_td">
<?php
			photo_thumb('daily', $view['wr_subject'], $i, 180);
			
			if($view['wr_subject']==G5_TIME_YMD && $member['mb_2']>1 && $i>=$sql_daily_arr['wr_1']-1){
				if($daily_isur[$i+1]){
					if($member['mb_nick'] == $daily_isur[$i+1]){photo_up('daily', $view['wr_subject'], $i, $sql_daily_arr['wr_1'], $sql_daily_arr['wr_2']);}
				}
				else {photo_up('daily', $view['wr_subject'], $i, $sql_daily_arr['wr_1'], $sql_daily_arr['wr_2']);}
			}
 ?>
</td>
<td class="jnt_td"><?php echo $daily_isur[$i+1].'<br>/<br>'.$daily_time[$i+1]; ?></td>

</tr>
			
<?php
		}


?>
</tbody>
</table>

<?php

	}
	else {  /////////////////////////////////////////////////////////////////////////////////  Mobile 버전 시작
?>

<table class="main">
<caption>
<p style="text-align:center; font-size:50px;" >DAILY REPORT</p>

</caption><tbody>
<tr>
	<td class="jnt_td td_sub" style="width:15%" rowspan="2"> No. </td>
	<td class="jnt_td td_sub" style="width:55%" colspan="2"> Description </td>
</tr>
<tr>	
	<td class="jnt_td td_sub" style="width:55%"> Photo </td>
	<td class="jnt_td td_sub" style="width:30%"> Issued by<br>/ time </td>
</tr>

<?php
		for($i=0;$i<$issue_idx;$i++){
?>
<tr>
<td class="jnt_td" rowspan="2"><?php echo $i+1; ?></td>
<td class="jnt_td" colspan="2"><?php echo $daily_cont[$i+1]; ?></td>
</tr>
<tr>
<td class="jnt_td">
<?php
			photo_thumb('daily', $view['wr_subject'], $i, 180);
			
			if($view['wr_subject']==G5_TIME_YMD && $member['mb_2']>1 && $i>=$sql_daily_arr['wr_1']-1){
				if($daily_isur[$i+1]){
					if($member['mb_nick'] == $daily_isur[$i+1]){photo_up('daily', $view['wr_subject'], $i, $sql_daily_arr['wr_1'], $sql_daily_arr['wr_2']);}
				}
				else {photo_up('daily', $view['wr_subject'], $i, $sql_daily_arr['wr_1'], $sql_daily_arr['wr_2']);}
			}
 ?>
</td>
<td class="jnt_td"><?php echo $daily_isur[$i+1].'<br>/<br>'.$daily_time[$i+1]; ?></td>

</tr>
			
<?php
		}
?>
</tbody>
</table>

<?php
	}
}
?>
