<?php
$query_spool = 'SELECT DISTINCT spool_no, unit, ag_ug, material, paint_code FROM ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc WHERE spool_no != "" ORDER BY spool_no';
$sql_spool = sql_query($query_spool);
$j = 0;
sql_query('TRUNCATE TABLE ' . G5_TABLE_PREFIX . 'write_spool');
while ($sql_spool_arr = sql_fetch_array($sql_spool)) {
    $j++;
    $query_insert = 'INSERT INTO ' . G5_TABLE_PREFIX . 'pcs_info_spool SET
					spool_no = "' . $sql_spool_arr['spool_no'] . '",
					ag_ug = "' . $sql_spool_arr['ag_ug'] . '",
					unit = "' . $sql_spool_arr['unit'] . '",
					material = "' . $sql_spool_arr['material'] . '",
					paint_code = "' . $sql_spool_arr['paint_code'] . '"';
    sql_query($query_insert);
    $query_insert = 'INSERT INTO ' . G5_TABLE_PREFIX . 'write_spool (wr_id,wr_num,wr_parent,wr_subject) VALUES ("' . $j . '","' . -$j . '","' . $j . '","' . $sql_spool_arr['spool_no'] . '")';
    sql_query($query_insert);

    $query_dwg_info = 'SELECT DISTINCT dwg_no FROM ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc WHERE spool_no != "' . $sql_spool_arr['spool_no'] . '"';
    $sql_dwg_info = sql_query($query_dwg_info);
    $i = 0;
    while ($sql_dwg_info_arr = sql_fetch_array($sql_dwg_info)) {
        $i++;
        $query_up = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_spool SET dwg' . $i . ' = "' . $sql_dwg_info_arr['dwg_no'] . '" WHERE spool_no = "' . $sql_spool_arr['spool_no'] . '"';
        sql_query($query_up);
    }
}
$query_insert = 'UPDATE ' . G5_TABLE_PREFIX . 'board set bo_count_write = ' . $j . ' WHERE bo_table = "spool"';
sql_query($query_insert);
?>

<font color=black size=5>
    Total <strong><?php echo $j; ?></strong> Spools registed into PCS Database.<br>
</font>
