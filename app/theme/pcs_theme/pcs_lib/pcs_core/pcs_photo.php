<?php
if($_POST['ph']){
	$thw=120;$thh=90;
	if($_POST['fn_itm']=='tp'){
		$thw=180;$thh=135;
		$filepath  = PCS_PHOTO_TP;
		$photoname  = $_POST['fn_sbj'].'_'.$_POST['fn_jnt'];	

		if($_POST['pcs_img_str']){
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_tp_stat SET tp_'.$_POST['fn_jnt'].' = "'.$photoname.'", tp_'.$_POST['fn_jnt'].'_by = "'.$member['mb_nick'].'", tp_'.$_POST['fn_jnt'].'_tm = "'.G5_TIME_YMDHIS.'" WHERE tp_no = "'.$_POST['fn_sbj'].'"';
		}
		else{
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_tp_stat SET tp_'.$_POST['fn_jnt'].' = "", tp_'.$_POST['fn_jnt'].'_by = "", tp_'.$_POST['fn_jnt'].'_tm = "0000-00-00 00:00:00" WHERE tp_no = "'.$_POST['fn_sbj'].'"';
		}
	}
	else if($_POST['fn_itm']=='daily'){
		$filepath  = PCS_DATA_DAILY.'/'.$_POST['fn_sbj'];
		$photoname  = $_POST['fn_sbj'].'_'.$_POST['fn_jnt'];
		
		$query_daily = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_subject = "'.$_POST['fn_sbj'].'"';
		$sql_daily = sql_query ($query_daily);
		$sql_daily_arr = sql_fetch_array ($sql_daily);
		
		$wr_c_val = '';
		$wr_1_val = $sql_daily_arr['wr_1'];
		$wr_2_val = '';
		$wr_3_val = '';
		


		if($_POST['pcs_img_str']){
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'write_daily SET wr_content="'.$sql_daily_arr['wr_content'].'Photo_'.($sql_daily_arr['wr_1']+1).' uploaded.;", wr_1="'.($sql_daily_arr['wr_1']+1).'", wr_2="'.$sql_daily_arr['wr_2'].$member['mb_nick'].';'.'", wr_3= "'.$sql_daily_arr['wr_3'].G5_TIME_YMDHIS.';'.'" WHERE wr_subject = "'.$_POST['fn_sbj'].'"';
		}
		else if($_POST['fn_pho']==$_POST['fn_jnt']){
			echo '<script type="text/javascript"> location.href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&wr_id='.$wr_id.'"; </script>';
		}
		else {
			$wr_c_arr = explode(';',$sql_daily_arr['wr_content']);
			$wr_2_arr = explode(';',$sql_daily_arr['wr_2']);
			$wr_3_arr = explode(';',$sql_daily_arr['wr_3']);
			
			for($i=0;$i<$sql_daily_arr['wr_1'];$i++){
				$wr_c_val .= $wr_c_arr[$i].';';
				$wr_2_val .= $wr_2_arr[$i].';';
				$wr_3_val .= $wr_3_arr[$i].';';

			}
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'write_daily SET wr_content="'.$wr_c_val.'", wr_1="'.($wr_1_val-1).'", wr_2="'.$wr_2_val.'", wr_3= "'.$wr_3_val.'" WHERE wr_subject = "'.$_POST['fn_sbj'].'"';
		}
	}
	else if($_POST['fn_itm']=='spool'){
		$filepath  = PCS_DWG_ISO.'/'.$_POST['fn_dwg'];
		$photoname  = $_POST['fn_sbj'];	

		if($_POST['pcs_img_str']){
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_spl_stat SET photo = "'.$photoname.'", photo_by = "'.$member['mb_nick'].'", photo_tm = "'.G5_TIME_YMDHIS.'" WHERE spool_no = "'.$_POST['fn_sbj'].'"';
		}
		else{
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_spl_stat SET photo = "", photo_by = "", photo_tm = "0000-00-00 00:00:00" WHERE spool_no = "'.$_POST['fn_sbj'].'"';
		}
	}
	else {
		$filepath  = PCS_DWG_ISO.'/'.$_POST['fn_sbj'];
		$photoname  = $_POST['fn_itm'].'_'.$_POST['fn_sbj'].'_'.$_POST['fn_jnt'];
		
		if($_POST['pcs_img_str']){
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc SET '.$_POST['fn_itm'].' = "'.$photoname.'", '.$_POST['fn_itm'].'_by = "'.$member['mb_nick'].'", '.$_POST['fn_itm'].'_tm = "'.G5_TIME_YMDHIS.'" 
							WHERE dwg_no = "'.$_POST['fn_sbj'].'" AND j_no = "'.$_POST['fn_jnt'].'"' ;
		}
		else{
			$query_photo = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc SET '.$_POST['fn_itm'].' = "", '.$_POST['fn_itm'].'_by = "", '.$_POST['fn_itm'].'_tm = "0000-00-00 00:00:00" 
							WHERE dwg_no = "'.$_POST['fn_sbj'].'" AND j_no = "'.$_POST['fn_jnt'].'"' ;
		}
	}
	
	$filename  = get_safe_filename($photoname.'.jpg');

	if($_POST['pcs_img_str']){
		
		base64_to_img($_POST['pcs_img_str'],$filepath.'/'.$filename);
		pcsthumbnail($filename, $filepath, $filepath, $thw, $thh, false);
	}
	else {
		unlink($filepath.'/'.$filename);
		unlink($filepath.'/thumb_'.$filename);
	}
	
	sql_query ($query_photo);

	echo '<script type="text/javascript"> location.href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&wr_id='.$wr_id.'"; </script>';
}

else {
	$ran = mt_rand(1, 10000);
	if($_POST['folder']=='daily'){$thumbIMG = PCS_URL_DAILY.'/'.$_POST['sbjt'].'/'.$_POST['sbjt'].'_'.$_POST['j_no'].'.jpg?ran='.$ran;}
	else if($_POST['photo']){$thumbIMG = PCS_ISO_URL.'/'.$_POST['spdwg'].'/'.$_POST['photo'].'.jpg?ran='.$ran;}
	else { $thumbIMG = ''; }
?>
<div style='background-color:white; padding:10px;'>
<form method="post" enctype="multipart/form-data" target="_self"> 
	<input type="hidden" name="fn_sbj" value="<?php echo $_POST['sbjt']; ?>">
	<input type="hidden" name="fn_jnt" value="<?php echo $_POST['j_no']; ?>">
	<input type="hidden" name="fn_itm" value="<?php echo $_POST['folder']; ?>">
	<input type="hidden" name="fn_dwg" value="<?php echo $_POST['spdwg']; ?>">
	<input type="hidden" name="fn_pho" value="<?php echo $_POST['photo']; ?>">
	<input type="hidden" name="pcs_img_str" id="pcs_img_str" value=''>
	<input type="hidden" name="ph" value="Y">
<table class="main" <?php if(!G5_IS_MOBILE){echo "style='width:50%'";}?>> 
<caption>Take a Photo to Upload</caption>
 
<tbody>
<tr> 
   <td class="main_td"><input type="file" name="myFile" id='pcs_thumb' accept='image/*' style='width:100%' /></td> 
</tr> 
<tr> 
   <td class="main_td"><img id='pcs_prev' width='300px' src="<?php echo $thumbIMG; ?>" alt='thumbnail image'></td> 

</tr> 
<tr> 
<td class="main_td">	
	<input type="submit" id="photo_btn" value="Photo Remove" accesskey="s" class="btn_submit" >
	<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table.'&wr_id='.$wr_id; ?>" class="btn_cancel">Cancle</a></td>
</tr> 
</tbody>
</table> 
</form> 
</div>
<script>
var pcs_file = document.querySelector('#pcs_thumb');
pcs_file.onchange = function () {
	var fileList = pcs_file.files ;
	var file_content = new FileReader();
	file_content.readAsDataURL(fileList[0]);
	file_content.onload = function(){
		var tempImage = new Image();
		tempImage.src = file_content.result;
		tempImage.onload = function(){
			var canvas = document.createElement('canvas');
			var canvasContext = canvas.getContext("2d");
			canvas.width = <?php if($_POST['folder']=='tp'){echo '1000';} else {echo '800';} ?>;	canvas.height = 600;
			canvasContext.drawImage(this,0,0,canvas.width,canvas.height);
			canvasContext.fillStyle = 'rgb(127,127,127)';
			var picX = <?php if($_POST['folder']=='tp'){echo '840';} else {echo '640';} ?>;			var picY = 540;
<?php
	if($_POST['folder']=='spool'){
?>
//			canvasContext.font = '10px verdana';
//			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX+1,picY+1);
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX+1,picY+16);
			canvasContext.fillText ("<?php echo $_POST['j_no']; ?>",picX+91,picY+16);
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = 'rgb(0,0,0)';
//			canvasContext.font = '10px verdana';
//			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX,picY);
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX,picY+15);
			canvasContext.fillText ("<?php echo $_POST['j_no']; ?>",picX+90,picY+15);
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
<?php
	}
	else if($_POST['folder']=='tp'){
?>
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX+1,picY+16);
			canvasContext.fillText ("<?php echo $_POST['j_no']; ?>",picX+91,picY+16);
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = 'rgb(0,0,0)';
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX,picY+15);
			canvasContext.fillText ("<?php echo $_POST['j_no']; ?>",picX+90,picY+15);
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
<?php
	}
	else if($_POST['folder']=='daily'){
?>
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = 'rgb(0,0,0)';
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
<?php
	}
	else{
?>
			canvasContext.font = '8px verdana';
			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX+1,picY+1);
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo 'Joint no : '.z_rem_jno($_POST['j_no']); ?>",picX+1,picY+16);
			canvasContext.fillText ("<?php echo $_POST['folder']; ?>",picX+91,picY+16);
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = 'rgb(0,0,0)';
			canvasContext.font = '8px verdana';
			canvasContext.fillText ("<?php echo $_POST['sbjt']; ?>",picX,picY);
			canvasContext.font = '12px verdana';
			canvasContext.fillText ("<?php echo 'Joint no : '.z_rem_jno($_POST['j_no']); ?>",picX,picY+15);
			canvasContext.fillText ("<?php echo $_POST['folder']; ?>",picX+90,picY+15);
			canvasContext.fillText ("<?php echo 'Photoed by : '.$member['mb_nick']; ?>",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
<?php
	}
?>
			var dataURL = canvas.toDataURL("image/jpeg");
			document.querySelector('#pcs_prev').src = dataURL;
			document.querySelector('#pcs_img_str').value = dataURL;
		}
	}
	pcs_file.remove(0);
	document.getElementById('photo_btn').value = "Photo update";
	document.getElementById('photo_btn').hidden = false;
}
</script>

<?php
}
?>

