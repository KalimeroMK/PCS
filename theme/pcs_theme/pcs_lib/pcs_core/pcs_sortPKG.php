<?php
include_once(__DIR__ . '/../common.php');

include_once(__DIR__ . '/pcs_config.php');

$old_table = array();
$new_table = array();
$sort_table = array();

$old_data = explode(';', $_POST['post_order']);
$old_qty = $_POST['post_qty'];
for ($old_idx = 0; $old_idx < $old_qty; $old_idx++) {
    $old_table[$old_idx] = explode(',', $old_data[$old_idx]);
}

$new_data = explode(';', $_POST['current_order']);
$new_qty = $_POST['current_qty'];
for ($new_idx = 0; $new_idx < $new_qty; $new_idx++) {
    $new_table[$new_idx] = explode(',', $new_data[$new_idx]);
}

$idx = 0;

for ($i = 0; $i < $old_qty; $i++) {
    for ($j = 0; $j < $new_qty; $j++) {
        if ($old_table[$i][1] == $new_table[$j][1]) {
            $sort_table[$idx][0] = $i + 1;
            $sort_table[$idx][1] = $j + 1;
            echo '[' . $idx . '] ' . $sort_table[$idx][0] . ' -> ' . $sort_table[$idx][1] . ' ~~~ <br>';
            $idx++;
        }
    }
}

$idx--;

$query_pkg_sort = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor WHERE pkg_no = "' . $_POST['pkg'] . '" AND latest = "Y"';
$sql_pkg_sort = sql_query($query_pkg_sort);


while ($sql_pkg_array = sql_fetch_array($sql_pkg_sort)) {
    $query_str = $sql_pkg_array['joint_info'];
    if ($old_qty < $new_qty) {
        for ($i = $idx; $i >= 0; $i--) {
            $query_str = str_replace(',,' . $sort_table[$i][0] . ',', ',,' . $sort_table[$i][1] . ',', $query_str);
            echo '[' . $i . '] ' . $query_str . '<br>';
        }
    }
    if ($old_qty > $new_qty) {
        for ($i = 0; $i <= $idx; $i++) {
            $query_str = str_replace(',,' . $sort_table[$i][0] . ',', ',,' . $sort_table[$i][1] . ',', $query_str);
            echo '[' . $i . '] ' . $query_str . '<br>';
        }
    }
    $query_sort = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor SET joint_info = "' . $query_str . '" WHERE dwg_no = "' . $sql_pkg_array['dwg_no'] . '" AND pkg_no = "' . $_POST['pkg'] . '" AND latest = "Y"';
    sql_query($query_sort);
}

$query_qty = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_pkg_stat SET dwg_qty = ' . $new_qty . ' WHERE pkg_no = "' . $_POST['pkg'] . '"';
sql_query($query_qty);


echo '
	<script>
	opener.document.location.reload();
	window.open("about:blank","_self").self.close();
	</script>
	';

?>
