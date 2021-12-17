<?php
if($_POST['folder'] || $_POST['ph']){include_once (PCS_LIB.'/pcs_photo.php');}
else {
?>

    <div class="list_01">
        <ul>
<?php
	if($_GET['bo_table']=='spool' && G5_IS_MOBILE && $_GET['x']){
		$query_spool_gps = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_spl_stat SET chk_by = "'.$member['mb_nick'].'", chk_tm="'.G5_TIME_YMDHIS.'", gps_lat="'.$_GET['x'].'", gps_lon="'.$_GET['y'].'" WHERE spool_no = "'.$_GET['stx'].'"';
		sql_query ($query_spool_gps);
	}

	for ($i=0; $i<count($list); $i++) {
		$or_sub = str_replace("<b class=\"sch_word\">","",$list[$i]['subject']) ;
		$or_sub = str_replace("</b>","",$or_sub) ;
		$or_sub = str_replace('/','_',$or_sub) ;
		switch ($_GET['bo_table']) {
			case 'spool' 	:	$datm = 'chk_tm';	$deif='location';	$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_spl_stat WHERE spool_no = "'.$or_sub.'"';	break;
			case 'iso' 	:	$datm = 'shop_dwg';	$deif='rev_no';		$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$or_sub.'"';	break;
			case 'plan' 	:	$datm = 'area';		$deif='rev_no';		$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_plan WHERE plan_no = "'.$or_sub.'"';	break;
			case 'pnid' 	:	$datm = 'reg_date';	$deif='rev_no';		$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid WHERE pnid_no = "'.$or_sub.'"';	break;
			case 'work' 	:	$datm = 'work_no';	$deif='unit';		$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_work WHERE work_no = "'.$or_sub.'"';	break;
			case 'daily' 	:	$datm = 'wr_3';		$deif='wr_1';		$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_subject = "'.$or_sub.'"';	break;
			case 'tp' 		:	$datm = 'pnid_no';	$deif='plan_no';	$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_tp WHERE tp_no = "'.$or_sub.'"';		break;
			case 'package' 	:	$datm = 'last_chk';	$deif='';			$query_pcs = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pkg_stat WHERE pkg_no = "'.$or_sub.'"';	break;
			default			:	break;
		}
		$sql_pcs = sql_query ($query_pcs);
		$sql_pcs_arr = sql_fetch_array ($sql_pcs);

?>
			<li class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">

                <div class="bo_cnt">

                    <?php
                    if ($is_category && $list[$i]['ca_name']) {
                    ?>
                    <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                    <?php } ?>

                    <a href="<?php echo $list[$i]['href'] ?>" class="bo_subject">
                        <?php echo $list[$i]['icon_reply']; ?>
                        <?php if ($list[$i]['is_notice']) { ?><strong class="notice_icon"><i class="fa fa-volume-up" aria-hidden="true"></i>공지</strong><?php } ?> 
                        <?php echo $list[$i]['subject'] ?>
                    </a>

                </div>
				
                <div class="bo_info" style="width: 50%;float: left;">
                    <span style="width:200px;" >
<?php
	if($_GET['bo_table']=='tp') {
		$sql_tp_photo = sql_fetch_array (sql_query ('SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_tp_stat WHERE tp_no = "'.$or_sub.'"'));
		
		$tp_dwg = explode(';',$sql_pcs_arr['dwg_no']);
		$tp_dwg_count = count($tp_dwg)-1;
		for($ref_dwg=0;$ref_dwg<$tp_dwg_count;$ref_dwg++){
			$sql_ref_dwg = sql_fetch_array (sql_query ('SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$tp_dwg[$ref_dwg].'"'));
			echo '<a href = "javascript:document.marked'.$i.$ref_dwg.'.submit()" ><b><font size="1">'.$sql_ref_dwg['dwg_no'].'</font></b></a>';	viewPDF('marked'.$i.$ref_dwg, 'fab', $sql_ref_dwg['dwg_no'], $sql_ref_dwg['rev_no']); 
		}
	}
	else if($_GET['bo_table']=='iso'){
		echo '<b><a href = "javascript:document.ISO'.$i.$ref_dwg.'.submit()" > rev_'.$sql_pcs_arr['rev_no'].'</a></b>';
		viewPDF('ISO'.$i, 'feb', $or_sub, $sql_pcs_arr['rev_no']);
	}
	else if($_GET['bo_table']=='daily'){echo 'Issue : '.$sql_pcs_arr[$deif];}
	else{echo $deif.' : '.$sql_pcs_arr[$deif];}
?> 
					</span>
                </div>
				
				<div class="bo_info" style="width: 50%;float: right; text-align:right;">
                    <span class="bo_date" > <?php if(0) {echo '<i class="fa fa-clock-o" aria-hidden="true"></i>'; }?>
<?php
	if($_GET['bo_table']=='iso'||$_GET['bo_table']=='plan'||$_GET['bo_table']=='pnid'||$_GET['bo_table']=='work') {
		if($_GET['bo_table']=='plan'){
			$tp_qty = substr_count($sql_pcs_arr['tp_no'],';');
			echo '<b><a id="rink'.$i.'" >'.$sql_pcs_arr[$datm];
			if($tp_qty>0){echo ' / TP - '.$tp_qty.' ea';}
			echo '</a></b>';
		}
		else if($_GET['bo_table']=='iso'){
			if($sql_pcs_arr[$datm]){
				echo '<b><a id="rink'.$i.'" >'.$sql_pcs_arr[$datm].'</a></b>';
			}
			else {echo '<b><a id="rink'.$i.'" ><font color = "blue">ISO Dwg.</font></a></b>';}
		}
		else if($_GET['bo_table']=='work'){
			$work_file = PCS_WORK_PDF.'/'.$or_sub.'.pdf';
			if (file_exists($work_file)) {echo '<b><a id="rink'.$i.'" >'.date ("Y-m-d", filemtime($work_file)).'</a></b>';} else {echo '-';}
		}
		else {echo '<b><a id="rink'.$i.'" >'.$sql_pcs_arr[$datm].'</a></b>';}
		echo '	<script>
					document.getElementById("rink'.$i.'").href = "javascript:document.submit_for'.$i.'.submit()";
				</script>';
	}
	elseif($_GET['bo_table']=='spool') {
		if(!$sql_pcs_arr[$datm]){$scantime = '<font color = "red">No spool data</font>';}
			else if($sql_pcs_arr[$datm]=='0000-00-00 00:00:00'){$scantime = 'Not yet Scaned';}
			else{$scantime = '<font color = "blue">'.$sql_pcs_arr[$datm].'</font>';}
		echo '<a onclick=\'window.open("'.PCS_LIB_URL.'/pcs_googlemap.php?lat='.$sql_pcs_arr['gps_lat'].'&lon='.$sql_pcs_arr['gps_lon'].'","w","width=600");\'><b>'.$scantime.'</b></a>';
	}
	elseif($_GET['bo_table']=='tp') {
		if($sql_tp_photo['tp_photo2']){
			echo '<div class="bo_info" style="width: 50%;float: left;">';
			photo_thumb('tp', $sql_tp_photo['tp_photo2'], 'photo2', 80, 'thumb_');
			echo '</div>';
		}
		if($sql_tp_photo['tp_photo1']){
			echo '<div class="bo_info" style="width: 50%;float: right; text-align:right;">';
			photo_thumb('tp', $sql_tp_photo['tp_photo1'], 'photo1', 80, 'thumb_');
			echo '</div>';
		}
	}
	elseif($_GET['bo_table']=='daily') {
		$daily_time = explode(';',$sql_pcs_arr['wr_3']);
		echo $daily_time[$sql_pcs_arr['wr_1']];
	}
	else {echo $sql_pcs_arr[$datm];}
?>
					</span>
<?php
	if($_GET['bo_table']=='iso')	{
		if($sql_pcs_arr[$datm]){viewPDF('submit_for'.$i, 'shop', $or_sub, $sql_pcs_arr['shop_dwg']);}
		else {viewPDF('submit_for'.$i, 'feb', $or_sub, $sql_pcs_arr['rev_no']);}
	}
	else if($_GET['bo_table']=='plan')	{viewPDF('submit_for'.$i, 'plan', $or_sub, $sql_pcs_arr['rev_no']);}
	else if($_GET['bo_table']=='pnid')	{viewPDF('submit_for'.$i, 'pnid', $or_sub, $sql_pcs_arr['rev_no']);}
	else if($_GET['bo_table']=='work')	{viewPDF('submit_for'.$i, 'work', $or_sub, '0');}
?>
                </div>
                
            </li>

<?php } ?>
            <?php if (count($list) == 0) { echo '<li class="empty_table">No list.</li>'; } ?>
        </ul>
    </div>
<?php
//echo '----------------';
}
?>	