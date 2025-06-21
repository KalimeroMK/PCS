<?php

	$field_arr = array('item_1_dwg','item_1_no','item_2_no','w_type','j_type','j_size','j_sche','pmi_yn','pwht_yn','nde_rate','item_1_type','item_2_type','pkg_no');
	$th_arr = array('Joint<br>no.','Item_1 Drawing no.','PT no.1','PT no.2','Weld<br>Type','Joint<br>Type','Size','Sche.','PMI','PWHT','NDE<br>rate','Item 1','Item 2','Package no.');
	$width_arr = array(5,15,5,5,5,5,5,5,5,5,5,8,8,15);
	$jny_arr[3] = array('WELD','OYHER');
	$jny_arr[4] = array('BW','SW','LET','BR','PAD','BT','TH','PS');
	$jny_arr[5] = array('0.5','0.75','1','1.5','2','3','4','6','8','10','12','16','20','24');
	$jny_arr[6] = array('20','40','STD','60','80','120','160','XS','XXS');
	$jny_arr[9] = array('5','10','20','100');
	
	$field_cnt = count($field_arr);
if($_POST['j_mode'] != 'edit'){
	$dwgNo = $_POST['j_mode'];

	$query_test = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc WHERE dwg_no = "'.$dwgNo.'" AND j_type != "SPL" ORDER BY j_no';
	$sql_ref_dwg = sql_query ($query_test,true);
	$row=0;

	while ($sql_ref_dwg_arr = sql_fetch_array ($sql_ref_dwg))	{
		
		
		$query_jnt = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc SET '.$field_arr[0].' = "'.$_POST['sel'.$row].'" WHERE j_key = "'.$dwgNo.'_'.$sql_ref_dwg_arr['j_no'].'"';
		sql_query ($query_jnt,true);

		for($col=1;$col<$field_cnt;$col++){

		$query_jnt = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc SET '.$field_arr[$col].' = "'.$_POST['txt'.$row.'_'.$col].'" WHERE j_key = "'.$dwgNo.'_'.$sql_ref_dwg_arr['j_no'].'"';
		sql_query ($query_jnt,true);
		}
		$row++;
	}
	echo '<script type="text/javascript"> location.href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&wr_id='.$wr_id.'"; </script>';
}

else {
	$main_dwg = $_POST['m_dwg'];
	$cont_dwg = explode(';',$_POST['c_dwg']);
	$cont_dwg_qty = count($cont_dwg);
?>
<html>
<head>
<title> Joint Edit Page </title>
<link rel="stylesheet" href="<?php echo G5_THEME_CSS_URL; ?>/<?php echo G5_IS_MOBILE ? 'pcs_mobile' : 'pcs_default'; ?>.css">

</head>
<body>
<form name="joint_edit" method="post"> 
<table class="main">
<caption> <a href = 'javascript:document.joint_edit.submit()'> <?php echo $main_dwg; ?> JOINT EDIT </a></caption>
<tbody>

<?php
		echo '<tr>';
	for($i=0;$i<$field_cnt+1;$i++){

		echo '<td class="jnt_td jnt_th" style="width:'.$width_arr[$i].'%;">'.$th_arr[$i];
		if($i==$field_cnt){
			echo '<input onchange="fill_Pkg('.($i-1).')" type="text" autocomplete="off" name="input_all" id="input_all" style="text-align:center; height:30px; background-color: plum; width:100%; font-size:15px; padding:5px;" >';
		}
		echo '</td>';
	}
		echo '</tr>';
		
	$query_bm = 'SELECT A.dwg_no, A.p_no, B.* FROM '.G5_TABLE_PREFIX.'pcs_info_bmlist AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_itemcode AS B ON A.i_code = B.i_code WHERE A.dwg_no = "'.$main_dwg.'" ';

	for($codwg=0;$codwg<$cont_dwg_qty -1;$codwg++){
		$query_bm .= 'OR A.dwg_no = "'.$cont_dwg[$codwg].'" ';
	}

	
	$field_query = 'DESCRIBE '.G5_TABLE_PREFIX.'pcs_info_itemcode';
	$field_name = field_name_array($field_query);
	
	$row=0;

	$query_test = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc WHERE dwg_no = "'.$main_dwg.'" AND j_type != "SPL" ORDER BY j_no';

	$sql_ref_dwg = sql_query ($query_test,true);
	while ($sql_ref_dwg_arr = sql_fetch_array ($sql_ref_dwg))	{
?>

<tr>
<td class="jnt_td"><?php echo z_rem_jno($sql_ref_dwg_arr['j_no']); ?></td>

<?php
		for($col=0;$col<$field_cnt;$col++){
?>

<td class="jnt_td">
 
<?php
			if($col !== 0){
				$jnt_type = $sql_ref_dwg_arr[$field_arr[4]];
				echo '<input name="txt'.$row.'_'.$col.'" id="txt'.$row.'_'.$col.'" list="jnt'.$col.'"';
				if($col==1||$col==2){echo 'onchange="fill_Jinfo('.$row.')" onclick=\'javascript:this.value="";\' ';}
				echo 'type="text" autocomplete="off" style="text-align:center; height:50px;	width:100%; font-size:15px; padding:5px; border:none; background-color:white;" value="'.$sql_ref_dwg_arr[$field_arr[$col]].'"';
				if(($col==2&&($jnt_type=='BT'||$jnt_type=='PS'||$jnt_type=='TH'))){echo 'disabled';}
				echo '>';
				if($col>3){
					echo '<datalist id="jnt'.$col.'">';
					foreach($jny_arr[$col] as $arrlist){
						echo '<option value="'.$arrlist.'">';
					}
					echo '</datalist>';
				}
			}
			else{
				$jnt_type = $sql_ref_dwg_arr[$field_arr[4]];
				echo '<select name="sel'.$row.'" id="sel'.$row.'" style="text-align:center; height:50px; width:100%; font-size:15px; padding:5px; border:none; background-color:white;"
						value="'.$sql_ref_dwg_arr[$field_arr[$col]].'"> <option value="'.$main_dwg.'" >'.$main_dwg.'</option>';
				for($codwg=0;$codwg<$cont_dwg_qty -1;$codwg++){
					if($sql_ref_dwg_arr['item_1_dwg']==$cont_dwg[$codwg]){echo '<option value="'.$cont_dwg[$codwg].'" selected>'.$cont_dwg[$codwg].'</option>';}
					else {echo '<option value="'.$cont_dwg[$codwg].'">'.$cont_dwg[$codwg].'</option>';}
				}
				echo '</select>';
			}
?>

</td>

<?php
		}
?>
</tr>

<?php 
			$row++;
	}

?>
</tbody>
</table>
<br>
<input type='hidden' id='j_mode' name='j_mode' value='<?php echo $main_dwg; ?>'>

</form>
</body>

<script language="javascript">
var bmarray = new Array();
var mainDwg = '<?php echo $main_dwg; ?>';
<?php
	
	$sql_bm = sql_query ($query_bm,true);
	
	echo 'bmarray["'.$main_dwg.'"] = new Array(); ';
	for($codwg=0;$codwg<$cont_dwg_qty -1;$codwg++){
		echo 'bmarray["'.$cont_dwg[$codwg].'"] = new Array(); ';
	}

	
	while($sql_bm_arr = sql_fetch_array ($sql_bm)){
		
		
		$java_var_bmarray = 'bmarray["'.$sql_bm_arr['dwg_no'].'"]["'.$sql_bm_arr['p_no'].'"] = new Array(); ';
        $counter = count($field_name);
		
		for($i=2; $i<$counter ; $i++){
			$java_var_bmarray .= 'bmarray["'.$sql_bm_arr['dwg_no'].'"]["'.$sql_bm_arr['p_no'].'"]["'.$i.'"] = "'.$sql_bm_arr[$field_name[$i]].'"; ';
		}

		echo $java_var_bmarray;
	}
?>

function fill_Pkg(col){
	for (var i=0; i<<?php echo $row; ?>; i++) {
		document.getElementById('txt'+i+'_'+col).value = document.getElementById('input_all').value;
	}
}

function fill_Jinfo(row){

	var selDwg = document.getElementById('sel'+row).value;
	var pt_1 = document.getElementById('txt'+row+'_'+1).value;
	var pt_2 = document.getElementById('txt'+row+'_'+2).value;
	var jntype = document.getElementById('txt'+row+'_'+4).value;
	
	if(pt_1){	
		var item1type  = bmarray[selDwg][pt_1]['2'];
		var item1size1 = bmarray[selDwg][pt_1]['3']*1;
		var item1size2 = bmarray[selDwg][pt_1]['4']*1;
		var item1sche1 = bmarray[selDwg][pt_1]['5'];
		var item1sche2 = bmarray[selDwg][pt_1]['6'];
		var item1jtyp1 = bmarray[selDwg][pt_1]['7'];
		var item1jtyp2 = bmarray[selDwg][pt_1]['8'];
	}
	else {
		var item1type  = '';
		var item1size1 = 0;
		var item1size2 = 0;
		var item1sche1 = '';
		var item1sche2 = '';
		var item1jtyp1 = '';
		var item1jtyp2 = '';
	}
	
	
	if(pt_2){
		var item2type  = bmarray[mainDwg][pt_2]['2'];
		var item2size1 = bmarray[mainDwg][pt_2]['3']*1;
		var item2size2 = bmarray[mainDwg][pt_2]['4']*1;
		var item2sche1 = bmarray[mainDwg][pt_2]['5'];
		var item2sche2 = bmarray[mainDwg][pt_2]['6'];
		var item2jtyp1 = bmarray[mainDwg][pt_2]['7'];
		var item2jtyp2 = bmarray[mainDwg][pt_2]['8'];
	}
	else {
		var item2type  = '';
		var item2size1 = 0;
		var item2size2 = 0;
		var item2sche1 = '';
		var item2sche2 = '';
		var item2jtyp1 = '';
		var item2jtyp2 = '';
	}

	if(jntype=='BT'||jntype=='TH'||jntype=='PS'){fill_txt(row,jntype,item1size1,'-','-','-','-','-');}
	else
	{
		var itemtext = item1type+item2type;

		if((item1type=='PIPE' && item2type=='PIPE') || (item1type=='NIPPLE' && item2type=='NIPPLE')){
			if(item1size1>item2size1){fill_txt(row,'BR',item2size1,'-',item1type,'-',item2type,'-');}
			else if(item1size1==item2size1){fill_txt(row,item1jtyp1,item1size1,item1sche1,item1type,'-',item2type,'-');}
			else{fill_txt(row,'BR',item1size1,'-',item1type,'-',item2type,'-');}
		}
		
		else if(item1type=='PIPE' || item1type=='NIPPLE'){
			if(item2type.indexOf('OLET')>0){
				if(item1size1==item2size1){fill_txt(row,item2jtyp2,item1size1,item1sche1,item1type,'-',item2type,'-');}
				else {fill_txt(row,item2jtyp1,item2size1,item2sche1,item1type,'-',item2type,'-');}
			}
			else if(item2type=='FLANGE'){fill_txt(row,item2jtyp1,item2size1,item1sche1,item1type,'-',item2type,'-');}
			else if(item1size1==item2size1){fill_txt(row,item2jtyp1,item2size1,item2sche1,item1type,'-',item2type,'-');}
			else if(item1size1==item2size2){fill_txt(row,item2jtyp2,item2size2,item2sche2,item1type,'-',item2type,'-');}
		}
		
		else if(item2type=='PIPE' || item2type=='NIPPLE'){
			if(item1type.indexOf('OLET')>0){
				if(item1size1==item2size1){fill_txt(row,item1jtyp2,item1size1,item1sche1,item1type,'-',item2type,'-');}
				else {fill_txt(row,item1jtyp1,item1size1,item1sche1,item1type,'-',item2type,'-');}
			}
			else if(item1type=='FLANGE'){fill_txt(row,item1jtyp1,item2size1,item2sche1,item1type,'-',item2type,'-');}
			else if(item1size1==item2size1){fill_txt(row,item1jtyp1,item1size1,item1sche1,item1type,'-',item2type,'-');}
			else if(item1size2==item2size1){fill_txt(row,item1jtyp2,item1size2,item1sche2,item1type,'-',item2type,'-');}
		}
		
		else if(item1type.indexOf('OLET')>0){
			if(item2type=='CAP'){fill_txt(row,item1jtyp2,item1size1,item1sche1,item1type,'-',item2type,'-');}
			else {fill_txt(row,item1jtyp1,item1size1,item1sche1,item1type,'-',item2type,'-');}
		}
		else if(item2type.indexOf('OLET')>0){
			if(item1type=='CAP'){fill_txt(row,item2jtyp2,item2size1,item2sche1,item1type,'-',item2type,'-');}
			else {fill_txt(row,item2jtyp1,item2size1,item2sche1,item1type,'-',item2type,'-');}
		}
		else{
			if(item1size1==item2size1){fill_txt(row,item1jtyp1,item1size1,item1sche1,item1type,'-',item2type,'-');}
			if(item1size2==item2size1){fill_txt(row,item1jtyp2,item1size2,item1sche2,item1type,'-',item2type,'-');}
			if(item1size1==item2size2){fill_txt(row,item1jtyp1,item1size1,item1sche1,item1type,'-',item2type,'-');}
		}

		
	}
		
}


function fill_txt(r,jt,sz,sc,ty1,he1,ty2,he2){
	document.getElementById('txt'+r+'_'+4).value = jt;
	document.getElementById('txt'+r+'_'+5).value = sz;
	document.getElementById('txt'+r+'_'+6).value = sc;
	document.getElementById('txt'+r+'_'+10).value = ty1;
	document.getElementById('txt'+r+'_'+11).value = ty2;
}



</script>

<?php
}
?>