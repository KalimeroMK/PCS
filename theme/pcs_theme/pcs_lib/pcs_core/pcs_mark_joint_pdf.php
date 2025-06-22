<?php
include_once(__DIR__ . '/../common.php');

include_once(__DIR__ . '/pcs_config.php');
include_once(__DIR__ . '/pcs_common_function.php');
if (!defined('_GNUBOARD_')) exit;

$dwg_file = $_POST['fn'];
$shop_dwg = $_POST['sd'];
$dwg_rev = explode(';',$_POST['rev']);
	$rev_count = count($dwg_rev)-2;
	$latest_rev = $dwg_rev[$rev_count];
	$last_rev = $dwg_rev[$rev_count-1];
	

	
if ($_POST['key']=='update') {
$query_view_mark_dwg = 
	'CREATE VIEW markDwgJoint AS SELECT *
	FROM '.G5_TABLE_PREFIX.'pcs_info_jnt_sbc
	WHERE dwg_no = "'.$_POST['mapped_dwg'].'" ORDER BY j_no';
sql_query ($query_view_mark_dwg);

$query_view_mark_coor = 
	'CREATE VIEW markDwgCoor AS SELECT *
	FROM '.G5_TABLE_PREFIX.'pcs_info_iso_coor
	WHERE dwg_no = "'.$_POST['mapped_dwg'].'"';
sql_query ($query_view_mark_coor);

	if($_POST['joint_txt']=='clear') {

		$query_dwg_coor = 'DELETE FROM markDwgCoor WHERE rev_no = "'.$_POST['rev_dwg'].'"';
		sql_query ($query_dwg_coor);
		$query_dwg_jnt = 'DELETE FROM markDwgJoint WHERE pcs_fitup_rlt = ""';
		sql_query ($query_dwg_jnt);
//		unlink(PCS_DWG_FAB.'/fab_'.$_POST['mapped_dwg'].'_'.$_POST['rev_dwg'].'.pdf');
	}
	if ($_POST['joint_txt']&&$_POST['joint_txt']!='clear') {

		if(!$_POST['jnt_follow']){

			$query_dwg_coor_check = 'SELECT * FROM markDwgCoor WHERE rev_no = "'.$_POST['rev_dwg'].'"';
			$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
			$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);

			$joint_coor_info = $sql_dwg_coor_array['joint_info'];
		
			$query_dwg = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$_POST['mapped_dwg'].'" AND rev_no = "'.$_POST['rev_dwg'].'"';
			$sql_dwg = sql_query ($query_dwg);
			$sql_dwg_array = sql_fetch_array ($sql_dwg);

		
			
				$query_dwg_coor = 'UPDATE markDwgCoor SET latest = "N"';
				sql_query ($query_dwg_coor);

				$jnt_arr = explode(';',$_POST['joint_txt']);
				$jntfor = count($jnt_arr)-1;
				$r_pipe_qty = substr_count($_POST['joint_txt'],'R,0,ACT');
			

				if($joint_coor_info){	//갱신

					for($i=0;$i<$jntfor;$i++){
						$spn = ''; $s_f = 'F';
						$jnt_each_arr = explode(',',$jnt_arr[$i]);
						if($jnt_each_arr[6] != 'comment' && $jnt_each_arr[6] != 'coupling') {
							$pwht_yn = 'NO';	$pmi_yn = 'NO';
							switch($jnt_each_arr[6]) {
								case 'bolt' 	:	$j_type_sbc = 'BT';		$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'B0'.$jnt_each_arr[10] : 'B'.$jnt_each_arr[10];;		break;
								case 'thread' 	: 	$j_type_sbc = 'TH'; 	$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'T0'.$jnt_each_arr[10] : 'T'.$jnt_each_arr[10];;		break;
								case 'support' 	: 	$j_type_sbc = 'PS';		$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'S0'.$jnt_each_arr[10] : 'S'.$jnt_each_arr[10];; 		break;
								case 'spool' 	: 	$j_type_sbc = 'SPL';	$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'SP0'.$jnt_each_arr[10] : 'SP'.$jnt_each_arr[10];;		break;
								default		 	:	$j_type_sbc = 'after';	$w_type_sbc = 'WELD';	$pmi_yn = $sql_dwg_array['pmi']; $pwht_yn = $sql_dwg_array['pwht'];
										
									if ($jnt_each_arr[11]*1) {
                                        $jnt_or = preg_replace('/([a-zA-Z]+[1-9]||[a-zA-Z])/','',$jnt_each_arr[10]);
                                        if ($jnt_or*1<10) {
                                            $jnt_sbc = '00'.$jnt_each_arr[10];
                                        } elseif ($jnt_or*1<100) {
                                            $jnt_sbc =  '0'.$jnt_each_arr[10];
                                        }
                                    } elseif ($jnt_each_arr[10]*1<10) {
                                        $jnt_sbc = '00'.$jnt_each_arr[10];
                                    } elseif ($jnt_each_arr[10]*1<100) {
                                        $jnt_sbc = '0'.$jnt_each_arr[10];
                                    } else {$jnt_sbc = $jnt_each_arr[10];}

								break;
							}

							if($jnt_each_arr[6]=='shop') {$s_f = 'S'; $j_type_sbc = 'WJ'; $spn = $jnt_each_arr[15];} 
							if($jnt_each_arr[6]=='field') {$s_f = 'F'; $j_type_sbc = 'WJ'; $spn = '';};
							if($jnt_each_arr[6]=='spool') {$spn = $jnt_each_arr[5].'-'.$jnt_sbc;};
					

							$query_jnt_sbc = 'SELECT * FROM markDwgJoint WHERE j_key = "'.$_POST['mapped_dwg'].'_'.$jnt_sbc.'"' ;
							$sql_query_check = sql_query ($query_jnt_sbc,true);
							if(sql_fetch_array ($sql_query_check)) {
								$query_jnt_sbc = 'UPDATE markDwgJoint SET 
													s_f = "'.$s_f.'", 
													spool_no = "'.$spn.'", 
													j_stat = "'.$jnt_each_arr[12].'" 
												WHERE j_key = "'.$_POST['mapped_dwg'].'_'.$jnt_sbc.'"' ;	sql_query ($query_jnt_sbc);
								$query_dwg_sta = 'UPDATE markDwgCoor SET
													dwg_state = "Marked" 
												WHERE rev_no = "'.$_POST['rev_dwg'].'"';	sql_query ($query_dwg_sta);
							}
							else {
								$query_jnt_sbc = 'INSERT INTO markDwgJoint SET
													j_key = "'.$_POST['mapped_dwg'].'_'.$jnt_sbc.'" ,
													unit = "'.$sql_dwg_array['unit'].'" ,
													ag_ug = "'.$sql_dwg_array['ag_ug'].'" ,
													dwg_no = "'.$_POST['mapped_dwg'].'" ,
													j_no = "'.$jnt_sbc.'" ,
													material = "'.$sql_dwg_array['material'].'" ,
													paint_code = "'.$sql_dwg_array['paint_code'].'" ,
													s_f = "'.$s_f.'" ,
													spool_no = "'.$spn.'" ,
													j_stat = "'.$jnt_each_arr[12].'" ,
													j_cng = "'.G5_TIME_YMDHIS.'" ,
													w_type = "'.$w_type_sbc.'" ,
													j_type = "'.$j_type_sbc.'" ,
													pmi_yn = "'.$pmi_yn.'" ,
													pwht_yn = "'.$pwht_yn.'" ,
													nde_rate = "'.$sql_dwg_array['nde_rate'].'"';	sql_query ($query_jnt_sbc);
								

								$query_dwg_sta = 'UPDATE markDwgCoor SET dwg_state = "Marked" WHERE rev_no = "'.$_POST['rev_dwg'].'"';
								sql_query ($query_dwg_sta);
							}
						}
					}
				$query_dwg_coor = 'UPDATE markDwgCoor SET
									latest = "Y",
									work_id = "'.$_POST['dwg_modi'].'",
									time = "'.G5_TIME_YMDHIS.'",
									joint_info = "'.$_POST['joint_txt'].'",
									r_pipe = "'.$r_pipe_qty.'",
									dwg_state = "Marked" 
								WHERE rev_no = "'.$_POST['rev_dwg'].'"';	sql_query ($query_dwg_coor);
				}
				else {		//신규

					for($i=0;$i<$jntfor;$i++){
						$spn = ''; $s_f = 'F'; $spn = '';
						$jnt_each_arr = explode(',',$jnt_arr[$i]);
						if($jnt_each_arr[6] != 'comment' && $jnt_each_arr[6] != 'coupling') {
							$pwht_yn = 'NO';	$pmi_yn = 'NO';
							switch($jnt_each_arr[6]) {
								case 'bolt' 	:	$j_type_sbc = 'BT';		$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'B0'.$jnt_each_arr[10] : 'B'.$jnt_each_arr[10];;		break;
								case 'thread' 	: 	$j_type_sbc = 'TH'; 	$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'T0'.$jnt_each_arr[10] : 'T'.$jnt_each_arr[10];;		break;
								case 'support' 	: 	$j_type_sbc = 'PS';		$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'S0'.$jnt_each_arr[10] : 'S'.$jnt_each_arr[10];; 		break;
								case 'spool' 	: 	$j_type_sbc = 'SPL';	$w_type_sbc = 'OTHER';	$jnt_sbc = $jnt_each_arr[10] * 1 < 10 ? 'SP0'.$jnt_each_arr[10] : 'SP'.$jnt_each_arr[10];;		break;
								default		 	:							$w_type_sbc = 'WELD';	$pmi_yn = $sql_dwg_array['pmi']; $pwht_yn = $sql_dwg_array['pwht'];

									if ($jnt_each_arr[11]*1) {
                                        $jnt_or = preg_replace('/([a-zA-Z]+[1-9]||[a-zA-Z])/','',$jnt_each_arr[10]);
                                        if ($jnt_or*1<10) {
                                            $jnt_sbc = '00'.$jnt_each_arr[10];
                                        } elseif ($jnt_or*1<100) {
                                            $jnt_sbc =  '0'.$jnt_each_arr[10];
                                        }
                                    } elseif ($jnt_each_arr[10]*1<10) {
                                        $jnt_sbc = '00'.$jnt_each_arr[10];
                                    } elseif ($jnt_each_arr[10]*1<100) {
                                        $jnt_sbc = '0'.$jnt_each_arr[10];
                                    } else {$jnt_sbc = $jnt_each_arr[10];}
									
								break;
							}

							if($jnt_each_arr[6]=='shop') {$s_f = 'S'; $j_type_sbc = ''; $spn = $jnt_each_arr[15];} 
							if($jnt_each_arr[6]=='field') {$s_f = 'F'; $j_type_sbc = ''; $spn = '';};
							if($jnt_each_arr[6]=='spool') {$spn = $jnt_each_arr[5].'-'.$jnt_sbc;};
					
							$query_jnt_sbc = 'INSERT INTO markDwgJoint SET
												j_key = "'.$_POST['mapped_dwg'].'_'.$jnt_sbc.'" ,
												unit = "'.$sql_dwg_array['unit'].'" ,
												ag_ug = "'.$sql_dwg_array['ag_ug'].'" ,
												dwg_no = "'.$_POST['mapped_dwg'].'" ,
												j_no = "'.$jnt_sbc.'" ,
												material = "'.$sql_dwg_array['material'].'" ,
												paint_code = "'.$sql_dwg_array['paint_code'].'" ,
												s_f = "'.$s_f.'" ,
												spool_no = "'.$spn.'" ,
												j_stat = "'.$jnt_each_arr[12].'" ,
												j_cng = "'.G5_TIME_YMDHIS.'" ,
												w_type = "'.$w_type_sbc.'" ,
												j_type = "'.$j_type_sbc.'" ,
												pmi_yn = "'.$pmi_yn.'" ,
												pwht_yn = "'.$pwht_yn.'" ,
												nde_rate = "'.$sql_dwg_array['nde_rate'].'"';	sql_query ($query_jnt_sbc);
						}
					}
					$query_dwg_coor = 'INSERT INTO markDwgCoor SET
										dwg_no = "'.$_POST['mapped_dwg'].'",
										rev_no = "'.$_POST['rev_dwg'].'",
										joint_info = "'.$_POST['joint_txt'].'",
										latest = "Y",
										work_id = "'.$_POST['dwg_modi'].'",
										time = "'.G5_TIME_YMDHIS.'",
										r_pipe = "'.$r_pipe_qty.'",
										dwg_state = "Marked"';	sql_query ($query_dwg_coor);
				}


			
		}
	
		else {
		$r_pipe_qty = substr_count($_POST['joint_txt'],'R,0,ACT');
		$query_dwg_coor = 'INSERT INTO markDwgCoor SET
							dwg_no = "'.$_POST['mapped_dwg'].'",
							rev_no = "'.$_POST['rev_dwg'].'",
							joint_info = "'.$_POST['joint_txt'].'",
							latest = "Y",
							work_id = "'.$_POST['dwg_modi'].'",
							time = "'.G5_TIME_YMDHIS.'",
							r_pipe = "'.$r_pipe_qty.'",
							dwg_state = "Marked"';	sql_query ($query_dwg_coor);	sql_query ($query_dwg_coor);
		}
	}
	
$query_view_jnt_drop = 'DROP VIEW IF EXISTS markDwgJoint'; 
sql_query ($query_view_jnt_drop);

$query_view_coor_drop = 'DROP VIEW IF EXISTS markDwgCoor'; 
sql_query ($query_view_coor_drop);

echo '<script>opener.document.location.reload();</script>';

include_once(__DIR__ . '/pcs_mark_FabISO.php');

}
else {
	$query_dwg_coor_check = 'SELECT joint_info FROM '.G5_TABLE_PREFIX.'pcs_info_iso_coor WHERE dwg_no = "'.$dwg_file.'" AND rev_no = "'.$latest_rev.'"';
	$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
	$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);
	$joint_coor_curr = $sql_dwg_coor_array['joint_info'];

	$query_dwg_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso_coor WHERE dwg_no = "'.$dwg_file.'" AND rev_no = "'.$last_rev.'"';
	$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
	$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);
	$joint_coor_prev = $sql_dwg_coor_array['joint_info'];
	
	$query_dwg_spec = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_dwgconfig';
	$sql_dwg_spec = sql_query ($query_dwg_spec);
	$sql_dwg_spec_array = sql_fetch_array ($sql_dwg_spec);

?>
<html>
<head>
<title> Joint Numbering Page </title>
</head>
<script src="../jquery/jquery-3.2.1.min.js"></script>
<script src="../pdfjs_viewer/build/pdf.js"></script>
<script src="../pdfjs_viewer/build/pdf.worker.js"></script>

<body style='margin-top: 50px;'>

<div style='position: fixed; top:0; left:0; height:45px; width:100%; border:2px solid green; font-size:15px; align:center; display:-webkit-flex; display:flex; -webkit-align-items:center; align-items: center;' >
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('0')" value='spool'> SPOOL no. : 
				<input type='text' id='spl_dwg' style='height:30px; width:300px; font-size:20px; padding:5px;'> <input type='text' id='spl_no' style='height:30px; width:50px; font-size:20px; padding:5px;'>&nbsp; &nbsp; 
			Joint no. : <input type='text' id='jnt_no' style='height:30px; width:50px; font-size:25px; padding:5px;' disabled>
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('1')" value='shop' checked>WELD(shop)
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('2')" value='field'>WELD(field)	&nbsp; &nbsp;
			Joint history : <input type='text'	id='joint_add' style='height:30px; width:50px; font-size:20px; padding:5px;'> &nbsp; &nbsp;
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('3')" value='bolt'>BOLT
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('4')" value='support'>SUPPORT
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('5')" value='thread'>THREAD
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('6')" value='coupling' disabled>COUPLING
			<input type='radio'	name='joint_type' style="height:20px; width: 25px;" onclick="chk_radio('7')" value='comment' disabled>Comment : 
			<input type='text'		id='com_info' style='height:30px; width:200px; font-size:25px; padding:5px;' disabled>			&nbsp; &nbsp;
			<input type='button'id='undo' value='Undo' style="height:30px; width:100px; font-size:16px;" onclick='undoJointmapping()'>

	</div>
	
		<div style = 'overflow:scroll; max-width:100%; height:100%; margin-right:350px; cursor:crosshair;' >
		<canvas id='cv' width='2100' height='1485' style='border:1px solid black;' onmousedown='mPush(event, this)' onmouseup='mRelease(event, this)'> </canvas>
		</div>
		<div style='width:350px; position: absolute; top: 49px; right: 0px ; height:95%;' >
			<div style='width:99%; height:20%; border:1px solid green; font-size:20px;'>
&nbsp; 			Dwg no. : <?php echo $dwg_file;?> <br>
&nbsp; 			<input type='text'	id='joint_line' style='height:35px; width:60px; font-size:25px; padding:10px;' value='<?php echo $sql_dwg_spec_array['linewidth']; ?>'> : Linewidth <br>
&nbsp; 			<input type='text'	id='joint_size' style='height:35px; width:60px; font-size:25px; padding:10px;' value='<?php echo $sql_dwg_spec_array['fontsize']; ?>'> : Size <br>

						<form name='num_smt' method="post"> 
						<input type="hidden" name="key" value="update">
						<input type='hidden' id='joint_txt' name='joint_txt'>
						<input type="hidden" name="mapped_dwg" value="<?php echo $dwg_file;?>">
						<input type="hidden" name="shop_dwg" value="<?php echo $shop_dwg;?>">
						<input type="hidden" name="rev_dwg" value="<?php echo $latest_rev;?>">
						<input type="hidden" name="dwg_modi" value="<?php echo $_POST['work_id'];?>">
						
						<p align="center">
						
							<input type="submit" id="n_finish" value="FINISH" style="height:35px; width:100px; font-size:18px; background-color:greenyellow;" >
							
						
						&nbsp;&nbsp;
						
						<?php if(!$joint_coor_curr && $joint_coor_prev) {	?>
						<input type="button" id="j_follow" name="j_follow" value="INHERIT" style="height:35px; width:100px; font-size:18px; background-color:yellow;" onclick="return j_follow_onclick()" />
						<input type='hidden' id='jnt_follow' name='jnt_follow' value='jnt_follow'>
						<?php }
							  else {?>
						
						<input type="button" id="allClear" name="allClear" value="CLEAR" style="height:35px; width:100px; font-size:18px; background-color:red;" onclick="return allClear_onclick()" />
						
						<?php } ?>
						</p>
						
						</form> 
			</div>

			<div style='overflow:scroll; width:99%; height:54%; border:1px solid red;'>
			<table id='jnt_table' style="font-size:15px; border-width: 1px 1px 0px 0px; border-style: solid solid none none;" width='330px' border="0" cellspacing="0" cellpadding="0">
				<caption> <span style="font-size: 20px;"> Joint List </span> </caption>
				<tbody>
					<tr style="height: 30px; background-color: aquamarine;">
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 80px" ><p align="center">No.</p></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 80px" ><p align="center">S/F</p></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 80px" ><p align="center">State</p></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 80px" ><p align="center">Spool</p></td>
					</tr>
					<tr style="height: 2px; background-color: aquamarine;">
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid;" ></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid;" ></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid;" ></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid;" ></td>
					</tr>
				</tbody>
			</table>

			<p>&nbsp;</p>
			</div>

			<div style='overflow:scroll; width:99%; height:25%; border:1px solid yellow;'>
				<img id='preview' src='' width='99%' alt='Image'/>
			</div>
		</div>
	

</body>

<script>

var addValue = new Array();	var jntExt = new Array();	
var Sno = 0;	var countspool = 0;	var countweld = 0;	var countbolt = 0;	var countthread = 0;	var countsupport = 0;	var jointDataInfo = '';	var txbfocus = '';	var fromtxb = ''; var jntState = 'ACT'; 
var txb_spd = document.getElementById('spl_dwg');
var txb_spn = document.getElementById('spl_no');
var txb_jnt = document.getElementById('jnt_no');
var txb_his = document.getElementById('joint_add');
$(txb_spn).focusin(function(){txb_spd.disabled = false;});

var jntxf = 0;	var jntyf = 0;	var jntxt = 0;	var jntyt = 0;	var jntMarkType = 'shop';	var dwgNo = '<?php echo $dwg_file;?>';	var revNo = '<?php echo $latest_rev;?>';	
var jntLineWidth = document.getElementById('joint_line').value*1;	var jntSize = document.getElementById('joint_size').value*1; txb_spd.disabled = true;
var jntPre = '';	var jntNo = '';	var jntAd = ''; var splAd = '';
var radio_btn = document.getElementsByName('joint_type');	txb_spd.value = dwgNo;
var tmptf = 'Tmp';	var rec_Jinfo = '<?php echo $joint_coor_curr;?>';
var canvas = document.getElementById('cv');	var context = canvas.getContext('2d');
var ini_img = new Image();	var tmpimage = new Image();




var loadingTask = pdfjsLib.getDocument('<?php echo PCS_ISO_URL;?>/' + dwgNo + '/' + dwgNo + '_' + revNo + '.pdf');

loadingTask.promise.then(function(pdfFile) {
	pdfFile.getPage(1).then(function(page) {
		var viewport = page.getViewport({ scale: 1, });
		var scale = canvas.width / viewport.width;
		var scaledViewport = page.getViewport({ scale: scale, });
		var renderContext = {
			canvasContext: context,
			viewport: scaledViewport
		};
		var rendertask = page.render(renderContext);
		rendertask.promise.then(function(){
			var renderIniDwg = ini_dwg(rec_Jinfo);
			ini_img.src = document.getElementById("cv").toDataURL("image/png");
			renderIniDwg.promise.then(function(){});
		});
	});
});



function ini_dwg(Jinfo) {
	
	var jointArray = Jinfo.split(';');

	for (var i=0; i<jointArray.length-1; i++) {

		var jointData = jointArray[i].split(',');
			Sno = jointData[0]*1;

		switch (jointData[6]){
			case 'shop' :		radio_btn[1].checked = true;		break;
			case 'field' :		radio_btn[2].checked = true;		break;
			case 'bolt' :		radio_btn[3].checked = true;		break;
			case 'support':		radio_btn[4].checked = true;		break;
			case 'thread' :		radio_btn[5].checked = true;		break;
			case 'coupling' :	radio_btn[6].checked = true;		break;
			case 'comment' :	radio_btn[7].checked = true;		document.getElementById('com_info').value = jointData[10];		break;
			case 'spool' :		radio_btn[0].checked = true;		break;
			default :			break;
		}

		if(jointData[11]*1){txb_his.value = jointData[10]};
		if(dwgNo != jointData[5]){txb_spd.value = jointData[5];txb_spn.value = jointData[10];}	else {txb_spd.value = jointData[5]; txb_spn.value = ''}
		jntNo = jointData[10];
		
		drawJoint(
			jointData[1]*1,
			jointData[2]*1,
			jointData[3]*1,
			jointData[4]*1,
			jointData[5],
			jointData[6],
			jointData[7]*1,
			jointData[8]*1,
			jointData[9],
			jointData[10],
			jointData[11],
			jointData[12]
		);

	jntState = jointData[12];
	datafill();
	addRowInTable(jointData[12],jointData[13],jointData[14],jointData[15]);
	jointDataPush();
	txb_his.value = '';

	}
	jointDataInfo = Jinfo;
	document.getElementById('joint_txt').value = Jinfo;
	minimap();
	if(Jinfo){Sno++;}
}

	
function datafill() {
	jointMarkType('incr');
	
	jntLineWidth = document.getElementById('joint_line').value*1;
	jntSize = document.getElementById('joint_size').value*1;
	jntAd = txb_his.value;
//	splAd = txb_spn.value;
	txb_jnt.value = jntNo;
}


function txb_refresh() {
	if (txb_his.value) {txb_his.value = '';}
	txb_spd.value = dwgNo;
	txb_spd.disabled = true;
	txb_spn.value = '';
}


function SF_toggle(type) {
	var radio_obj = document.getElementsByName('joint_type');

	if(type=='shop') {radio_obj[1].checked = true;}
	else if(type=='field') {radio_obj[2].checked = true;}
}


function jointMarkType(inde) {
	for (var i=0; i<radio_btn.length; i++){
		if(radio_btn[i].checked) {
			jntPre = radio_btn[i].value;
			jntMarkType = radio_btn[i].value;

			switch (jntMarkType) {
				case 'comment' : 	jntPre = 'CMT';	jntNo = document.getElementById('com_info').value;			break;
				case 'coupling' : 	jntPre = 'CPL';	jntNo = 'CPL';												break;
				case 'spool' : 		jntPre = 'SP';
									if(dwgNo!=txb_spd.value){jntNo = txb_spn.value; break;}
									else if(jntNo=='R'){ break; }
									else if(inde=='incr'){countspool++;}
									else if(inde=='decr' && countspool != 0){countspool--;};
									jntNo = countspool;		
									break;
				case 'bolt' : 		jntPre = 'B'; if(inde=='incr'){countbolt++;} 	else if(inde=='decr' && countbolt != 0){countbolt--;};			jntNo = countbolt;		break;
				case 'thread' : 	jntPre = 'T'; if(inde=='incr'){countthread++;} 	else if(inde=='decr' && countthread != 0){countthread--;};		jntNo = countthread;	break;
				case 'support' : 	jntPre = 'S'; if(inde=='incr'){countsupport++;}	else if(inde=='decr' && countsupport != 0){countsupport--;};	jntNo = countsupport;	break;
				default :			jntPre = 'WELD';  if(inde=='incr' && !jntAd){countweld++;} 	else if(inde=='decr' && countweld != 0){countweld--;};	jntNo = countweld;		break;
			}
		}
	}
}


function updateData() {
//	minimap();
	addRowInTable('ACT','','','');
	jointDataPush();
}


function minimap() {
	document.getElementById('preview').src = document.getElementById("cv").toDataURL("image/png");
	tmpimage.src = document.getElementById("cv").toDataURL("image/png");
	
}


function jointDataPush() {
	var cob_jn = '';
	
			switch (jntPre) {
				case 'WELD' : cob_jn = jntNo;		break;
				case 'B' : 		
				case 'S' : 		
				case 'T' : 	if(jntNo*1<10) {cob_jn = jntPre + '0' + jntNo;} else {cob_jn = jntPre + jntNo;}	break;				
				case 'SP' : cob_jn = jntPre + jntNo;	break;
				default :	break;
			}
	
	if(jntAd)	{addValue[Sno] = Sno+','+jntxf+','+jntyf+','+jntxt+','+jntyt+','+txb_spd.value+','+jntMarkType+','+jntLineWidth+','+jntSize+','+jntPre+','+jntAd+',1,ACT,,'+jntAd+',,;';}
	else 		{addValue[Sno] = Sno+','+jntxf+','+jntyf+','+jntxt+','+jntyt+','+txb_spd.value+','+jntMarkType+','+jntLineWidth+','+jntSize+','+jntPre+','+jntNo+',0,ACT,,'+cob_jn+',,;';}
	
	
	jointDataInfo += addValue[Sno];
	document.getElementById('joint_txt').value = jointDataInfo;
	Sno++;
}



function itemmark(jntidx,txtvalue,itm){
	var jointindex = jntidx-1
	var jointArray = jointDataInfo.split(';');
	var jointData = jointArray[jointindex].split(',');
	jointData[itm] = jointData[itm].replace(jointData[itm],txtvalue);
	
	jointDataInfo = '';
	for(var j=0; j<jointArray.length-1; j++){
		if(j==jointindex){
			for(var i=0; i<jointData.length-1; i++){
				jointDataInfo += jointData[i]+',';
			}
		}
		else {jointDataInfo += jointArray[j];}
		jointDataInfo += ';';
	}

	document.getElementById('joint_txt').value = jointDataInfo;
}


function delRowInTable() {
	var jntTable = document.getElementById('jnt_table')
	var rowIndex = jntTable.rows.length-1;
	if(rowIndex>0) jntTable.deleteRow(rowIndex);
}


function trDelete(stat,jntidx) {
	var jointindex = jntidx-1
	var jointArray = jointDataInfo.split(';');
	var jointData = jointArray[jointindex].split(',');

		if (jointData[12]=='ACT') {
			jointDataInfo = jointDataInfo.replace(jointArray[jointindex],jointArray[jointindex].replace('ACT','DEL'));
			$('#jnt_table tr:eq('+(jointindex+2)+')').css("background-color","red");
		}
		else if (jointData[12]=='DEL') {
			jointDataInfo = jointDataInfo.replace(jointArray[jointindex],jointArray[jointindex].replace('DEL','REM'));
			$('#jnt_table tr:eq('+(jointindex+2)+')').css("background-color","red");
		}
		else {
			jointDataInfo = jointDataInfo.replace(jointArray[jointindex],jointArray[jointindex].replace('REM','ACT'));
			$('#jnt_table tr:eq('+(jointindex+2)+')').css("background-color","white");
		}
	
	document.getElementById('joint_txt').value = jointDataInfo;
	dwgrefresh(jointDataInfo);
	minimap();
}

function SFChange(jntidx) { 
	var jointindex = jntidx-1
	var jointArray = jointDataInfo.split(';');
	var jointData = jointArray[jointindex].split(',');

		if (jointData[6]=='shop') {
			jointDataInfo = jointDataInfo.replace(jointArray[jointindex],jointArray[jointindex].replace('shop','field')); 
			document.getElementById('SPno_'+(jntidx-1)).value = 'N/A';
			document.getElementById('SPno_'+(jntidx-1)).disabled = true;
			$(document.getElementById('SPno_'+(jntidx-1))).css('background-color','gray')
		}
		else {
			jointDataInfo = jointDataInfo.replace(jointArray[jointindex],jointArray[jointindex].replace('field','shop'));
			document.getElementById('SPno_'+(jntidx-1)).value = '';
			document.getElementById('SPno_'+(jntidx-1)).disabled = false;
			$(document.getElementById('SPno_'+(jntidx-1))).css('background-color','red')
		}

	document.getElementById('joint_txt').value = jointDataInfo;
	dwgrefresh(jointDataInfo);
	minimap();
}

function onclickfillspl(f_idx,t_idx){
	var jointArray = jointDataInfo.split(';');
	var f_jointData = jointArray[f_idx-1].split(',');
	var t_jointData = jointArray[t_idx-1].split(',');

	if(t_jointData[6]=='spool' && t_jointData[5]!=dwgNo) {
		document.getElementById('SPno_'+(f_idx-1)).value = '<' + t_jointData[10] + '>';
		document.getElementById('SPno_'+(f_idx-1)).style = 'height:20px; width: 100%; font-size:15px; padding:5px; text-align:center';
	}
	f_jointData[15] = t_jointData[5] + '-SP0' + t_jointData[10];
	
	jointDataInfo = '';
	for(var j=0; j<jointArray.length-1; j++){
		if(j==(f_idx-1)){
			for(var i=0; i<f_jointData.length-1; i++){
				jointDataInfo += f_jointData[i]+',';
			}
		}
		else {jointDataInfo += jointArray[j];}
		jointDataInfo += ';';
	}

	document.getElementById('joint_txt').value = jointDataInfo;

}

function splmark(jntidx,txtvalue){
	var jointindex = jntidx-1
	var jointArray = jointDataInfo.split(';');
	var jointData = jointArray[jointindex].split(',');

	if(txtvalue*1>countspool || txtvalue=='') {
		document.getElementById('SPno_'+(jntidx-1)).value = '';
		document.getElementById('SPno_'+(jntidx-1)).style = 'height:20px; width: 100%; font-size:15px; padding:5px; text-align:center; background-color:red';
		jointData[15] = '';
	}
	else {
		jointData[15] = jointData[5] + '-SP0' + txtvalue;
	}
	
		jointDataInfo = '';
		for(var j=0; j<jointArray.length-1; j++){
			if(j==jointindex){
				for(var i=0; i<jointData.length-1; i++){
					jointDataInfo += jointData[i]+',';
				}
			}
			else {jointDataInfo += jointArray[j];}
			jointDataInfo += ';';
		}

	document.getElementById('joint_txt').value = jointDataInfo;
}


function addRowInTable(jntState,itm1,itm2,spl1) {
	var jntTable = document.getElementById('jnt_table')
	var rowIndex = jntTable.rows.length;
	var intxt = ''
	var jono = ''
	var tabstyle = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:20px';
	if(jntMarkType=='spool'){txbfocus = 'df';}
	
	newTr = jntTable.insertRow(rowIndex);
		newTr.align = 'center';
		if(jntState == 'DEL' || jntState == 'REM'){newTr.style = 'background-color: red';}
		newTr.idName = 'newTr'+rowIndex;
	

	newTd1 = newTr.insertCell(0);
		newTd1.style = tabstyle;
		if(jntMarkType=='spool' && (dwgNo != txb_spd.value)){jono = txb_spn.value;} else {jono = jntNo;};
		if(jntAd) {intxt = jntAd;}
		else if(jntMarkType=='comment') {intxt = jntNo;}
			else if((jntMarkType=='shop' || jntMarkType=='field') && jono*1<1000) {intxt = jono;}
				else if(jntMarkType=='spool' && jntNo*1<10) {intxt = jntPre + '0' + jono;}
					else {intxt = jntPre + jono;}
		
		if(jntMarkType=='spool' && (dwgNo != txb_spd.value)){intxt = '&lt;' + intxt + '&gt;';}
		if(jntMarkType=='spool' && jntNo=='R'){intxt = '(PIPE)';}
		newTd1.innerHTML = intxt;
		$(newTd1).on("click", newTd1, function(){
			if(txbfocus && fromtxb){onclickfillspl(fromtxb, (rowIndex-1));}
			fromtxb = '';
		});

	newTd2 = newTr.insertCell(1);
		newTd2.style = tabstyle;
		if(jntMarkType=='shop'){newTd2.innerHTML ='S'} else {newTd2.innerHTML ='F'};
		$(newTd2).on("click", newTd2, function(){
			var jtp = $(this).closest("tr").find("td:eq(4)").text();
			if( jtp != 'BT' || jtp != 'PS' || jtp != 'SPL' ) {
				SFChange(rowIndex-1);
				if($(this).closest("tr").find("td:eq(1)").text() == 'S') {$(this).closest("tr").find("td:eq(1)").text('F');}
				else {$(this).closest("tr").find("td:eq(1)").text('S');}
			}
		});

	newTd3 = newTr.insertCell(2);
		newTd3.style = tabstyle;
		$(newTd3).on('click', newTd3, function(){
			if($(this).closest("tr").find("td:eq(2)").text() == 'ACT') {
				$(this).closest("tr").find("td:eq(2)").text('DEL');
				trDelete('DEL',rowIndex-1);
			}
			else if($(this).closest("tr").find("td:eq(2)").text() == 'DEL') {
				$(this).closest("tr").find("td:eq(2)").text('REM');
				trDelete('REM',rowIndex-1);
			}
			else {
				$(this).closest("tr").find("td:eq(2)").text('ACT');
				trDelete('ACT',rowIndex-1);
			}
		});
		newTd3.innerHTML = jntState;

	newTd4 = newTr.insertCell(3);
		newTd4.style = tabstyle;
		var txbSpl = document.createElement('input');
		txbSpl.type = 'text';
		txbSpl.id = 'SPno_' + (rowIndex-2);
		txbSpl.style = 'height:20px; width: 100%; font-size:15px; padding:5px; text-align:center';
		if(spl1==''){txbSpl.value = spl1;}
		else if(spl1.search(dwgNo)<0){txbSpl.value = '<' + right(spl1,2)*1 + '>';}
		else if(txbSpl.value = right(spl1,2)*1) {txbSpl.value = right(spl1,2)*1;}

		if(jntMarkType == 'shop') {
			if(!txbSpl.value) {$(txbSpl).css('background-color','red');};
			newTd4.appendChild(txbSpl);
			$(txbSpl).focusin(function(){
				txbSpl.value = ''; $(txbSpl).css('background-color','white');
				fromtxb = rowIndex-1;
			});
			$(txbSpl).focusout(function(){
				splmark(rowIndex-1,txbSpl.value);
				if(!txbSpl.value) {$(txbSpl).css('background-color','red');};
			});
		}
		else {txbSpl.value = 'N/A'; txbSpl.disabled = true; newTd4.appendChild(txbSpl); $(txbSpl).css('background-color','gray');}
}



function undoJointmapping() {
	if(Sno!=0) {
		Sno--;
		jointDataInfo = jointDataInfo.replace(addValue[Sno],'');
		jointMarkType('decr');
		delRowInTable();
	}
	dwgrefresh(jointDataInfo);
	txb_jnt.value = jntNo;
	minimap();
}


function chk_radio(val) { 
	var radio_btn = document.getElementsByName('joint_type');
	jntMarkType = radio_btn[val].value;
	switch (jntMarkType) {
		case 'spool' : 		jntPre = 'SP';			jntNo = countspool;		break;
		case 'bolt' : 		jntPre = 'B'; 			jntNo = countbolt;		txb_jnt.value = jntNo;		break;
		case 'thread' : 	jntPre = 'T';			jntNo = countthread;	txb_jnt.value = jntNo;		break;
		case 'support' :	jntPre = 'S';			jntNo = countsupport;	txb_jnt.value = jntNo;		break;
		case 'comment' :	jntPre = 'CMT';			break;
		case 'coupling' :	jntPre = 'CPL';			break;
		default :			jntPre = ''; 			jntNo = countweld;		txb_jnt.value = jntNo;		break;
	}
} 


function drawJoint(xf,yf,xt,yt,dn,j_type,l,s,pr,no,ad,T_F) {

	if(T_F) {
		switch (T_F) {
			case 'Tmp' 	: context.strokeStyle = 'greenyellow';	context.globalAlpha = 1;	break;
			case 'DEL' 	: context.strokeStyle = 'red';			context.globalAlpha = .5;	break;
			case 'REM' 	: context.strokeStyle = 'red';			context.globalAlpha = .5;	break;
			default		: context.strokeStyle = 'blue';		context.globalAlpha = 1;	break;
		}
		var jnt_type = j_type;
		var a = xf-xt;
		var b = yf-yt;
		var th = 'infi';
		if(a>0) { if(b>=0) {th=4;} else {th=1;}	}
		if(a<0) { if(b>=0) {th=3;} else {th=2;}	}
	
		context.lineWidth = l;
		context.fillStyle = "blue";

		var jointsize = s;
	
		if(ad*1) {var jointNo = no;} 
			else if((jnt_type=='shop' || jnt_type=='field') && no*1<1000) {jointNo = no;}
//				else if(no*1<10) {jointNo = pr + '0' + no;}
					else {jointNo = pr + no;}

		
		textSize = jointsize*1.2;

		var sAngle = 0;
		var eAngle = 2 * Math.PI;
		var L = Math.sqrt(Math.pow(xf-xt,2) + Math.pow(yf-yt,2));
		var LEX = ((L-jointsize)*xt + jointsize*xf) / L;
		var LEY = ((L-jointsize)*yt + jointsize*yf) / L;

		context.beginPath();

		context.font = textSize+'px verdana';
		context.textBaseline = 'middle';
		context.textAlign = 'center';
		var texWid = context.measureText(jointNo).width/2;
//		var NoX = xt - texWid;
//		var NoY = yt + textSize/3;
		var NoX = xt;
		var NoY = yt+1;

		switch (jnt_type) {
			case 'spool' : 
				if(no=='R') {jointNo = 'RANDOM PIPE';}
				else if(no*1<10) {jointNo = dn + '_0' + no;}
					else {jointNo = dn+'_'+no;}
				var texWid = context.measureText(jointNo).width/2;
				NoX = xt;
				texWid += 10;
				switch (th) {
					case 1 : LEX = xt+texWid ; LEY = yt-jointsize ; break ;
					case 2 : LEX = xt-texWid ; LEY = yt-jointsize ; break ;
					case 3 : LEX = xt-texWid ; LEY = yt+jointsize ; break ;
					case 4 : LEX = xt+texWid ; LEY = yt+jointsize ; break ;
					default : 										break ;
				}
				context.moveTo(xt-texWid,yt-jointsize);
				context.lineTo(xt-texWid,yt+jointsize);
				context.lineTo(xt+texWid,yt+jointsize);
				context.lineTo(xt+texWid,yt-jointsize);
				context.lineTo(xt-texWid,yt-jointsize);

				if(no=='R') {
					context.moveTo(xt-texWid,yt);
					context.arc(xt-texWid+jointsize/2,yt,jointsize*1.12,-2.03,2.03,true);
					context.moveTo(xt+texWid,yt);
					context.arc(xt+texWid-jointsize/2,yt,jointsize*1.12,1.1,-1.1,true);
				}
			
				else if(jointNo.indexOf(dwgNo) == -1) {
					context.lineTo(xt-texWid-jointsize/2,yt);
					context.lineTo(xt-texWid,yt+jointsize);
					context.moveTo(xt+texWid,yt-jointsize);
					context.lineTo(xt+texWid+jointsize/2,yt);
					context.lineTo(xt+texWid,yt+jointsize);
				}
			break;

			case 'shop' : 
				context.arc(xt,yt,jointsize,sAngle,eAngle,false);
			break;

			case 'field' : 
				var ratio = jointsize/1.73;
				context.moveTo(xt-ratio,yt-jointsize);
				context.lineTo(xt-ratio*2,yt);
				context.lineTo(xt-ratio,yt+jointsize);
				context.lineTo(xt+ratio,yt+jointsize);
				context.lineTo(xt+ratio*2,yt);
				context.lineTo(xt+ratio,yt-jointsize);
				context.lineTo(xt-ratio,yt-jointsize);
			break;

			case 'bolt' : 
				var ratio = jointsize/1.73;
				context.moveTo(xt-ratio,yt-jointsize);
				context.lineTo(xt-ratio*2,yt);
				context.lineTo(xt-ratio,yt+jointsize);
				context.lineTo(xt+ratio,yt+jointsize);
				context.lineTo(xt+ratio*2,yt);
				context.lineTo(xt+ratio,yt-jointsize);
				context.lineTo(xt-ratio,yt-jointsize);
			break;

			case 'thread' : 
				var ratio = jointsize/1.73;
				context.moveTo(xt-ratio,yt-jointsize);
				context.lineTo(xt-ratio*2,yt);
				context.lineTo(xt-ratio,yt+jointsize);
				context.lineTo(xt+ratio,yt+jointsize);
				context.lineTo(xt+ratio*2,yt);
				context.lineTo(xt+ratio,yt-jointsize);
				context.lineTo(xt-ratio,yt-jointsize);
			break;

			case 'support' : 
				var ratio = jointsize/1.73;
				context.moveTo(xt-ratio,yt-jointsize);
				context.lineTo(xt-ratio*2,yt);
				context.lineTo(xt-ratio,yt+jointsize);
				context.lineTo(xt+ratio,yt+jointsize);
				context.lineTo(xt+ratio*2,yt);
				context.lineTo(xt+ratio,yt-jointsize);
				context.lineTo(xt-ratio,yt-jointsize);
			break;

			case 'coupling' : 
				var ang
				if((xf-xt) < 0) {ang = Math.atan((yt-yf)/(xt-xf)) + 90*Math.PI/180;}
				else	{ang = Math.atan((yt-yf)/(xt-xf)) - 90*Math.PI/180;}
				context.save();
				context.setTransform(1,0,0,1,0,0);
				context.translate(xf,yf);
				context.rotate(ang);
	
				context.strokeStyle = 'red';
				context.lineWidth = 3;
				context.beginPath()
				context.moveTo(-10,+0);
				context.lineTo(+10,+0);
				context.moveTo(-10,-20);
				context.lineTo(+10,-20);
				context.moveTo(-10,+10);
				context.lineTo(-10,-30);
				context.moveTo(+10,+10);
				context.lineTo(+10,-30);
				context.fillStyle = 'red';
				context.fill();
				context.closePath();
				context.stroke();
				context.restore();			
			break;
			
			case 'comment' : 
				jointNo = no;
				
			break;
			
			default :				break;
	}
	
	if(jnt_type!='coupling') {
		if(L>jointsize) {context.moveTo(xf,yf);	context.lineTo(LEX,LEY);}
		context.fillText (jointNo,NoX,NoY);
		
		if(jnt_type!='spool') {
			context.save();	
			context.globalAlpha = .3
			context.font = textSize+'px verdana';
			context.textBaseline = 'middle';
			context.textAlign = 'center';

			context.fillStyle = 'red';
			context.translate(NoX+2*jointsize*(xt-xf)/L,NoY+2*jointsize*(yt-yf)/L);
			context.rotate(getAngle(xt-xf,yt-yf));
			context.fillText('Joint Detail', 0, 0);
			context.restore();
		}
	}
	
	context.closePath();
	context.stroke();
	}
}


function mPush(evt, currentObj) {
	var mouseF = getMousePos(canvas, evt);
	jntxf = Math.round(mouseF.x);
	jntyf = Math.round(mouseF.y);
	
	var jp = jntPre;
	var jn = jntNo;
	var ja = jntAd;

	canvas.addEventListener('mousedown', function(evt) {tmptf = 'Tmp';}, false);

	canvas.addEventListener('mousemove', function(evt) {
	if(txb_his.value){ja = 1; jn = txb_his.value;}
	if(document.getElementById('com_info').value && jntPre == 'CMT'){jn = document.getElementById('com_info').value;}
		if(event.button=='0'){
			var mousePos = getMousePos(canvas, evt);
			context.drawImage(tmpimage,0,0,canvas.width,canvas.height);
			if(dwgNo != txb_spd.value)	{jntNo = txb_spn.value;};
			if(txb_spn.value.toUpperCase()=='R')		{jntNo = 'R';};
			drawJoint(jntxf,jntyf,mousePos.x,mousePos.y,txb_spd.value,jntMarkType,jntLineWidth,jntSize,jp,jn,ja,tmptf);
		}
	}, false);

	canvas.addEventListener('mouseup', function(evt) {tmptf = '';}, false);
}

function mRelease(evt, currentObj) {
	if(event.button=='0'){
		datafill();
		var mouseT = getMousePos(canvas, evt);
		jntxt = Math.round(mouseT.x);
		jntyt = Math.round(mouseT.y);
		updateData();
		dwgrefresh(jointDataInfo);
		txb_refresh();
		minimap();
		jntNo++;
	}
}

function dwgrefresh(jointText){
	context.drawImage(ini_img,0,0,canvas.width,canvas.height);
	
	var jointArray = jointText.split(';');

	for (var i=0; i<jointArray.length; i++) {
		var jointData = jointArray[i].split(',');
		drawJoint(jointData[1]*1,jointData[2]*1,jointData[3]*1,jointData[4]*1,jointData[5],	jointData[6],jointData[7]*1,jointData[8]*1,jointData[9],jointData[10],jointData[11],jointData[12]);
	}
}

function getMousePos(canvas, evt) {
	var rect = canvas.getBoundingClientRect();
	return {x: evt.clientX - rect.left,	y: evt.clientY - rect.top};
}

function j_follow_onclick(){
	document.getElementById('joint_txt').value = '<?php echo $joint_coor_prev; ?>';
	document.num_smt.submit();
}

function allClear_onclick(){
	document.getElementById('joint_txt').value = 'clear';
	$('#n_finish').trigger('click');
}

document.oncontextmenu = function (){
	if(jntMarkType == 'shop') {jntMarkType = 'field'}
	else {jntMarkType = 'shop'}
	SF_toggle(jntMarkType);
	return false;
}

function right(s,c){return s.substr(-c);}

function getAngle(X,Y){
	var r = Math.atan2(X,Y);
	if (Y < 0) {r += Math.PI;}
	return -r;
}

</script>
</html>
<?php
}
?>