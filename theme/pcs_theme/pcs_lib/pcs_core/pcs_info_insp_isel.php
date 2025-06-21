<?php
	$table_field_array = array('Inspection','Unit','ISO Dwg No','Material','S/F','Checked by','Result','Date');
	$table_width_array = array(40,40,80,30,30,30,40,130);


	$query_field = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_joint';
	$field_enum_value = enum_value($query_field);
	
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<form name='submit_form' method="post" onSubmit="return doSumbit()">

<table class="main">
<caption> <a href = 'javascript:document.submit_form.submit()'> SELECT INSPECTION STATUS </a> </caption>
<tbody>
<tr>

<?php
	for($i=0; isset($table_field_array[$i]) && $table_field_array[$i]; $i++){
?>

	<td class="jnt_td jnt_th" style="width: <?php echo $table_width_array[$i]; ?>px;"><?php echo $table_field_array[$i]; ?></td>
	
<?php
	}
?>

</tr>

<tr>
<input type='hidden' name='btn_check' value='<?php echo $_GET['wr_id']; ?>'>

<?php
	for($i=0; isset($table_field_array[$i]) && $table_field_array[$i]; $i++){
		switch ($i)	{
			case 0 :
?>

<td class="jnt_td" style="width: <?php echo $table_width_array[$i]; ?>px;">
<select name='<?php echo $mysql_field_array[$i]; ?>' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
	<option value='fitup'>Fit-Up</option>
	<option value='vi' selected>Visual</option>
	<option value='pwht'>PWHT</option>
	<option value='pmi'>PMI</option>
		<optgroup label="NDE">
 			<option value="RT">RT</option>
       		<option value="MT">MT</option>
       		<option value="PT">PT</option>
       		<option value="PAUT">PAUT</option>
	     </optgroup>
 	</select>
</td>

<?php
		Break;	

			case 2 :
?>

<td class="jnt_td" style="width: <?php echo $table_width_array[$i]; ?>px;"><input type='text' name='<?php echo $mysql_field_array[$i]; ?>' style='padding:0px 0px 0px 5px; text-align:left;width:90%;height:30px;font-size:15px;background-color:bisque;'></td>
	
<?php		Break;

			case 6 : ?>
			
<td class="jnt_td" style="width: <?php echo $table_width_array[$i]; ?>px;">
	<select name='<?php echo $mysql_field_array[$i]; ?>' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
		<option value='Request'>Request</option>
		<option value='Done'>Done</option>
		<option value='Accept' selected>Accept</option>
		<option value='Reject'>Reject</option>
	</select>
</td>

<?php
			Break;	
			
			case 5 : ?>
			
<td class="jnt_td" style="width: <?php echo $table_width_array[$i]; ?>px;">
	<select name='<?php echo $mysql_field_array[$i]; ?>' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
		<option value=''>-</option>
		<option value='<?php echo $member['mb_nick'];?>'>Myself</option>
	</select>
</td>

<?php
			Break;	

			case 7 :
				$datxt1 = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" name="sel_fr" id="sel_fr" value="'.G5_TIME_YMD.'">';
				$datxt2 = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" name="sel_to" id="sel_to" value="'.G5_TIME_YMD.'">';
?>

<td class="jnt_td" style="width: <?php echo $table_width_array[$i]; ?>px;"><?php echo 'From : '.$datxt1.' ~ To : '.$datxt2; ?></td>

<?php		
			Break;

			default :
?>

<td class="jnt_td" style="width: <?php echo $table_width_array[$i]; ?>px;">
	<select name='<?php echo $mysql_field_array[$i]; ?>' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
		<option value=''>-</option>
		<?php sel_option_enum($field_enum_value[$mysql_field_array[$i]],''); ?>
	</select>
</td>

<?php		Break;			
		}
	}
?>

</tr>
</tbody>
</table>
</form>
<p>&nbsp;</p>

<script>
$(function(){
    $("#sel_fr").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
});

$(function(){
    $("#sel_to").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
});
</script>