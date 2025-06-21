<?php
	$field_arr = array('ag_ug','unit','material','test_type','paint_code','location');
	$fld_qty = count($field_arr);

if($_POST['Spec']){
	$query_table = 'SHOW TABLES';
	$sql_table = sql_query ($query_table);
	
	while ($table_arr = sql_fetch_array ($sql_table))	{
		$table_name = $table_arr['Tables_in_'.G5_MYSQL_DB];
		
		if(strpos($table_name, '_pcs_') !== false){
			$query_fld = 'DESCRIBE '.$table_name;
			$sql_fld = sql_query ($query_fld);
			
			while ($fld_arr = sql_fetch_array ($sql_fld))	{
				
				if(strpos($fld_arr['Type'],'enum') !== false) {
					for($i=0;$i<$fld_qty;$i++){
						if($field_arr[$i]==$fld_arr['Field']){
							
							$query_change = 'ALTER TABLE `'.$table_name.'` CHANGE `'.$fld_arr['Field'].'` `'.$fld_arr['Field'].'` ENUM('.str_replace('\\','',$_POST[$fld_arr['Field']]).')';
							sql_query ($query_change);
							
						}
					}
				}
			}
		}
	}
	echo '<script type="text/javascript"> location.href="'.G5_URL.'" </script>';
}

	$query_field = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_iso';
	$field_enum_value1 = enum_txt($query_field);

	$query_field = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_spl_stat';
	$field_enum_value2 = enum_txt($query_field);

	function enum_txt($query): array
    {

		$sql_field = sql_query ($query);

		while ($fld_arr = sql_fetch_array ($sql_field))	{
			if(strpos($fld_arr['Type'],'enum') !== false) {
				$temp_str = str_replace('enum(','',$fld_arr['Type']);
				$temp_str = str_replace(')','',$temp_str);
				$qry_field_name[$fld_arr['Field']] = $temp_str;
			}
		}
		return $qry_field_name;
	}


?>
<style>
	input {
		padding:0px 0px 0px 5px; text-align:left;width:95%;height:50px;font-size:30px;border:none;border-right:0px; border-top:0px; boder-left:0px; boder-bottom:0px;
	}
</style>

<form name='submit_form1' method="post" onSubmit="return doSumbit()"> 
<input type='hidden' name='Spec' value='Y'>
<table class="main" >
<caption> <a href = 'javascript:document.submit_form1.submit()'> PROJECT SPECIFICATION </a></caption>
<tbody>
<?php
for ($i=0;$i<$fld_qty;$i++) {
	if($i==$fld_qty-1){
?>
<tr>
	<td class="main_td td_sub" style="width:25%"> <?php if($field_arr[$i]=='ag_ug') {echo 'LEVEL';} else {echo strtoupper(str_replace('_',' ',$field_arr[$i]));} ?> </td>
	<td class="main_td"> <input type="text" name="<?php echo $field_arr[$i];?>" value="<?php echo $field_enum_value2[$field_arr[$i]];?>"></td>
</tr>
<?php
	}
	else{
?>
<tr>
	<td class="main_td td_sub" style="width:25%"> <?php if($field_arr[$i]=='ag_ug') {echo 'LEVEL';} else {echo strtoupper(str_replace('_',' ',$field_arr[$i]));} ?> </td>
	<td class="main_td"> <input type="text" name="<?php echo $field_arr[$i];?>"	value="<?php echo $field_enum_value1[$field_arr[$i]];?>"></td>
</tr>
<?php
	}
}
?>

</tbody>
</table>
</form>
<p>&nbsp;
