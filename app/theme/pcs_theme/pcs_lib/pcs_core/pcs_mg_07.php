<table style="font-size:20px; border-width: 1px 1px 0px 0px; border-style: solid solid none none; border-color: black black currentColor currentColor;" width='100%' border="0" cellspacing="0" cellpadding="0">
<caption> <p align='center'> <span style="font-size: 30pt;"> PACKAGE-LINE CLASS STATUS </span> </p> </caption>
<tbody>
<tr>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 50px; height: 40px; background-color: plum;" ><p align="center">No.</p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 200px; height: 40px; background-color: plum;" ><p align="center">dwg</p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 50px; height: 40px; background-color: plum;" ><p align="center">joint</p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 50px; height: 40px; background-color: plum;" ><p align="center">S/F</p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 220px; height: 40px; background-color: plum;" ><p align="center">time</p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor currentColor black black; width: 600px; height: 40px; background-color: plum;" ><p align="center">joint</p></td>
</tr>

<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1); 


	$idx=0;
	$table_line=1;

	$query_class = "SELECT * FROM cfp_info_dwg_coor where dwg_no LIKE '%-107-%' AND latest = 'Y' ORDER BY dwg_no";
	$sql_class = sql_query ($query_class);
	
while ($sql_class_arr = sql_fetch_array ($sql_class))	{
	
?>


<?php
	$jnt_dwg = explode(';', $sql_class_arr['joint_info'] );

	for($i=0;$i<count($jnt_dwg)-1;$i++){
		$atm_dwg = explode(",",$jnt_dwg[$i]);
		$new_atm_dwg = '';

		if(($atm_dwg[6]=='shop'||$atm_dwg[6]=='field')&&$atm_dwg[12]=='Act'){
			$idx++;
?>
<tr>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height: 40px; background-color: white;"><p align="center">
<?php echo $idx;?></p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height: 40px; background-color: white;"><p align="center">
<?php echo $atm_dwg[5];?></p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height: 40px; background-color: white;"><p align="center">
<?php echo $atm_dwg[10]?></p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height: 40px; background-color: white;"><p align="center">
<?php echo $atm_dwg[6];?></p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height: 40px; background-color: white;"><p align="center">
</p></td>
<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height: 40px; background-color: white;"><p align="center">
</p></td>
</tr>
<?php 	}
	}
} ?>

</tbody>
</table>
