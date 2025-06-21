<?php

$query_dwg = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_iso WHERE dwg_no = "' . $view['wr_subject'] . '"';
$sql_dwg = sql_query($query_dwg);
$sql_dwg_arr = sql_fetch_array($sql_dwg);

$query_pkg1 = 'SELECT wr_id FROM ' . G5_TABLE_PREFIX . 'write_package WHERE wr_subject = "' . $sql_dwg_arr['pkg_no1'] . '"';
$sql_pkg1 = sql_query($query_pkg1);
$sql_pkg1_arr = sql_fetch_array($sql_pkg1);
$query_pkg2 = 'SELECT wr_id FROM ' . G5_TABLE_PREFIX . 'write_package WHERE wr_subject = "' . $sql_dwg_arr['pkg_no2'] . '"';
$sql_pkg2 = sql_query($query_pkg2);
$sql_pkg2_arr = sql_fetch_array($sql_pkg2);

spl_ins_qry($_POST['field_id'], $_POST['btn_stat']);

if ($_POST['folder'] || $_POST['ph']) {
	include_once(PCS_LIB . '/pcs_photo.php');
} else if ($_POST['j_mode']) {
	include_once(PCS_LIB . '/pcs_info_jnt_edit.php');
} else {

	$con_dwg_array = explode(";", $sql_dwg_arr['con_dwg']);
	$con_EQ_array = explode(";", $sql_dwg_arr['con_eq']);
	$inc_tp_array = explode(";", $sql_dwg_arr['inc_tp']);


	if (!G5_IS_MOBILE) { /////////// PC 버전 시작

		if ($_POST['dwg']) {
			$query_pkg = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_iso SET pkg_no1 = "' . $_POST['pkg1'] . '" ,pkg_no2 = "' . $_POST['pkg2'] . '" WHERE dwg_no = "' . $_POST['dwg'] . '"';
			sql_query($query_pkg);
		}



		if ($_POST['num_app']) {
			$query_approve = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_iso_coor SET dwg_state = "' . $_POST['num_app'] . '" WHERE dwg_no = "' . $view['wr_subject'] . '" AND rev_no = "' . $sql_dwg_arr['rev_no'] . '"';
			sql_query($query_approve);
		}

		$query_dwg_coor_check = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_iso_coor WHERE dwg_no = "' . $view['wr_subject'] . '" AND rev_no = "' . $sql_dwg_arr['rev_no'] . '"';
		$sql_dwg_coor_check = sql_query($query_dwg_coor_check);
		$sql_dwg_coor_array = sql_fetch_array($sql_dwg_coor_check);

		$spl_array = explode(';', $sql_dwg_coor_array['joint_info']);
?>
		<p style="text-align:center; font-size:50px;" onclick="dom_hide('spec');">SPECIFICATION</p>
		<table class="main" id="spec">
<?php var_dump($sql_dwg_coor_array['dwg_state'])?>
			<tbody>
				<td class="main_td td_sub" style="height:80px;" colspan="6"> <a href='javascript:document.shopdwg.submit()'> <b> DRAWING INFORMATION </b> </a> </td>
				<?php viewPDF('shopdwg', 'shop', $view['wr_subject'], $sql_dwg_arr['shop_dwg']);  ?>
				</tr>
				<tr>
					<td class="main_td td_sub" style="height:80px;"> DRAWING NO.</td>

					<td class="main_td" colspan="2">
<!--                        remove condition to roll back -->
<!--						--><?php //if ($sql_dwg_coor_array['dwg_state'] != 'Approved' && $member['mb_3'] > 0) { ?>
							<b><a href='javascript:document.numbering_form.submit()'> <?php echo $view['wr_subject']; ?> </a></b>
							<form name='numbering_form' action="<?php echo PCS_CORE_URL; ?>/pcs_mark_joint_pdf.php" method="post" target="result" onSubmit="return doSumbit()">
								<input type="hidden" name="fn" value="<?php echo $view['wr_subject']; ?>">
								<input type="hidden" name="sd" value="<?php echo $sql_dwg_arr['shop_dwg']; ?>">
								<input type="hidden" name="rev" value="<?php echo $sql_dwg_arr['rev_his']; ?>">
								<input type="hidden" name="work_id" value="<?php echo $member['mb_nick']; ?>">
							</form>
<!--						--><?php	//} else {
//							echo $view['wr_subject'];
//						}
//						?>
					</td>


					<td class="main_td" style='background-color: #DCFF99;'>
						<a href='javascript:document.submit_for_marked<?php echo $sql_dwg_arr['rev_no']; ?>.submit()'><b> Rev_<?php echo $sql_dwg_arr['rev_no']; ?> </b></a>
						<?php viewPDF('submit_for_marked' . $sql_dwg_arr['rev_no'], 'fab', $view['wr_subject'], $sql_dwg_arr['rev_no']); ?>
					</td>

					<td class="main_td">
						<?php
						$rev_array = explode(";", $sql_dwg_arr['rev_his']);
						$r_number = count($rev_array) - 2;
						for ($j = 0; $j < $r_number; $j++) {	?>
							<a href='javascript:document.submit_for_old<?php echo $j; ?>.submit()'> R_<?php echo $rev_array[$j]; ?> </a>
							<?php viewPDF('submit_for_old' . $j, 'fab', $view['wr_subject'], $rev_array[$j]); ?>
						<?php	} ?>
					</td>

					<td class="main_td">
						<?php
						if ($sql_dwg_coor_array['dwg_state'] == 'Approved') {
							$txt_color = 'green';
							$txt_value = 'Marked';
						} else if ($sql_dwg_coor_array['dwg_state'] == 'Marked') {
							$txt_color = 'blue';
							$txt_value = 'Approved';
						}
						if ($member['mb_3'] > 2) {
							echo '
		<a href = "javascript:submit_mark.submit()" ><font color = "' . $txt_color . '"><b>' . $sql_dwg_coor_array['dwg_state'] . '</b></font></a>
		<form name="submit_mark" method="post" onSubmit="return doSumbit()">
		<input type="hidden" name="num_app" value="' . $txt_value . '">
		</form>';
						} else {
							echo '<font color = "' . $txt_color . '"><b>' . $sql_dwg_coor_array['dwg_state'] . '</b></font>';
						}
						?>
					</td>

				</tr>

				<tr>
					<td class="main_td td_sub" style="height:80px;"> INCLUDED PKG. </td>

					<td class="main_td" colspan="2">
						<?php
						if ($member['mb_10'] > 4 && !$_POST['dwg']) {
							echo '<form name="submit_pkg" method="post" onSubmit="return doSumbit()">';
							echo '<input type="hidden" name="dwg" value="' . $view['wr_subject'] . '">';
							echo '<input type="text" name="pkg1" style="padding:0px 0px 0px 15px; text-align:center;width:90%;height:50px;font-size:30px;border:none;border-right:0px; border-top:0px; boder-left:0px; boder-bottom:0px;" value="' . $sql_dwg_arr['pkg_no1'] . '">';
						} else {
							echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=package&wr_id=' . $sql_pkg1_arr['wr_id'] . '><b>' . $sql_dwg_arr['pkg_no1'] . '</a>';
						}
						?>
					</td>

					<td class="main_td" colspan="2">
						<?php
						if ($member['mb_10'] > 4 && !$_POST['dwg']) {
							echo '<input type="text" name="pkg2" style="padding:0px 0px 0px 15px; text-align:center;width:90%;height:50px;font-size:30px;border:none;border-right:0px; border-top:0px; boder-left:0px; boder-bottom:0px;" value="' . $sql_dwg_arr['pkg_no2'] . '">';
							echo '</form>';
						} else {
							echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=package&wr_id=' . $sql_pkg2_arr['wr_id'] . '>' . $sql_dwg_arr['pkg_no2'] . '</a>';
						}
						?>

					</td>

					<td class="main_td">
						<?php
						if ($member['mb_10'] > 4 && !$_POST['dwg']) {
							echo '<a href = "javascript:document.submit_pkg.submit()"> PKG-marking </a>';
						}
						?>
					</td>
				</tr>

				<tr>
					<td class="main_td td_sub"> LEVEL / UNIT </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['ag_ug'] . ' / ' . $sql_dwg_arr['unit']; ?> </td>
					<td class="main_td td_sub"> NPS </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['line_size']; ?> </td>
					<td class="main_td td_sub"> MATERIAL </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['material']; ?> </td>
				</tr>

				<tr>
					<td class="main_td td_sub"> PMI / PWHT </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['pmi'] . ' / ' . $sql_dwg_arr['pwht']; ?> </td>
					<td class="main_td td_sub"> TEST TYPE </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['test_type']; ?> </td>
					<td class="main_td td_sub"> INSULATION </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['line_insul']; ?> </td>
				</tr>

				<tr>
					<td class="main_td td_sub"> NDE </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['nde_rate'] . ' %'; ?> </td>
					<td class="main_td td_sub"> PRESSURE </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['pressure']; ?> </td>
					<td class="main_td td_sub"> PAINT CODE </td>
					<td class="main_td"> <?php echo $sql_dwg_arr['paint_code']; ?> </td>
				</tr>

				<tr>
					<?php
					if ($sql_dwg_arr['con_dwg']) {
						echo '<td class="main_td" colspan=6 style="background-color: #F6D8CE; height:50px;"><b>CONTINUED DRAWING</td></tr>';

						$j = 0;
						for ($i = 0; $i < count($con_dwg_array) - 1; $i++) {
							$query_con_dwg = "SELECT wr_id, wr_1  FROM " . G5_TABLE_PREFIX . "write_iso WHERE wr_subject = '" . $con_dwg_array[$i] . "'";
							$sql_con_dwg = sql_query($query_con_dwg);
							$sql_con_dwg_arr = sql_fetch_array($sql_con_dwg);

							$query_con_dwg_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_iso WHERE dwg_no = '" . $con_dwg_array[$i] . "'";
							$sql_con_dwg_info = sql_query($query_con_dwg_info);
							$sql_con_dwg_info_arr = sql_fetch_array($sql_con_dwg_info);

					?>

							<td class="jnt_td" style='height:80px;font-size:15px;'>
								<?php
								if ($con_dwg_array[$i]) {
									$j++;
									$con_no = $i + 1;
									if ($sql_con_dwg_arr['wr_id']) {
										echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=iso&wr_id=' . $sql_con_dwg_arr['wr_id'] . '> <b>' . $con_no . '. ' . $con_dwg_array[$i] . '</b></a></br>';
										echo $sql_con_dwg_info_arr['line_size'] . ' - ' . $sql_con_dwg_info_arr['test_type'] . ' - ' . $sql_con_dwg_info_arr['pressure'] . '</br>';
										echo "<a href = 'javascript:document.submit_for" . $i . $j . ".submit()'> <b> View ISO Drawing </b> </a>";
									} else {
										echo '<mark>' . $con_no . '. ' . $con_dwg_array[$i] . '</mark>';
									}

									viewPDF('submit_for' . $i . $j, 'fab', $con_dwg_array[$i], $sql_con_dwg_info_arr['rev_no']);
								}
								?>

							</td>
							<?php
							if ($j % 6 == 0) {
								echo '</tr><tr>';
							}
						}
						if ($j % 6) {
							for ($k = 0; $k < 6 - ($j % 6); $k++) { ?>
								<td class="main_td"></td>
					<?php
							}
						}
					}
					?>
				</tr>

				<tr>
					<?php
					if ($sql_dwg_arr['inc_tp']) {
						echo '<td class="main_td" colspan=6 style="background-color: #BCF5A9; height:50px;"><b>INCLUDED TP</td></tr>';

						$j = 0;
						for ($i = 0; $i < count($inc_tp_array) - 1; $i++) {
							$query_inc_tp = "SELECT * FROM " . G5_TABLE_PREFIX . "write_tp WHERE wr_subject = '" . $inc_tp_array[$i] . "'";
							$sql_inc_tp = sql_query($query_inc_tp);
							$sql_inc_tp_arr = sql_fetch_array($sql_inc_tp);

							$query_inc_tp_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_iso WHERE dwg_no = '" . $inc_tp_array[$i] . "'";
							$sql_inc_tp_info = sql_query($query_inc_tp_info);
							$sql_inc_tp_info_arr = sql_fetch_array($sql_inc_tp_info);

					?>

							<td class="jnt_td" style='height:80px;font-size:15px;'>
								<?php
								if ($inc_tp_array[$i]) {
									$j++;
									$con_no = $i + 1;
									if ($sql_inc_tp_arr['wr_id']) {
										echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=tp&wr_id=' . $sql_inc_tp_arr['wr_id'] . '> <b>' . $con_no . '. ' . $inc_tp_array[$i] . '</b></a><br>';
									} else {
										echo '<mark>' . $con_no . '. ' . $inc_tp_array[$i] . '</mark>';
									}
								}
								?>

							</td>
							<?php
							if ($j % 6 == 0) {
								echo '</tr><tr>';
							}
						}
						if ($j % 6) {
							for ($k = 0; $k < 6 - ($j % 6); $k++) { ?>
								<td class="main_td"></td>
					<?php
							}
						}
					}
					?>
				</tr>

				<?php //add_tr($con_EQ_array, '#CEF6F5', 'CONNECTED EQUIPMENT', 'equipment'); 
				?>



				<tr>
					<?php

					if (strstr($sql_dwg_coor_array['joint_info'], 'spool')) {
						echo '<td class="main_td" colspan=6 style="background-color: #BCF5A9; height:80px;"><b>INCLUDED SPOOL</td></tr>';
					}
					$j = 0;
					for ($i = 0; $i < count($spl_array) - 1; $i++) {
						$jnt_each_arr = explode(",", $spl_array[$i]);
						if ($jnt_each_arr[6] == 'spool' && $jnt_each_arr[10] != 'R') {
							$j++;
							$query_spl = "SELECT wr_id FROM " . G5_TABLE_PREFIX . "write_spool WHERE wr_subject = '" . $jnt_each_arr[5] . '-SP0' . $jnt_each_arr[10] . "'";
							$sql_spl = sql_query($query_spl);
							$sql_spl_arr = sql_fetch_array($sql_spl);
					?>

							<td class="main_td" style='height:80px;'>
								<a href=<?php echo G5_BBS_URL . '/board.php?bo_table=spool&wr_id=' . $sql_spl_arr['wr_id']; ?> target='_self'>
									<?php
									//	echo $jnt_each_arr[5].'-SP0'.$jnt_each_arr[10];
									if ($jnt_each_arr[5] == $view['wr_subject']) {
										echo 'SP-0' . $jnt_each_arr[10];
									} else {
										echo '<mark><b>&lt;SP-0' . $jnt_each_arr[10] . '&gt;<b></mark>';
									}
									?>
								</a>
							</td>
							<?php
							if ($j % 6 == 0) {
								echo '</tr><tr>';
							}
						}
					}
					if ($j % 6) {
						for ($k = 0; $k < 6 - ($j % 6); $k++) { ?>
							<td class="main_td"></td>
					<?php
						}
					}

					?>
				</tr>

			</tbody>
		</table>


		<p>&nbsp;</p>
		<p>&nbsp;</p>

		<p style="text-align:center; font-size:50px;" onclick="dom_hide('joint');">JOINT STATUS</p>
		<caption>
			<?php
			if ($sql_dwg_coor_array['dwg_state'] == 'Marked' || $member['mb_3'] > 2) {
				echo '
		<a href = "javascript:jEdit.submit()" ><p style="text-align:right; font-size:30px;"> Joint information match up </p></a>
		<form name="jEdit" method="post" onSubmit="return doSumbit()">
		<input type="hidden" name="j_mode" value="edit">
		<input type="hidden" name="m_dwg" value="' . $view['wr_subject'] . '">
		<input type="hidden" name="c_dwg" value="' . $sql_dwg_arr['con_dwg'] . '">
		</form>
		';
			}
			?>
		</caption>
		<table class="main" id="joint">
			<tbody>
				<tr>
					<td class="jnt_td jnt_th" style="width:100px"> J.No </td>
					<td class="jnt_td jnt_th" style="width:100px"> Company </td>
					<td class="jnt_td jnt_th" style="width:100px"> Type </td>
					<td class="jnt_td jnt_th" style="width:100px"> S / F </td>
					<td class="jnt_td jnt_th" style="width:100px"> NPS </td>
					<td class="jnt_td jnt_th" style="width:200px"> Photo 1 </td>
					<td class="jnt_td jnt_th" style="width:200px"> Photo 2 </td>
					<td class="jnt_td jnt_th" style="width:200px"> Fit-up </td>
					<td class="jnt_td jnt_th" style="width:200px"> Welding </td>
					<td class="jnt_td jnt_th" style="width:200px"> PMI </td>
					<td class="jnt_td jnt_th" style="width:200px"> PWHT </td>
					<td class="jnt_td jnt_th" style="width:200px"> NDE </td>
					<td class="jnt_td jnt_th" style="width:100px"> STATE </td>
				</tr>

				<?php

				$field_query = 'DESCRIBE ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc';
				$field_name_sbc = field_name_array($field_query);

				$field_query_con = 'DESCRIBE ' . G5_TABLE_PREFIX . 'pcs_info_joint';
				$field_name_con = field_name_array($field_query_con);

				$idx = 0;
				$temptbl = GenerateString(15);

				$query_view_dwg_create =
					'CREATE VIEW ' . $temptbl . ' AS SELECT *
		FROM ' . G5_TABLE_PREFIX . 'pcs_info_joint
		WHERE dwg_no = "' . $view['wr_subject'] . '" ORDER BY j_no';
				sql_query($query_view_dwg_create);

				$query_sbc = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc WHERE dwg_no = "' . $view['wr_subject'] . '" ORDER BY j_no';
				$sql_ref_sbc = sql_query($query_sbc, true);


				while ($sql_ref_sbc_arr = sql_fetch_array($sql_ref_sbc)) {
					if ($sql_ref_sbc_arr['j_stat'] != 'REM' && $sql_ref_sbc_arr['j_type'] != 'SPL') {
						$idx++;

						$query_con = 'SELECT * FROM ' . $temptbl . ' WHERE j_key = "' . $sql_ref_sbc_arr['j_key'] . '"';
						$sql_ref_con = sql_query($query_con, true);
						$sql_ref_con_arr = sql_fetch_array($sql_ref_con);

						for ($i = 0; $i < count($field_name_sbc); $i++) {
							if (substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 4, 1) == '-' && substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 7, 1) == '-') {
								if ($sql_ref_sbc_arr[$field_name_sbc[$i]] == '0000-00-00 00:00:00') {
									$sql_ref_sbc_arr[$field_name_sbc[$i]] = false;
								} else {
									$sql_ref_sbc_arr[$field_name_sbc[$i]] = substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 0, 10);
								}
							}
						}
						if ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
							$PCS_DEL = "<del><font color = red>";
						} else {
							$PCS_DEL = '';
						}

						for ($i = 0; $i < count($field_name_con); $i++) {
							if (substr($sql_ref_con_arr[$field_name_con[$i]], 4, 1) == '-' && substr($sql_ref_con_arr[$field_name_con[$i]], 7, 1) == '-') {
								if ($sql_ref_con_arr[$field_name_con[$i]] == '0000-00-00') {
									$sql_ref_con_arr[$field_name_con[$i]] = false;
								}
							}
						}
						//	echo $query_sbc;
				?>

						<tr id='tr<?php echo $idx; ?>'>
							<td class="jnt_td" rowspan="2">
								<?php
								echo $PCS_DEL;
								echo z_rem_jno($sql_ref_sbc_arr['j_no']);
								?>
							</td>
							<td class="td_upper"><?php echo $PCS_DEL; ?>PCS</td>
							<td class="td_upper"> <?php echo $PCS_DEL;
													echo $sql_ref_sbc_arr['j_type']; ?></td>
							<td class="td_upper"> <?php echo $PCS_DEL;
													echo $sql_ref_sbc_arr['s_f']; ?></td>
							<td class="td_upper"> <?php echo $PCS_DEL;
													echo $sql_ref_sbc_arr['j_size']; ?></td>

							<td class="td_upper" rowspan="2">
								<?php
								echo $PCS_DEL;
								photo_thumb('photo_1', $sql_ref_sbc_arr['photo_1'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
								if (!$sql_ref_sbc_arr['photo_1'] && $member['mb_2'] > 1) {
									photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
								} else if ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_1_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_1_tm']) || $member['mb_2'] == 3)) {
									photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
								}
								echo '<br>' . $sql_ref_sbc_arr['item_1_type'];
								rep_view('heat_1' . $idx, 'report/heat', '',	$sql_ref_con_arr['heat_1']);
								?>

							</td>

							<td class="td_upper" rowspan="2">
								<?php
								echo $PCS_DEL;
								photo_thumb('photo_2', $sql_ref_sbc_arr['photo_2'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
								if (!$sql_ref_sbc_arr['photo_2'] && $member['mb_2'] > 1) {
									photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
								} else if ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_2_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_2_tm']) || $member['mb_2'] == 3)) {
									photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
								}
								echo '<br>' . $sql_ref_sbc_arr['item_2_type'];
								rep_view('heat_2' . $idx, 'report/heat', '',	$sql_ref_con_arr['heat_2']);
								?>
							</td>

							<td class="td_upper">
								<?php
								echo $PCS_DEL;
								if ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
									echo $sql_ref_sbc_arr['pcs_fitup_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
								} else {
									if ($member['mb_4'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_fitup_rlt_by'] && $sql_ref_sbc_arr['pcs_fitup_rlt_by'] != '') {
										echo $sql_ref_sbc_arr['pcs_fitup_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
									} else {
										insp_fitup($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_fitup_req_date'], $sql_ref_sbc_arr['pcs_fitup_rlt'], $sql_ref_sbc_arr['pcs_fitup_rlt_date'], $sql_ref_sbc_arr['pcs_vi_req_date']);
									}
								}
								?>
							</td>

							<td class="td_upper">
								<?php
								echo $PCS_DEL;

								if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
									echo 'N/A';
								} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
									echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
								} elseif ($sql_ref_sbc_arr['pcs_fitup_rlt'] == 'Accept') {
									if ($member['mb_4'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_vi_rlt_by'] && $sql_ref_sbc_arr['pcs_vi_rlt_by'] != '') {
										echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
									} else {
										insp_vi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_vi_req_date'], $sql_ref_sbc_arr['pcs_vi_rlt'], $sql_ref_sbc_arr['pcs_vi_rlt_date'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
									}
								} else {
									echo 'Not yet<br>Fit-up';
								}
								?>
							</td>

							<td class="td_upper">
								<?php
								echo $PCS_DEL;

								if ($sql_ref_sbc_arr['pmi_yn'] == 'YES') {
									if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
										echo 'Not yet<br>VI accepted';
									} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
										echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
									} else {
										if ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_pmi_rlt_by'] && $sql_ref_sbc_arr['pcs_pmi_rlt_by'] != '') {
											echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
										} else {
											insp_pmi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_pmi_rlt'], $sql_ref_sbc_arr['pcs_pmi_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
										}
									}
								} else {
									echo 'N/A';
								}
								?>
							</td>

							<td class="td_upper">

								<?php
								echo $PCS_DEL;

								if ($sql_ref_sbc_arr['pwht_yn'] == 'YES') {
									if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
										echo 'Not yet<br>VI accepted';
									} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
										echo $sql_ref_sbc_arr['pcs_pwht_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pwht_rlt'];
									} else {
										if ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_pwht_rlt_by'] && $sql_ref_sbc_arr['pcs_pwht_rlt_by'] != '') {
											echo $sql_ref_sbc_arr['pcs_pwht_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pwht_rlt'];
										} else {
											insp_pwht($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pwht_rlt'], $sql_ref_sbc_arr['pcs_pwht_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
										}
									}
								} else {
									echo 'N/A';
								}
								?>
							</td>

							<td class="td_upper">
								<?php
								echo $PCS_DEL;
								if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
									echo 'N/A';
								} elseif ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
									echo 'Not yet<br>VI accepted';
								} elseif ($sql_ref_sbc_arr['pwht_yn'] == 'YES' && $sql_ref_sbc_arr['pcs_pwht_rlt'] != 'Accept') {
									echo 'Not yet<br>PWHT accepted';
								} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
									echo $sql_ref_sbc_arr['pcs_nde_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_nde_rlt'];
								} else {
									if ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_nde_rlt_by'] && $sql_ref_sbc_arr['pcs_nde_rlt_by'] != '') {
										echo $sql_ref_sbc_arr['pcs_nde_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_nde_rlt'];
									} else {
										insp_nde($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_6'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_nde_type'], $sql_ref_sbc_arr['pcs_nde_req_date'], $sql_ref_sbc_arr['pcs_nde_rlt']);
									}
								}

								?>
							</td>

							<td class="td_upper"><?php echo $PCS_DEL . $sql_ref_sbc_arr['j_stat']; ?></td>
						</tr>

						<tr>
							<td class="td_lower" style="border-style:dashed solid solid solid;">CON</td>
							<td class="td_lower"><?php echo $sql_ref_con_arr['j_type']; ?></td>
							<td class="td_lower"><?php echo $sql_ref_con_arr['s_f']; ?></td>
							<td class="td_lower"><?php echo $sql_ref_con_arr['nps']; ?></td>
							<td class="td_lower"><?php echo $sql_ref_con_arr['ft_date']; ?></td>
							<td class="td_lower"><?php rep_view('vi' . $idx, 'report/vi',		$sql_ref_con_arr['vi_date'],	substr($sql_ref_con_arr['vi_rep'], -11)); ?></td>
							<td class="td_lower"><?php rep_view('pmi' . $idx, 'report/pmi',	$sql_ref_con_arr['pmi_date'],	$sql_ref_con_arr['pmi_rep']); ?></td>
							<td class="td_lower"><?php rep_view('pwht' . $idx, 'report/pwht',	$sql_ref_con_arr['pwht_date'],	$sql_ref_con_arr['pwht_rep']); ?></td>
							<td class="td_lower"><?php rep_view('nde' . $idx, 'report/nde',	$sql_ref_con_arr['nde_date'],	$sql_ref_con_arr['nde_rep']); ?></td>
							<td class="td_lower"><?php echo $sql_ref_con_arr['j_stat']; ?>
						</tr>
				<?php
					}
				}
				$query_view_dwg_drop = 'DROP VIEW IF EXISTS ' . $temptbl;
				sql_query($query_view_dwg_drop);
			} else {  /////////////////////////////////////////////////////////////////////////////////  Mobile 버전 시작
				?>



				<table class="main">
					<caption>SPECIFICATION</caption>
					<tbody>
						<tr>
							<td class="main_td td_sub" style="background-color: orange;font-size:18px;" colspan="2"><a href='javascript:document.submit_for_marked.submit()'><?php echo $view['wr_subject']; ?></a>
								<?php viewPDF('submit_for_marked', 'fab', $view['wr_subject'], $sql_dwg_arr['rev_no']); ?>
							</td>
						</tr>
						<!--
<tr>
<td class="main_td td_sub"> MATERIAL </td>
<td class="main_td"> <?php echo $sql_dwg_arr['material']; ?> </td>
</tr>
<tr>
<td class="main_td td_sub"> PMI / PWHT</td>
<td class="main_td"> <?php echo $sql_dwg_arr['pmi'] . ' / ' . $sql_dwg_arr['pwht']; ?> </td>
</tr>
<tr>
<td class="main_td td_sub"> NDE / PAINT</td>
<td class="main_td"> <?php echo $sql_dwg_arr['nde_rate'] . '% / ' . $sql_dwg_arr['paint_code']; ?> </td>
</tr>
<tr>
<?php if ($sql_dwg_arr['pkg_no1']) {
					echo '<td class="main_td" colspan="2"><a href="' . G5_BBS_URL . '/board.php?bo_table=package&wr_id=' . $sql_pkg1_arr['wr_id'] . '" target="_self">' . $sql_dwg_arr['pkg_no1'] . '</a></td>';
				} ?> 
</tr>
<tr>
<?php if ($sql_dwg_arr['pkg_no2']) {
					echo '<td class="main_td" colspan="2"><a href="' . G5_BBS_URL . '/board.php?bo_table=package&wr_id=' . $sql_pkg2_arr['wr_id'] . '" target="_self">' . $sql_dwg_arr['pkg_no2'] . '</a></td>';
				} ?> 
</tr>
-->



						<?php
						if ($sql_dwg_arr['inc_tp']) {
							echo '<tr><td class="main_td" colspan=6 style="background-color: #BCF5A9; height:50px;"><b>INCLUDED TP</td></tr>';

							$j = 0;
							for ($i = 0; $i < count($inc_tp_array) - 1; $i++) {
								$query_inc_tp = "SELECT * FROM " . G5_TABLE_PREFIX . "write_tp WHERE wr_subject = '" . $inc_tp_array[$i] . "'";
								$sql_inc_tp = sql_query($query_inc_tp);
								$sql_inc_tp_arr = sql_fetch_array($sql_inc_tp);

								$query_inc_tp_info = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_iso WHERE dwg_no = '" . $inc_tp_array[$i] . "'";
								$sql_inc_tp_info = sql_query($query_inc_tp_info);
								$sql_inc_tp_info_arr = sql_fetch_array($sql_inc_tp_info);

						?>

								<tr>
									<td class="jnt_td" style='height:80px;font-size:15px;'>
										<?php
										if ($inc_tp_array[$i]) {
											$j++;
											$con_no = $i + 1;
											if ($sql_inc_tp_arr['wr_id']) {
												echo '<a href=' . G5_BBS_URL . '/board.php?bo_table=tp&wr_id=' . $sql_inc_tp_arr['wr_id'] . '> <b>' . $con_no . '. ' . $inc_tp_array[$i] . '</b></a><br>';
											} else {
												echo '<mark>' . $con_no . '. ' . $inc_tp_array[$i] . '</mark>';
											}
										}
										?>

									</td>
								<tr>
							<?php

							}
						}
							?>



					</tbody>
				</table>

				<p>&nbsp;
				<p>&nbsp;
				<p>&nbsp;
				<table class='main'>
					<caption> JOINT STATUS </caption>
					<tbody>

						<?php
						$field_query = 'DESCRIBE ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc';
						$field_name_sbc = field_name_array($field_query);

						$idx = 0;
						$ymd = substr(G5_TIME_YMD, 2, 8);

						$query_sbc = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_jnt_sbc WHERE dwg_no = "' . $view['wr_subject'] . '" ORDER BY j_no';
						$sql_ref_sbc = sql_query($query_sbc, true);

						while ($sql_ref_sbc_arr = sql_fetch_array($sql_ref_sbc)) {
							$idx++;

							for ($i = 0; $i < count($field_name_sbc); $i++) {
								if (substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 4, 1) == '-' && substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 7, 1) == '-') {
									if ($sql_ref_sbc_arr[$field_name_sbc[$i]] == '0000-00-00 00:00:00') {
										$sql_ref_sbc_arr[$field_name_sbc[$i]] = false;
									} else {
										$sql_ref_sbc_arr[$field_name_sbc[$i]] = substr($sql_ref_sbc_arr[$field_name_sbc[$i]], 0, 10);
									}
								}
							}
							if ($sql_ref_sbc_arr['j_stat'] != 'REM' && $sql_ref_sbc_arr['j_type'] != 'SPL') {

						?>


								<tr>
									<td class='jnt_td jnt_th' style="width: 25%;">Joint no.<br>
										<font size="5">
											<?php echo z_rem_jno($sql_ref_sbc_arr['j_no']); ?>
										</font>
									</td>
									<td class='jnt_td jnt_th' style="width: 75%;" colspan="3">
										<a href='javascript:document.smt_<?php echo $idx; ?>.submit()'><b> <?php echo $view['wr_subject']; ?> </b></a>
										<?php viewPDF('smt_' . $idx, 'fab', $view['wr_subject'], $sql_dwg_arr['rev_no']); ?>
									</td>
								</tr>
								<tr>
									<td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Photo 1 </td>
									<td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Photo 2 </td>
								</tr>
								<tr>
									<td class='jnt_td' style="width: 50%;" colspan="2">
										<?php
										echo $PCS_DEL;
										photo_thumb('photo_1', $sql_ref_sbc_arr['photo_1'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
										if (!$sql_ref_sbc_arr['photo_1'] && $member['mb_2'] > 1) {
											photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
										} else if ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_1_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_1_tm']) || $member['mb_2'] == 3)) {
											photo_up('photo_1', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_1']);
										}
										echo '<br>' . $sql_ref_sbc_arr['item_1_type'];
										rep_view('heat_1' . $idx, 'report/heat', '',	$sql_ref_con_arr['heat_1']);
										?>
									</td>
									<td class='jnt_td' style="width: 50%;" colspan="2">
										<?php
										echo $PCS_DEL;
										photo_thumb('photo_2', $sql_ref_sbc_arr['photo_2'], $sql_ref_sbc_arr['j_no'], 120, $sql_ref_sbc_arr['dwg_no']);
										if (!$sql_ref_sbc_arr['photo_2'] && $member['mb_2'] > 1) {
											photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
										} else if ($sql_ref_sbc_arr['j_stat'] != 'DEL' && (($member['mb_2'] != 3 && $member['mb_nick'] == $sql_ref_sbc_arr['photo_2_by'] && G5_TIME_YMD == $sql_ref_sbc_arr['photo_2_tm']) || $member['mb_2'] == 3)) {
											photo_up('photo_2', $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $sql_ref_sbc_arr['photo_2']);
										}
										echo '<br>' . $sql_ref_sbc_arr['item_2_type'];
										rep_view('heat_2' . $idx, 'report/heat', '',	$sql_ref_con_arr['heat_2']);
										?>
									</td>
								</tr>
								<tr>
									<td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Fit-up </td>
									<td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> Welding </td>
								</tr>
								<tr id='tr<?php echo $idx; ?>'>
									<td class='jnt_td' style="width: 50%;" colspan="2">
										<?php
										echo $PCS_DEL;
										if ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
											echo $sql_ref_sbc_arr['pcs_fitup_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
										} else {
											if ($member['mb_4'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_fitup_rlt_by'] && $sql_ref_sbc_arr['pcs_fitup_rlt_by'] != '') {
												echo $sql_ref_sbc_arr['pcs_fitup_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_fitup_rlt'];
											} else {
												insp_fitup($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_fitup_req_date'], $sql_ref_sbc_arr['pcs_fitup_rlt'], $sql_ref_sbc_arr['pcs_fitup_rlt_date'], $sql_ref_sbc_arr['pcs_vi_req_date']);
											}
										}
										?>
									</td>
									<td class='jnt_td' style="width: 50%;" colspan="2">
										<?php
										echo $PCS_DEL;

										if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
											echo 'N/A';
										} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
											echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
										} elseif ($sql_ref_sbc_arr['pcs_fitup_rlt'] == 'Accept') {
											if ($member['mb_4'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_vi_rlt_by'] && $sql_ref_sbc_arr['pcs_vi_rlt_by'] != '') {
												echo $sql_ref_sbc_arr['pcs_vi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_vi_rlt'];
											} else {
												insp_vi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_4'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_vi_req_date'], $sql_ref_sbc_arr['pcs_vi_rlt'], $sql_ref_sbc_arr['pcs_vi_rlt_date'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
											}
										} else {
											echo 'Not yet<br>Fit-up';
										}
										?>
									</td>
								</tr>
								<?php
								if ($sql_ref_sbc_arr['pmi_yn'] == 'YES' || $sql_ref_sbc_arr['pwht_yn'] == 'YES') {
								?>
									<tr>
										<td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> PMI </td>
										<td class='jnt_td td_sub_pkg1' style="width: 50%; height: 20px" colspan="2"> PWHT </td>
									</tr>
									<tr>
										<td class='jnt_td' style="width: 50%;" colspan="2">
											<?php
											echo $PCS_DEL;

											if ($sql_ref_sbc_arr['pmi_yn'] == 'YES') {
												if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
													echo 'Not yet<br>VI accepted';
												} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
													echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
												} else {
													if ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_pmi_rlt_by'] && $sql_ref_sbc_arr['pcs_pmi_rlt_by'] != '') {
														echo $sql_ref_sbc_arr['pcs_pmi_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pmi_rlt'];
													} else {
														insp_pmi($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pmi_req_date'], $sql_ref_sbc_arr['pcs_pmi_rlt'], $sql_ref_sbc_arr['pcs_pmi_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
													}
												}
											} else {
												echo 'N/A';
											}
											?>
										</td>
										<td class='jnt_td' style="width: 50%;" colspan="2">
											<?php
											echo $PCS_DEL;

											if ($sql_ref_sbc_arr['pwht_yn'] == 'YES') {
												if ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
													echo 'Not yet<br>VI accepted';
												} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
													echo $sql_ref_sbc_arr['pcs_pwht_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pwht_rlt'];
												} else {
													if ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_pwht_rlt_by'] && $sql_ref_sbc_arr['pcs_pwht_rlt_by'] != '') {
														echo $sql_ref_sbc_arr['pcs_pwht_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_pwht_rlt'];
													} else {
														insp_pwht($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_5'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_pwht_req_date'], $sql_ref_sbc_arr['pcs_pwht_rlt'], $sql_ref_sbc_arr['pcs_pwht_rlt_date'], $sql_ref_sbc_arr['pcs_nde_req_date']);
													}
												}
											} else {
												echo 'N/A';
											}
											?>
										</td>
									</tr>
								<?php } ?>

								<tr>
									<td class='jnt_td td_sub_pkg2' style="width: 50%; height: 20px" colspan="4"> NDE </td>
								</tr>
								<tr>
									<td class='jnt_td' style="width: 50%;" colspan="4">
										<?php
										echo $PCS_DEL;
										if ($sql_ref_sbc_arr['w_type'] != 'WELD') {
											echo 'N/A';
										} elseif ($sql_ref_sbc_arr['pcs_vi_rlt'] != 'Accept') {
											echo 'Not yet<br>VI accepted';
										} elseif ($sql_ref_sbc_arr['pwht_yn'] == 'YES' && $sql_ref_sbc_arr['pcs_pwht_rlt'] != 'Accept') {
											echo 'Not yet<br>PWHT accepted';
										} elseif ($sql_ref_sbc_arr['j_stat'] == 'DEL') {
											echo $sql_ref_sbc_arr['pcs_nde_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_nde_rlt'];
										} else {
											if ($member['mb_5'] < 2 && $member['mb_nick'] != $sql_ref_sbc_arr['pcs_nde_rlt_by'] && $sql_ref_sbc_arr['pcs_nde_rlt_by'] != '') {
												echo $sql_ref_sbc_arr['pcs_nde_rlt_date'] . '<br>' . $sql_ref_sbc_arr['pcs_nde_rlt'];
											} else {
												insp_nde($idx, $sql_ref_sbc_arr['dwg_no'], $sql_ref_sbc_arr['j_no'], $member['mb_6'], $member['mb_nick'], $sql_ref_sbc_arr['pcs_nde_type'], $sql_ref_sbc_arr['pcs_nde_req_date'], $sql_ref_sbc_arr['pcs_nde_rlt']);
											}
										}

										?>
									</td>
								</tr>


						<?php
							}
						}
						?>
					</tbody>
				</table>


		<?php
			}
		}
		?>


			</tbody>
		</table>
		<p>&nbsp;</p>
		<script language="javascript">
			function jointEdit(dn, con_dwg) {
				window.open("<?php echo PCS_LIB_URL; ?>/pcs_info_jnt_edit.php?dn=" + dn + "&con_dwg=" + con_dwg, 'jointedit' + dn, "width=1600, height=800, left=200, top=100");
			}
			$('html, body').stop().animate({
				scrollTop: $("#tr<?php echo $_POST['html_loc']; ?>").offset().top - screen.height / 2
			}, 300);

			function dom_hide(el) {
				if (document.getElementById(el).hidden) {
					document.getElementById(el).hidden = false;
				} else {
					document.getElementById(el).hidden = true;
				}
			}
		</script>