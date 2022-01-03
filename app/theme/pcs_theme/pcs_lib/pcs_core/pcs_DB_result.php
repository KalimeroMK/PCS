    <?php
if($_GET['rlt'] == 'no') {	?>
    <span style="color: black; font-size: large; ">
Only Zip file can be uploaded!!!!
</span>
<?php } 
else {
	switch($_GET[wr_id]){
		case 2 : $pcs_typ = 'joint';		break;
		case 3 : $pcs_typ = 'spool';		break;
		case 4 : $pcs_typ = 'drawing';		break;
		case 5 : $pcs_typ = 'pnid';			break;
		case 6 : $pcs_typ = 'package';		break;
		case 7 : $pcs_typ = 'bmlist';		break;
		case 8 : $pcs_typ = 'itemcode';		break;
		case 9 : $pcs_typ = 'equipment';	break;
		default: break;
	}

	$query_j_qty = "SELECT COUNT(*) FROM ".G5_TABLE_PREFIX."pcs_info_".$pcs_typ;
	$j_qty = pcs_sql_value($query_j_qty);
?>
    <span style="color: black; font-size: large; ">
Total <strong><?php echo $j_qty;?></strong> <?php echo $pcs_typ.'s';?> inserted into PCS Database.<br>
</  span>
<?php } ?>
<p>&nbsp;</p>