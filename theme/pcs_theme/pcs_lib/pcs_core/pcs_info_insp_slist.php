<?php
	$date_qrf = $_POST['sel_qrf'];
	$date_qrt = $_POST['sel_qrt'];
	$date_ptf = $_POST['sel_ptf'];
	$date_ptt = $_POST['sel_ptt'];

	$query_insp_spl = 'SELECT A.*, B.* FROM '.G5_TABLE_PREFIX.'pcs_info_spool AS A LEFT JOIN '.G5_TABLE_PREFIX.'pcs_info_spl_stat AS B ON A.spool_no = B.spool_no WHERE ';
	for($i=0; $mysql_field_array[$i]; $i++){

		switch($i) {
			case 0 :
				if($_POST[$mysql_field_array[$i]]) {$query_insp_spl .= $mysql_field_array[$i].' = "'.$_POST[$mysql_field_array[$i]].'" ';}
				break;

			case 4 :
				if($_POST[$mysql_field_array[$i]]) {$query_insp_spl .= ' AND '.$mysql_field_array[$i].' LIKE "%'.$_POST[$mysql_field_array[$i]].'%"';}
				break;
				
			case 7 : 
				if($date_qrf){
					$query_insp_spl .= ' AND ( "'.$date_qrf.' 00:00:00" <= '.$mysql_field_array[$i].' AND '.$mysql_field_array[$i].' <= "'.$date_qrt.' 23:59:59" )';
				}
				break;

			case 9 :
				if($date_ptf){
					$query_insp_spl .= ' AND ( "'.$date_ptf.' 00:00:00" <= '.$mysql_field_array[$i].' AND '.$mysql_field_array[$i].' <= "'.$date_ptt.' 23:59:59" )';
				}
				break;

			default:
				if($_POST[$mysql_field_array[$i]]) {$query_insp_spl .= ' AND '.$mysql_field_array[$i].' = "'.$_POST[$mysql_field_array[$i]].'" ';}
				break;
		}
	}
	$query_insp_spl .= ' ORDER BY A.spool_no';
//	echo $query_insp_spl;
?>
<form name='submit_form' method="post" onSubmit="return doSumbit()">
<table class="main">
<caption> <a onclick='sbm();'> SPOOL STATUS </a> </caption>
<tbody>
<tr>
	<td class="jnt_td jnt_th" style="width: 3%"> No. </td>
	<td class="jnt_td jnt_th" style="width: 25%"> Spool No. </td>
	<td class="jnt_td jnt_th" style="width: 6%"> Material </td>
	<td class="jnt_td jnt_th" style="width: 6%"> Paint </td>
	<td class="jnt_td jnt_th" style="width: 6%"> Location </td>
	<td class="jnt_td jnt_th" style="width: 10%"> QR checked by /<br> QR checked time </td>
	<td class="jnt_td jnt_th" style="width: 10%"> Photoed by /<br> Photoed time </td>
	<td class="jnt_td jnt_th" style="width: 10%"> Photo </td>
</tr>

<?php

	$no == 0;

	$sql_insp_spl = sql_query ($query_insp_spl);

	while ($sql_insp_spl_arr = sql_fetch_array ($sql_insp_spl))	{
	
		$no++;
		$query_rev = 'SELECT rev_no FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$sql_insp_spl_arr['dwg_no'].'"';
		$sql_rev = sql_query ($query_rev);
		$sql_rev_arr = sql_fetch_array ($sql_rev);
		
		$query_g5_spl = 'SELECT wr_id FROM '.G5_TABLE_PREFIX.'write_spool WHERE wr_subject = "'.$sql_insp_spl_arr['spool_no'].'"';
		$sql_g5_spl = sql_query ($query_g5_spl);
		$sql_g5_spl_arr = sql_fetch_array ($sql_g5_spl);
?>
<input type="hidden" name="no[<?php echo $no; ?>]" value="<?php echo $no; ?>" />
<input type="hidden" name="lat[<?php echo $no; ?>]" value="<?php echo $sql_insp_spl_arr['gps_lat']; ?>" />
<input type="hidden" name="lon[<?php echo $no; ?>]" value="<?php echo $sql_insp_spl_arr['gps_lon']; ?>" />	
<tr>
<td class="jnt_td"><?php echo $no; ?></td>
<td class="jnt_td"><a href=<?php echo G5_URL.'/bbs/board.php?bo_table=spool&wr_id='.$sql_g5_spl_arr['wr_id']; ?> target='_self'> <font style='font-size:25px;'> <b> <?php echo $sql_insp_spl_arr['spool_no']; ?></b></font></a></td>
<td class="jnt_td"><?php echo $sql_insp_spl_arr['material']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_spl_arr['paint_code']; ?></td>
<td class="jnt_td"><?php echo $sql_insp_spl_arr['location']; ?></td>
<td class="jnt_td"><?php if($sql_insp_spl_arr['chk_by']){echo $sql_insp_spl_arr['chk_by'].'<br>'.$sql_insp_spl_arr['chk_tm'];} else {echo '<b>NONE</b>';} ?></td>
<td class="jnt_td"><?php if($sql_insp_spl_arr['photo_by']){echo $sql_insp_spl_arr['photo_by'].'<br>'.$sql_insp_spl_arr['photo_tm'];} ?></td>
<td class="jnt_td"><?php if($sql_insp_spl_arr['photo_by']){photo_thumb('spool', $sql_insp_spl_arr['photo'], '', 180,$sql_insp_spl_arr['dwg1']);} ?></td>
</tr>

<?php 
 	}
?>
</tbody>
</table>
</form>
<p>&nbsp;
<script>
function sbm(){
	var gglform = document.submit_form;
	var url = "<?php echo PCS_LIB_URL.'/pcs_info_insp_location.php'; ?>";
	window.open("","submit_form","width=900, height=900, left=200, top=100");
	
	gglform.action = url;
	gglform.method = 'post';
	gglform.target = 'submit_form';
	gglform.submit();
	
}
</script>