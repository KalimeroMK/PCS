<?php
$pkg_no = $_POST['pkg'];
$issued_dwg = $_POST['dwg'];
if($_POST['mode'] == 'issue'){
	$pc_no = intval($_POST['t_no'])+1;
}
else{
	$pc_no = $_POST['t_no'];
	$query_pnc_up = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_punch WHERE pkg_no = "'.$pkg_no.'" AND s_no = "'.$_POST['t_no'].'"';
	$sql_pnc_up = sql_query ($query_pnc_up);
	$sql_pnc_up_arr = sql_fetch_array ($sql_pnc_up);
	
	if ($pc_no*1<10) {
        $punchFile = $pkg_no.'_00'.$pc_no;
    } elseif ($_POST['s_no']*1<100) {
        $punchFile = $pkg_no.'_0'.$pc_no;
    } else {$punchFile = $pkg_no.'_'.$pc_no;}

	$pncURL = PCS_PKG_URL.'/'.$pkg_no.'/'.$punchFile;
	
	if($member['mb_7']<2 && $member['mb_nick'] != $sql_pnc_up_arr['issued_by'] && substr($sql_pnc_up_arr['pcs_vi_rlt_by'],0,10) != G5_TIME_YMD ){ $rmv_ok = false; } else { $rmv_ok = true; }
}
?>
<form name='punch' method="post" onSubmit="return doSumbit()"> 
<input type="hidden" name="p_page" value="p_upda">
<input type='hidden' name='punch' value='<?php echo $_POST['mode']; ?>'>
<input type='hidden' name='pkg_no' value='<?php echo $pkg_no; ?>'>
<input type='hidden' name='s_no' value='<?php echo $pc_no; ?>'>
<input type='hidden' name='dwg_no' value='<?php echo $issued_dwg; ?>'>
<?php
if ($_POST['mode'] == 'issue') {
    echo '<input type="hidden" name="pcs_img_str1" id="pcs_img_str1" value="">';
} elseif ($_POST['mode'] == 'clear') {
    echo '<input type="hidden" name="pcs_img_str2" id="pcs_img_str2" value="">';
}

if(!G5_IS_MOBILE){
?>

<div style="overflow:hidden;height:auto;">

<div style='width:25%;float:left;padding:10px;'>

<table class="main" style="width:100%">
<caption> PHOTO Before </caption>
<tbody>
<tr>
<td class="main_td" style="height:60px; font-size:25px;">
<?php
if($_POST['mode'] == 'issue') {echo '<input type="file" name="myFile1" id="pcs_thumb1" accept="image/*" style="width:100%" />';}
?>
</td>
</tr>
<tr>
<td class="main_td" style="height:295px"> <img id='pcs_prev1' width='350px' src='<?php if($sql_pnc_up_arr['issued_by']){echo $pncURL.'_BF.jpg';}?>' alt='thumbnail image'></td>
</tr>
<tr>
<td class="main_td" style="height:60px; font-size:25px;"> 

</td>
</tr>
</tbody>
</table>

<p>&nbsp;<p>&nbsp;
</div>



<div style="width:50%;float:left; padding:10px;">

<table class="main" style="width:100%">
<caption> PUNCH DETAIL </caption>
<tbody>
<tr>
	<td class="main_td td_sub" style="width:20%; height:60px; font-size:25px;" colspan="1"> Drawing</td>
	<td class="main_td" colspan="3"> <?php echo $issued_dwg; ?> </td>
</tr>
<tr>
	<td class="main_td td_sub" style="width:20%; height:60px; font-size:25px;" colspan="1"> Description</td>
	<td class="main_td" colspan="3" style="text-align:left;font-size:20px;padding:0px 0px 0px 15px;"> 
<?php
if($_POST['mode'] == 'issue'||$_POST['mode'] == 'u_desc') {echo '<input type="text" name="punch_des" autocomplete="off" style="padding:0px 0px 0px 15px; text-align:left;width:98%;height:50px;font-size:20px;border:none;" onchange="reveal();" value="'.$sql_pnc_up_arr['punch_desc'].'">';}
else {echo $sql_pnc_up_arr['punch_desc'];}
?>
	</td>
</tr>
<tr>
	<td class="main_td td_sub" style="width:20%; height:60px; font-size:25px;"> Punch no. </td>
	<td class="main_td"> <?php echo $pc_no; ?> </td>
	<td class="main_td td_sub" style="width:20%; height:60px; font-size:25px;"> Category </td>
	<td class="main_td"> 
<?php
if($_POST['mode'] == 'issue') {
?>
			<select name="punch_cat" id="punch_cat" style="padding:0px 0px 0px 15px; text-align:left;width:50%;height:70px;font-size:40px;border:none;">
			<option value="A" >A</option>
			<option value="B" selected >B</option>
			<option value="C" >C</option>
		</select>
<?php
}
else {echo $sql_pnc_up_arr['category'];}
?>
	</td>
</tr>
<tr>
	<td class="main_td td_sub" style="width:20%; height:80px; font-size:25px;"> Issue Date </td>
	<td class="main_td" style="width:20%; height:80px; font-size:25px;"> <?php if($sql_pnc_up_arr['issued_by']){echo $sql_pnc_up_arr['issued_date'];} ?></td>
	<td class="main_td td_sub" style="width:20%; height:80px; font-size:25px;"> Issued by </td>
	<td class="main_td" style="width:20%; height:80px; font-size:25px;"> <?php echo $sql_pnc_up_arr['issued_by']; ?></td>
</tr>
<tr>
	<td class="main_td td_sub" style="width:20%; height:80px; font-size:25px;"> Clear Date </td>
	<td class="main_td" style="width:20%; height:80px; font-size:25px;"> <?php if($sql_pnc_up_arr['cleared_by']){echo $sql_pnc_up_arr['cleared_date'];} ?></td>
	<td class="main_td td_sub" style="width:20%; height:80px; font-size:25px;"> Cleared by </td>
	<td class="main_td" style="width:20%; height:80px; font-size:25px;"> <?php echo $sql_pnc_up_arr['cleared_by']; ?></td>
</tr>
<tr>
	<td class="main_td" style="height:60px" colspan="2"><a id="sbm" href = 'javascript:document.punch.submit()' <?php if($_POST['mode'] != 'remove') {echo 'hidden';} ?>>
<?php
	if($member['mb_7']){
		if ($_POST['mode'] == 'issue'||$_POST['mode'] == 'clear') {
            echo '<font color = blue><b> UPDATE </b></font>';
        } elseif ($_POST['mode'] == 'remove' && $rmv_ok) {
            echo '<font color = orange><b> REMOVE </b></font>';
        } elseif ($_POST['mode'] == 'u_desc') {
            echo '<font color = green><b> UPDATE </b></font>';
        } 
	}
?>

	</a></td>
	<td class="main_td" style="height:60px;" colspan="2"> <a href="<?php echo G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&wr_id='.$wr_id; ?>" ><font color = red><b> Cancle </b></font></a></td>
</tr>

</tbody>
</table>

<p>&nbsp;<p>&nbsp;

</div>

<div style='width:25%;float:left;padding:10px;'>

<table class="main" style="width:100%">
<caption> PHOTO after </caption>
<tbody>
<tr>
<td class="main_td" style="height:60px; font-size:25px;">
<?php
if($_POST['mode'] == 'clear') {echo '<input type="file" name="myFile2" id="pcs_thumb2" accept="image/*" style="width:100%" />';}
?>
</td>
</tr>
<tr>
<td class="main_td" style="height:295px"> <img id='pcs_prev2' width='350px' src='<?php if($sql_pnc_up_arr['cleared_by']){echo $pncURL.'_AF.jpg';}?>' alt='thumbnail image'></td>
</tr>
<tr>
<td class="main_td" style="height:60px; font-size:25px;"> 

</td>
</tr>
</tbody>
</table>

<p>&nbsp;<p>&nbsp;
</div>



</div>


<?php
}
else{		//////////////////////////////////////////////////////// mobile
?>
<table class="main" style="width:100%">
<caption> PUNCH DETAIL </caption>
<tbody>
<tr>
	<td class="jnt_td td_sub" style="width:20%; height:60px;" colspan="1"> Drawing</td>
	<td class="jnt_td" colspan="3"> <?php echo $issued_dwg; ?> </td>
</tr>
<tr>
	<td class="jnt_td td_sub" style="width:20%; height:60px;" colspan="1"> Desc. </td>
	<td class="jnt_td" colspan="3" style="text-align:left;font-size:20px;padding:0px 0px 0px 15px;"> 
<?php
if($_POST['mode'] == 'issue') {echo '<input type="text" name="punch_des" autocomplete="off" style="padding:0px 0px 0px 15px; text-align:left;width:98%;height:50px;font-size:20px;border:none;">';}
else {echo $sql_pnc_up_arr['punch_desc'];}
?>
	</td>
</tr>
<tr>
	<td class="jnt_td td_sub" style="width:20%; height:60px;"> Punch<br>no. </td>
	<td class="jnt_td"> <?php echo $pc_no; ?> </td>
	<td class="jnt_td td_sub" style="width:20%; height:60px;"> Category </td>
	<td class="jnt_td"> 
<?php
if($_POST['mode'] == 'issue') {
?>
		<select name="punch_cat" id="punch_cat" style="padding:0px 0px 0px 15px; text-align:left;height:50px;font-size:30px;border:none;">
			<option value="<?php echo $_POST['cat'];?>" ><?php echo $_POST['cat'];?></option>
		</select>
<?php
}
else {echo $sql_pnc_up_arr['category'];}
?>
	</td>
</tr>
<tr>
<td class="main_td" style="font-size:25px;" colspan="4">
<?php
if($_POST['mode'] == 'issue') {echo '<input type="file" name="myFile1" id="pcs_thumb1" accept="image/*" style="width:100%" />';}
if($_POST['mode'] == 'clear') {echo '<input type="file" name="myFile2" id="pcs_thumb2" accept="image/*" style="width:100%" />';}
?>
</td>
</tr>
<tr>
<td class="main_td" style="height:300px" colspan="4">
<?php
if($_POST['mode'] == 'issue'||$_POST['mode'] == 'remove') { echo '<img id="pcs_prev1" width="300px" src="'.$pncURL.'_BF.jpg" alt="thumbnail image">';}
if($_POST['mode'] == 'clear') { echo '<img id="pcs_prev2" width="300px" src="'.$pncURL.'_AF.jpg" alt="thumbnail image">';}
?>
</td>
</tr>
<tr>
	<td class="jnt_td" style="width:50%; height:60px" colspan="2"><a id="sbm" href = 'javascript:document.punch.submit()' <?php if($_POST['mode'] != 'remove') {echo 'hidden';} ?>>
<?php
		if ($_POST['mode'] == 'issue'||$_POST['mode'] == 'clear') {
    echo '<font color = blue><b> UPDATE </b></font>';
} elseif ($_POST['mode'] == 'remove' && $rmv_ok) {
    echo '<font color = orange><b> REMOVE </b></font>';
} 
?>

	</a></td>
	<td class="jnt_td" style="width:50%; height:60px;" colspan="2"> <a href="<?php echo G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&wr_id='.$wr_id; ?>" ><font color = red><b> Cancle </b></font></a></td>
</tr>

</tbody>
</table>

<?php	
}
?>
</form>
<script>
<?php	
if($_POST['mode'] == 'issue') {
?>
var pcs_file_BF = document.getElementById("pcs_thumb1");
pcs_file_BF.onchange = function () {
	var fileList = pcs_file_BF.files ;
	var file_content = new FileReader();
	file_content.readAsDataURL(fileList[0]);
	file_content.onload = function(){
		var tempImage1 = new Image();
		tempImage1.src = file_content.result;
		tempImage1.onload = function(){
			var canvas1 = document.createElement("canvas");
			var canvasContext = canvas1.getContext("2d");
			canvas1.width = 600;	canvas1.height = 450;
			canvasContext.drawImage(this,0,0,canvas1.width,canvas1.height);
			canvasContext.fillStyle = "rgb(127,127,127)";
			var picX = 430;			var picY = 390;
			canvasContext.font = "10px verdana";
			canvasContext.fillText ("<?php echo 'PKG : '.$_POST['pkg']; ?>",picX+1,picY+1);
			canvasContext.fillText ("<?php echo 'DWG : '.$_POST['dwg']; ?>",picX+1,picY+16);
			canvasContext.fillText ("<?php echo 'Before Clear : Punch no. '.$pc_no; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = "rgb(0,0,0)";
			canvasContext.font = "10px verdana";
			canvasContext.fillText ("<?php echo 'PKG : '.$_POST['pkg']; ?>",picX,picY);
			canvasContext.fillText ("<?php echo 'DWG : '.$_POST['dwg']; ?>",picX,picY+15);
			canvasContext.fillText ("<?php echo 'Before Clear : Punch no. '.$pc_no; ?> ("+document.getElementById("punch_cat").value+")",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
			var dataURL1 = canvas1.toDataURL("image/jpeg");
			document.getElementById("pcs_prev1").src = dataURL1;
			document.getElementById("pcs_img_str1").value = dataURL1;
		}
	}
	pcs_file_BF.remove(0);
	document.getElementById("sbm").hidden = false;
}
<?php
}
if($_POST['mode'] == 'clear') {
?>
var pcs_file_AF = document.getElementById("pcs_thumb2");
pcs_file_AF.onchange = function () {
	var fileList = pcs_file_AF.files ;
	var file_content = new FileReader();
	file_content.readAsDataURL(fileList[0]);
	file_content.onload = function(){
		var tempImage2 = new Image();
		tempImage2.src = file_content.result;
		tempImage2.onload = function(){
			var canvas2 = document.createElement("canvas");
			var canvasContext = canvas2.getContext("2d");
			canvas2.width = 600;	canvas2.height = 450;
			canvasContext.drawImage(this,0,0,canvas2.width,canvas2.height);
			canvasContext.fillStyle = "rgb(127,127,127)";
			var picX = 430;			var picY = 390;
			canvasContext.font = "10px verdana";
			canvasContext.fillText ("<?php echo 'PKG : '.$_POST['pkg']; ?>",picX+1,picY+1);
			canvasContext.fillText ("<?php echo 'DWG : '.$_POST['dwg']; ?>",picX+1,picY+16);
			canvasContext.fillText ("<?php echo 'After Clear : Punch no. '.$pc_no; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = "rgb(0,0,0)";
			canvasContext.font = "10px verdana";
			canvasContext.fillText ("<?php echo 'PKG : '.$_POST['pkg']; ?>",picX,picY);
			canvasContext.fillText ("<?php echo 'DWG : '.$_POST['dwg']; ?>",picX,picY+15);
			canvasContext.fillText ("<?php echo 'After Clear : Punch no. '.$pc_no.'  ('.$sql_pnc_up_arr['category'].')' ?>",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
			var dataURL2 = canvas2.toDataURL("image/jpeg");
			document.getElementById("pcs_prev2").src = dataURL2;
			document.getElementById("pcs_img_str2").value = dataURL2;
		}
	}
	pcs_file_AF.remove(0);
	document.getElementById("sbm").hidden = false;
}
<?php
}
if($_POST['mode'] == 'u_desc') {
?>
function reveal() {document.getElementById("sbm").hidden = false;}
<?php
}
?>
</script>
