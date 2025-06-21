<?php 
	if(!G5_IS_MOBILE&&$wr_id==1) {
		$mysql_field_array = array('inspection','unit','dwg_no','material','s_f','rlt_by','rlt','date');
		if(!empty($_POST['btn_check'])) 	{include_once (PCS_LIB.'/pcs_info_insp_ilist.php');}
		elseif($member['mb_1']>1)	{include_once (PCS_LIB.'/pcs_info_insp_isel.php');}
		else {echo '<font size = 5>Only <Strong>Authorized Member</strong> can ckeck Inspection status.</font>';}
	}
	if(!G5_IS_MOBILE&&$wr_id==2) {
		$mysql_field_array = array('ag_ug','unit','material','paint_code','spool_no','location','chk_by','chk_tm','photo_by','photo_tm');
		if(!empty($_POST['btn_check'])) 	{include_once (PCS_LIB.'/pcs_info_insp_slist.php');}
		elseif($member['mb_1']>1)	{include_once (PCS_LIB.'/pcs_info_insp_ssel.php');}
		else {echo '<font size = 5>Only <Strong>Authorized Member</strong> can ckeck Spool status.</font>';}
	}
	if(!G5_IS_MOBILE&&$wr_id==3) {
		$mysql_field_array = array('unit','tp_no','tp_photo1_by','tp_photo1_tm','tp_photo2_by','tp_photo2_tm','tp_photo3_by','tp_photo3_tm');
		if(!empty($_POST['btn_check'])) 	{include_once (PCS_LIB.'/pcs_info_insp_tplist.php');}
		elseif($member['mb_1']>1)	{include_once (PCS_LIB.'/pcs_info_insp_tpsel.php');}
		else {echo '<font size = 5>Only <Strong>Authorized Member</strong> can ckeck Spool status.</font>';}
	}
	if($wr_id==4) {include_once (PCS_LIB.'/pcs_insp_codescan.php');}
?>
