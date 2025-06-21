<?php 
error_reporting(0);
	$query_ref_pkg = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_package';
	$sql_ref_pkg = sql_query ($query_ref_pkg);
	
	while ($sql_ref_pkg_arr = sql_fetch_array ($sql_ref_pkg)) {	
		echo $sql_ref_pkg_arr['pkg_no'].',';
		
		$query_pkg_stat = 'SELECT DISTINCT * FROM '.G5_TABLE_PREFIX.'pcs_info_drawing WHERE (pkg_no1 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no2 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no3 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no4 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no5 = "'.$sql_ref_pkg_arr['pkg_no'].'" OR pkg_no6 = "'.$sql_ref_pkg_arr['pkg_no'].'") ORDER BY line_size DESC';
		$sql_pkg_stat = sql_query ($query_pkg_stat);
		while ($sql_pkg_stat_arr = sql_fetch_array ($sql_pkg_stat)) {
			echo $sql_pkg_stat_arr['dwg_no'].',';
		}
		echo '<br>';
	}
?>
	