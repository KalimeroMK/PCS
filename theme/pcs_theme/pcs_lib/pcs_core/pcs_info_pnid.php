<?php 
if(!G5_IS_MOBILE) {
	$query_pnid = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid WHERE pnid_no = "'.$view['wr_subject'].'"';
	$sql_pnid = sql_query ($query_pnid);
	$sql_pnid_arr = sql_fetch_array ($sql_pnid);

	$pnid_array = explode(';',$sql_pnid_arr['con_pnid']);
	$EQ_array = explode(';',$sql_pnid_arr['m_item']);

	if($_POST['num_app'] ?? null){
		$query_approve = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_pnid_coor SET pnid_state = "'.$_POST['num_app'].'" WHERE pnid_no = "'.$view['wr_subject'].'" ';
		sql_query ($query_approve);
	}
	
	$query_dwg_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_no = "'.$view['wr_subject'].'"';
	$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
	$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);

	$pkg_array = array();
	$pnid_txt = explode(';',$sql_dwg_coor_array['pnid_coor'] ?? '');
	foreach($pnid_txt as $pkg_txt){
		$pkg = explode(',',$pkg_txt);
		if(isset($pkg[1]) && $pkg[1] !== '' && $pkg[1] !== '0'){$pkg_array[$pkg[1]] = $pkg[1];}
	}
?>

<table class="main">
<caption> SPECIFICATION </caption>
<tbody>

<tr>
<td class="main_td td_sub" style="height:80px;" colspan="6"> <a href = 'javascript:document.submit_for.submit()'> <b> P&ID INFORMATION </b> </a> </td>
	<form name='submit_for' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post" target="<?php echo $view['wr_subject'];?>" onSubmit="return doSumbit()"> 
	<input type="hidden" name="folder" value="pnid">
	<input type="hidden" name="file" value="<?php echo $view['wr_subject'];?>">
	<input type="hidden" name="rev" value="<?php echo $sql_pnid_arr['rev_no'];?>">
	</form>
</tr>
<tr>
<td class="main_td td_sub" style="height:80px;"> P&ID No. </td>
<td class="main_td" colspan="2">
<?php	$pnid_state = $sql_dwg_coor_array['pnid_state'] ?? '';
if($pnid_state!='Approved' && $member['mb_3']>0) { ?>
	<a href = 'javascript:document.numbering_form.submit()'> <?php echo $view['wr_subject']; ?> </a>
	<form name="numbering_form" action="<?php echo PCS_CORE_URL; ?>/pcs_mark_pnid_pdf.php" method="post" target="result" onSubmit="return doSumbit()"> 
	<input type="hidden" name="key" value="key">
	<input type="hidden" name="fn" value="<?php echo $view['wr_subject']; ?>">
	<input type="hidden" name="rev" value="<?php echo $sql_pnid_arr['rev_no']; ?>">
	<input type="hidden" name="unit" value="<?php echo $sql_pnid_arr['unit']; ?>">
	</form> 
<?php	}
		else {echo $view['wr_subject']; }
?>
</td>
<td class="main_td td_sub">	 Revision No. </td>
<td class="main_td">
	<a href = 'javascript:document.m_pnid.submit()'><b><?php echo $sql_pnid_arr['rev_no']; ?> </b></a><?php viewPDF('m_pnid','pnid',$view['wr_subject'],$sql_pnid_arr['rev_no']);?>
</td>
<td class="main_td">
<?php
	$txt_color = 'black';
$txt_value = '';
if ($pnid_state=='Approved') {
    $txt_color = 'green';
    $txt_value = 'Marked';
} elseif ($pnid_state=='Marked') {
    $txt_color = 'blue';
    $txt_value = 'Approved';
}
if($member['mb_3']>2){
	echo '
		<a href = "javascript:submit_mark.submit()" ><font color = "'.$txt_color.'"><b>'.$pnid_state.'</b></font></a>
		<form name="submit_mark" method="post" onSubmit="return doSumbit()">
		<input type="hidden" name="num_app" value="'.$txt_value.'">
		</form>';
}
else {
	echo '<font color = "'.$txt_color.'"><b>'.$pnid_state.'</b></font>';
}
?>
</td>
</tr>


<?php 
	add_tr($pnid_array, '#F6D8CE', 'CONNECTED P&ID', 'pnid');
	add_tr($EQ_array, '#CEF6F5', 'INCLUDED EQUIPMENT', 'equipment');
	if($pkg_array){
		echo '<tr>';
		$qty=count($pkg_array);
		if($qty>0){ echo '<td class="main_td" colspan=6 style="background-color: #FFBF00; height:50px;"><b>INCLUDED PACKAGE</td></tr>';
	
			$j=0;
			foreach ($pkg_array as $pkg_no){

				$query_inc_dwg = "SELECT wr_id FROM ".G5_TABLE_PREFIX."write_package WHERE wr_subject = '".$pkg_no."'";
				$sql_inc_dwg = sql_query ($query_inc_dwg);
				$sql_inc_dwg_arr = sql_fetch_array ($sql_inc_dwg);
				
				echo '<td class="jnt_td" style="height:80px;font-size:18px;">';

				if($pkg_no){
					$j++;
			
					if($sql_inc_dwg_arr['wr_id']){ echo '<a href='.G5_BBS_URL.'/board.php?bo_table=package&wr_id='.$sql_inc_dwg_arr['wr_id'].'> <b>'.$pkg_no.'</b></a>';}
					else {echo '<mark>'.$pkg_no.'</mark>';}
				}
				echo'</a></td>';
				if($j%6==0){echo'</tr><tr>';}	
		
			}
			if($j % 6 !== 0){for($k=0;$k<6-($j%6);$k++){ echo '<td class="main_td" style="height:80px;font-size:18px;"></td>';}	}
		}
		echo '</tr>';
	}
?>

</tbody>
</table>
<?php
}
?>
