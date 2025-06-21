<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

	$query_pkg_coor_check = "SELECT * FROM ".G5_TABLE_PREFIX."pcs_info_tp_stat";
	$sql_pkg_coor_check = sql_query ($query_pkg_coor_check);
	
//echo $query_pkg_coor_check;
while ($sql_pkg_coor_array = sql_fetch_array ($sql_pkg_coor_check))	{
	$filename  = get_safe_filename($sql_pkg_coor_array['tp_photo2'].'.jpg');
pcsthumbnail($filename, PCS_PHOTO_TP, PCS_PHOTO_TP, 180, 135, false);	

//	print_r($jointcoor_val);
	
//	$row_value = trim(implode(',',$jointcoor_val));

echo PCS_PHOTO_TP.'<br>';
	

}

?>
