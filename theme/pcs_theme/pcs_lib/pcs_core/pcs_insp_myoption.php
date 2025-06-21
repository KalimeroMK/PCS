
<?php
	if ($_POST['dwg_op1']) {
		$query_update = 'UPDATE '.G5_TABLE_PREFIX.'member SET mb_9 = "'.$_POST['dwg_op1'].'",  mb_10 = "'.$_POST['dwg_op2'].'" WHERE mb_no = "'.$_POST['mb_no'].'"';
		sql_query ($query_update);
		echo '<script type="text/javascript"> location.href="'.G5_URL.'" </script>';
	}

	$table_field_array = array('No','ID','Name','Position','Dwg Option','View Detail');
	$table_width_array = array(50, 50, 50, 50, 50, 50);
	$mysql_field_array = array('mb_no','mb_id','mb_name','mb_nick','mb_9','mb_10');
	$option1_array  = array('View Only','Download');
	$option2_array  = array('Original','Welding','PWHT','NDE','ALL');

	$query_member = 'SELECT * FROM '.G5_TABLE_PREFIX.'member WHERE mb_id = "'.$member['mb_id'].'"';
	$sql_member = sql_query ($query_member);
	
	$query_field = 'DESC '.G5_TABLE_PREFIX.'member';
	$field_enum_value = enum_value($query_field);
	
	$sql_member_arr = sql_fetch_array ($sql_member);
?>

<table class="main" style="width:100%">
<caption> <a href = 'javascript:document.submit_form.submit()'> MY DRAWING-VIEW OPTION </a> </caption>
<tbody>
<tr>
<?php for($i=1; $table_field_array[$i]; $i++){echo '<td class="main_td jnt_th" style="width: '.$table_width_array[$i].'px;">'.$table_field_array[$i].'</td>';}?>
</tr>

<tr>
<form name='submit_form' method="post" onSubmit="return doSumbit()">
<input type='hidden' name='<?php echo 'mb_no'; ?>' value=<?php echo $sql_member_arr['mb_no']; ?>>
<?php
	for($i=1; $table_field_array[$i]; $i++){ ?>

	<td class="main_td" style="width: <?php echo $table_width_array[$i]; ?>px;">

<?php
		if ($i<4) { echo $sql_member_arr[$mysql_field_array[$i]];}
		elseif ($i<5) {
?>
	<select name='dwg_op1' style='WIDTH: 95%; height: 40px; font-size:20px; background-color:bisque'>
	<option value='0'>-</option>
<?php sel_option_arr($option1_array, $sql_member_arr[$mysql_field_array[$i]]); ?>
	</select>

<?php
		}
		elseif ($i<6) {
?>
	<select name='dwg_op2' style='WIDTH: 95%; height: 40px; font-size:20px; background-color:bisque'>
	<option value='0'>-</option>
<?php sel_option_arr($option2_array, $sql_member_arr[$mysql_field_array[$i]]); ?>
	</select>

<?php
		}
	}
?>
</form>
</tr>
</tbody>
</table>