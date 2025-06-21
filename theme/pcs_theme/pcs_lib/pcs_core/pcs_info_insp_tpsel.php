<?php
	$table_field_array = array('Area','TP number','Photo 1 by','Photo 1 Date','Photo 2 by','Photo 2 Date','Photo 3 by','Photo 3 Date');
	$table_width_array = array(10,9,7,20,7,20,7,20);

	$query_field = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_tp';
	$field_enum_value = enum_value($query_field);

	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<form name='submit_form' method="post" onSubmit="return doSumbit()">

<table class="main">
<caption> <a href = 'javascript:document.submit_form.submit()'> SELECT Tie-in Point STATUS </a> </caption>
<tbody>
<tr>
<?php
	for($i=0; $table_field_array[$i]; $i++){
		echo '<td class="jnt_td jnt_th" style="width: '.$table_width_array[$i].'%">'.$table_field_array[$i].'</td>'; 
	}
?>
</tr>

<tr>
<input type='hidden' name='btn_check' value='<?php echo $_GET['wr_id']; ?>'>
<?php
	for($i=0; $table_field_array[$i]; $i++){
		switch ($i)	{
		
			case 0 :
?>
<td class="jnt_td" >
	<select name='<?php echo $mysql_field_array[$i]; ?>' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
		<option value=''>-</option>
		<?php sel_option_enum($field_enum_value[$mysql_field_array[$i]],''); ?>
	</select>
</td>

<?php
			Break;

			case 1 :
?>
<td class="jnt_td" ><input type='text' name='<?php echo $mysql_field_array[$i]; ?>' style='padding:0px 0px 0px 5px; text-align:left;width:95%;height:30px;font-size:15px;background-color:bisque;'></td>
<?php		Break;

			case 3 :
				$datxt1f = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" autocomplete="off" name="sel_1f" id="sel_1f" value="" onchange="datfol1();">';
				$datxt1t = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" autocomplete="off" name="sel_1t" id="sel_1t" value="">';
?>

<td class="jnt_td" style="padding:0 20px 0 0 ; text-align:right;"><?php echo $datxt1f.' ~ '.$datxt1t; ?></td>

<?php		
			Break;

			case 5 :
				$datxt2f = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" autocomplete="off" name="sel_2f" id="sel_2f" value="" onchange="datfol2();">';
				$datxt2t = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" autocomplete="off" name="sel_2t" id="sel_2t" value="">';

?>

<td class="jnt_td" style="padding:0 20px 0 0 ; text-align:right;"><?php echo $datxt2f.' ~ '.$datxt2t; ?></td>

<?php		
			Break;

			case 7 :
				$datxt2f = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" autocomplete="off" name="sel_3f" id="sel_3f" value="" onchange="datfol3();">';
				$datxt2t = '<input style="width: 100px; text-align:center;height:30px;font-size:15px;background-color:bisque;" type="text" autocomplete="off" name="sel_3t" id="sel_3t" value="">';

?>

<td class="jnt_td" style="padding:0 20px 0 0 ; text-align:right;"><?php echo $datxt2f.' ~ '.$datxt2t; ?></td>

<?php		
			Break;

			default :
?>
			
<td class="jnt_td" >
	<select name='<?php echo $mysql_field_array[$i]; ?>' style='WIDTH: 95%; height: 30px; font-size:15px; background-color:bisque'>
		<option value=''>-</option>
		<option value='<?php echo $member['mb_nick'];?>'>Myself</option>
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
    $("#sel_1f").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
	$("#sel_1t").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
    $("#sel_2f").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
	$("#sel_2t").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
    $("#sel_3f").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
	$("#sel_3t").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-1:c", maxDate: "+365d" });
});

function datfol1(){
	document.getElementById('sel_1t').value = document.getElementById('sel_1f').value;
}
function datfol2(){
	document.getElementById('sel_2t').value = document.getElementById('sel_2f').value;
}
function datfol3(){
	document.getElementById('sel_3t').value = document.getElementById('sel_3f').value;
}
</script>