<?php
$issue_date = $_POST['issue_date'];
$issue_num = $_POST['issue_num'];
if($_POST['issue_mode'] == 'issue'){
	$desc_no = intval($_POST['issue_num'])+1;
}
else{
	$desc_no = $_POST['t_no'];
	$query_issue_cont = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_subject = "'.$issue_date.'"';
	$sql_issue_cont = sql_query ($query_issue_cont);
	$sql_issue_cont_arr = sql_fetch_array ($sql_issue_cont);
	
	$content_arr = explode('|',$sql_issue_cont_arr['wr_content']);
	$content_arr_qty = count($content_arr)-1;
	
	if($member['mb_7']<2 && $member['mb_nick'] != $sql_issue_cont_arr['wr_2'] && substr($sql_issue_cont_arr['pcs_vi_rlt_by'],0,10) != G5_TIME_YMD ){ $rmv_ok = false; } else { $rmv_ok = true; }
}
?>
<form name='daily' method="post" onSubmit="return doSumbit()"> 
<input type='hidden' name='issue_mode' value='update'>
<input type='hidden' name='issue_num' value='<?php echo $desc_no; ?>'>
<input type='hidden' name='issued_by' value='<?php echo $member['mb_nick']; ?>'>
<input type='hidden' name='issued_at' value='<?php echo G5_TIME_YMDHIS; ?>'>
<input type='hidden' name='issued_content' id="iscnt" >
<?php
if($_POST['issue_mode'] == 'issue') {echo '<input type="hidden" name="pcs_img_str1" id="pcs_img_str1" value="">';}

if(!G5_IS_MOBILE){
?>





<table class="main" style="width:100%">
<caption> DESCRIPTION DETAIL </caption>
<tbody>
<tr>
	<td class="main_td td_sub" style="width:5%"> no.</td>
	<td class="main_td td_sub" style="width:50%"> Description</td>
	<td class="main_td td_sub" > Photo</td>
	<td class="main_td td_sub" > Upload</td>
</tr>
<tr>
	<td class="main_td" style="height:300px"> <?php echo $desc_no; ?> </td>
	<td class="main_td" >
<?php
		if($_POST['issue_mode'] == 'issue'||$_POST['issue_mode'] == 'u_desc') {echo '<input type="text" name="issue_desc" id="istxt" autocomplete="off" style="padding:0px 0px 0px 15px; text-align:left;width:98%;height:250px;font-size:20px;border:none;" onchange="reveal();" value="'.$_POST['wr_content'].'">';}
		else {echo $sql_issue_cont_arr['punch_desc'];}
?>
	</td>
	<td class="main_td" style="width:25%"> <img id='pcs_prev1' width='350px' src='<?php if($sql_issue_cont_arr['issued_by']){echo $pncURL.'_BF.jpg';}?>' alt='thumbnail image'></td>
	<td class="main_td" style="width:20%"> 
<?php
if($_POST['issue_mode'] == 'issue') {echo '<input type="file" name="myFile1" id="pcs_thumb1" accept="image/*" style="width:100%" />';}
?>
	</td>
</tr>

<tr>
	<td class="main_td" style="height:60px" colspan="2"><a id="sbm" href = 'javascript:document.daily.submit()' <?php if($_POST['issue_mode'] != 'remove') {echo 'hidden';} ?>>
<?php
	if($member['mb_7']){
		if ($_POST['issue_mode'] == 'issue'||$_POST['issue_mode'] == 'clear') {
            echo '<font color = blue><b> UPDATE </b></font>';
        } elseif ($_POST['issue_mode'] == 'remove' && $rmv_ok) {
            echo '<font color = orange><b> REMOVE </b></font>';
        } elseif ($_POST['issue_mode'] == 'u_desc') {
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


<?php
}

?>
</form>
<script>
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
			canvasContext.fillText ("Description <?php echo $issue_num; ?>.",picX+1,picY+16);
			canvasContext.fillText ("Photoed by : <?php echo $issue_num; ?>",picX+1,picY+31);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX+1,picY+46);
			canvasContext.fillStyle = "rgb(0,0,0)";
			canvasContext.font = "10px verdana";
			canvasContext.fillText ("Description <?php echo $issue_num; ?>.",picX,picY+15);
			canvasContext.fillText ("Photoed by : <?php echo $issue_num; ?>",picX,picY+30);
			canvasContext.fillText ("<?php echo 'At '.G5_TIME_YMDHIS; ?>",picX,picY+45);
			var dataURL1 = canvas1.toDataURL("image/jpeg");
			document.getElementById("pcs_prev1").src = dataURL1;
			document.getElementById("pcs_img_str1").value = dataURL1;
		}
	}
	pcs_file_BF.remove(0);
	document.getElementById("sbm").hidden = false;
}

function reveal() {document.getElementById("sbm").hidden = false;}
</script>
