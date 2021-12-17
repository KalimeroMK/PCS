<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);



include_once('./_common.php');
include_once('./pcs_config.php');

	
	$query_pkg_coor_check = "SELECT * FROM ".G5_TABLE_PREFIX."pcs_info_pkg_coor";
	$sql_pkg_coor_check = sql_query ($query_pkg_coor_check);
	

while ($sql_pkg_coor_array = sql_fetch_array ($sql_pkg_coor_check))	{
	
	$row_value ='';
	$dwg_value ='';
	$jointcoor = explode(";",$sql_pkg_coor_array['joint_info']);
	
	for($i=0;$i<count($jointcoor)-1;$i++){
		
		$jointcoor_val = explode(",",$jointcoor[$i]);
		
		for($j=0;$j<count($jointcoor_val)-1;$j++){
			
			if(1<$j && $j<6){
				$jointcoor_val[$j] = round($jointcoor_val[$j]*2100/2339);
			}
			
		$row_value = $row_value.$jointcoor_val[$j].',';	
		}
	$row_value = $row_value.';';
	}
	
//	print_r($jointcoor_val);
	
//	$row_value = trim(implode(',',$jointcoor_val));

//echo($row_value);
	
	$query_pkg_coor = "UPDATE ".G5_TABLE_PREFIX."pcs_info_pkg_coor SET joint_info = '".$row_value."' WHERE dwg_no = '".$sql_pkg_coor_array['dwg_no']."' AND pkg_no = '".$sql_pkg_coor_array['pkg_no']."' AND rev_no = '".$sql_pkg_coor_array['rev_no']."'";
	sql_query ($query_pkg_coor);

}

?>
