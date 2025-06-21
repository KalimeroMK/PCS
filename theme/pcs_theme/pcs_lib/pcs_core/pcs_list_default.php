    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $board['bo_subject'] ?> 목록</caption>
        <thead>
        <tr>
            <th scope="col">No.</th>
<?php

		switch ($_GET['bo_table']) { 			//	각 개시판 List 컬럼 결정

			case 'equipment' 	:	?>
				
			<th scope="col"> Item no. </th>
			<th scope="col"> Description </th>
			<th scope="col"> Unit </th>
			<th scope="col"> Type </th>
			<th scope="col"> Foundation </th>
			<th scope="col"> G/A Drawing </th>
			<th scope="col"> M/H , NOZZLE </th>
			<th scope="col"> P/F , LADDER </th>
			<th scope="col"> INTERNAL </th>
			
<?php		break;

			case 'spool' 	:	?>
				
			<th scope="col"> Spool no. </th>
			<th scope="col"> Weld state </th>
			<th scope="col"> Welding </th>
			<th scope="col"> PWHT </th>
			<th scope="col"> PMI </th>
			<th scope="col"> Paint </th>
			<th scope="col"> Location </th>
			<th scope="col"> Last Check<br>(days before) </th>
			<th scope="col"> Checked Time<br>by QR Code </th>
			
<?php		break;

			case 'iso' 	:	?>
				
			<th scope="col"> ISO Drawing no. </th>
			<th scope="col"> Shop Dwg no. </th>
            <th scope="col"> State </th>
            <th scope="col"> Numbering </th>
			<th scope="col"> Rev </th>
			<th scope="col"> NPS </th>
			<th scope="col"> Material </th>
			<th scope="col"> NDE </th>
			<th scope="col"> PMI/PWHT </th>
			<th scope="col"> Paint </th>
			<th scope="col"> Insulation </th>
            <th scope="col"> Issued Date </th>
			
<?php		break;

			case 'package' 	:	?>
				
            <th scope="col"> Package no. </th>
			<th scope="col"> test type </th>
			<th scope="col"> CLASS </th>
			<th scope="col"> WELDING </th>
			<th scope="col"> SUPPORT </th>
			<th scope="col"> PWHT </th>
			<th scope="col"> PMI </th>
			<th scope="col"> Punch<br>A </th>
			<th scope="col"> Punch<br>B </th>
			<th scope="col"> Punch<br>C </th>
			<th scope="col">  RT </th>
			<th scope="col"> Status in <br> Y/M/D </th>

<?php		break;

			case 'pnid' 	:	?>
				
			<th scope="col"> P&ID no. </th>
            <th scope="col"> AREA </th>
			<th scope="col"> Original </th>
			<th scope="col"> High-Pressured<br>Marked-P&ID </th>
			<th scope="col"> Other<br>Marked-P&ID</th>
			<th scope="col"> Rev </th>
            <th scope="col"> Regist<br>Date </th>
			
<?php		break;

			case 'plan' 	:	?>
				
			<th scope="col"> PLAN dwg no. </th>
			<th scope="col"> Rev </th>
            <th scope="col"> Unit </th>
            <th scope="col"> Area </th>
            <th scope="col"> TP Qty. </th>
            <th scope="col"> Issue Date </th>
			
<?php		break;

			case 'tp' 	:	?>
				
			<th scope="col"> Tie-In Point no. </th>
            <th scope="col"> Unit </th>
			<th scope="col"> Plan Dwg </th>
			<th scope="col"> P&ID Dwg </th>
			<th scope="col"> ISO.1 </th>
			<th scope="col"> ISO.2 </th>
			<th scope="col"> ISO.3 </th>
			<th scope="col"> 3D Model </th>
			<th scope="col"> TP Photo </th>
			<th scope="col"> Work Done </th>
			
<?php		break;

			case 'work' 	:	?>
				
			<th scope="col"> Working Plan Drawing </th>
            <th scope="col"> Unit </th>
			<th scope="col"> Modified Date </th>
			
<?php		break;

			case 'daily' 	:

				$query_id = 'SELECT wr_subject FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_id = '.number_format($total_count);		// 쿼리문
				$sql_id = sql_query ($query_id);
				$sql_id_arr = sql_fetch_array ($sql_id);

				if($sql_id_arr['wr_subject']!=G5_TIME_YMD){
					$cnt_inc = number_format($total_count)+1;
					sql_query ('INSERT INTO `'.G5_TABLE_PREFIX.'write_daily` (`wr_id`, `wr_num`, `wr_reply`, `wr_parent`, `wr_is_comment`, `wr_comment`, `wr_comment_reply`, `ca_name`, `wr_option`, `wr_subject`, `wr_content`, `wr_link1`, `wr_link2`, `wr_link1_hit`, `wr_link2_hit`, `wr_hit`, `wr_good`, `wr_nogood`, `mb_id`, `wr_password`, `wr_name`, `wr_email`, `wr_homepage`, `wr_datetime`, `wr_file`, `wr_last`, `wr_ip`, `wr_facebook_user`, `wr_twitter_user`, `wr_1`, `wr_2`, `wr_3`, `wr_4`, `wr_5`, `wr_6`, `wr_7`, `wr_8`, `wr_9`, `wr_10`)
								VALUES ('.$cnt_inc.', -'.$cnt_inc.', "", '.$cnt_inc.', 0, 0, "", "", "", "'.G5_TIME_YMD.'", ";", "", "", 0, 0, 0, 0, 0, "", "", "", "", "", "'.G5_TIME_YMDHIS.'", 0, "", "", "", "", 0, ";", ";", "", "", "", "", "", "", "")');
					sql_query('UPDATE '.G5_TABLE_PREFIX.'board set bo_count_write = '.$cnt_inc.' WHERE bo_table = "daily"');
					mkdir(PCS_DATA_DAILY.'/'.G5_TIME_YMD.'/', 0707); 
				}
?>
				
			<th scope="col"> Daily Report Date </th>
            <th scope="col"> Comment Qty. </th>
			<th scope="col"> Last Check time </th>
			
<?php		break;

			default			:	?>
				
            <th scope="col">Subject</th>

<?php		break;
		} ?>
       </tr>
        </thead>
        <tbody>
        <?php
		
        for ($i=0; $i<count($list); $i++) {
			
			$or_sub = str_replace("<b class=\"sch_word\">","",$list[$i]['subject']) ;
			$or_sub = str_replace("</b>","",$or_sub) ;

         ?>
        <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
            <td class="td_num2">
            <?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong class="notice_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i><span class="sound_only">공지</span></strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\">열람중</span>";
            else
                echo $list[$i]['num'];
             ?>
            </td>

            <td class="pcs_subject" style="padding-left:<?php echo $list[$i]['reply'] ? (strlen($list[$i]['wr_reply'])*10) : '0'; ?>px">
                <?php
                if ($is_category && $list[$i]['ca_name']) {
                 ?>
                <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                <?php } ?>
                <div class="bo_tit">
                    
                    <a href="<?php echo $list[$i]['href'] ?>">
                        <?php echo $list[$i]['icon_reply'] ?>
                        <?php
                            if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
                         ?>
                        <?php echo $list[$i]['subject'] ?>
                       
                    </a>
                    <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><span class="cnt_cmt">+ <?php echo $list[$i]['wr_comment']; ?></span><span class="sound_only">개</span><?php } ?>
                </div>

            </td>
<?php	switch ($_GET['bo_table']) { 

			case 'equipment' 	:				

			$query_eq = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_equipment WHERE eq_no = "'.$or_sub.'"';		// 쿼리문
			$sql_eq = sql_query ($query_eq);
			$sql_eq_arr = sql_fetch_array ($sql_eq);
//			echo $query_time;
?>
			<td class="pcs_td_spl"><?php echo $sql_eq_arr['description'] ?></td>
			<td class="pcs_td_spl"><?php echo $sql_eq_arr['unit'] ?></td>
			<td class="pcs_td_spl"><?php echo $sql_eq_arr['type'] ?></td>
			<td class="pcs_td_spl"><?php if($sql_eq_arr['dwg_1']) {echo $sql_eq_arr['dwg_1'];} else {echo 'N/A';}?></td>
			<td class="pcs_td_spl"><?php if($sql_eq_arr['dwg_2']) {echo $sql_eq_arr['dwg_2'];} else {echo 'N/A';}?></td>
			<td class="pcs_td_spl"><?php if($sql_eq_arr['dwg_3']) {echo $sql_eq_arr['dwg_3'];} else {echo 'N/A';}?></td>
			<td class="pcs_td_spl"><?php if($sql_eq_arr['dwg_4']) {echo $sql_eq_arr['dwg_4'];} else {echo 'N/A';}?></td>
			<td class="pcs_td_spl"><?php if($sql_eq_arr['dwg_5']) {echo $sql_eq_arr['dwg_5'];} else {echo 'N/A';}?></td>

<?php							break;

			case 'spool' 	:				//	spool_no 개시판에서만 도면 클릭 가능 

				$query_spl = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_spool WHERE spool_no = "'.$or_sub.'"';		// 쿼리문
				$sql_spl = sql_query ($query_spl);
				$sql_spl_arr = sql_fetch_array ($sql_spl);
			
				$query_time = 'SELECT DATEDIFF(NOW(),chk_tm) FROM '.G5_TABLE_PREFIX.'pcs_info_spl_stat WHERE spool_no = "'.$or_sub.'"';		// 쿼리문
				$sql_time = sql_query ($query_time);
				$sql_time_arr = sql_fetch_array ($sql_time);
				$lastdays = $sql_time_arr['DATEDIFF(NOW(),chk_tm)'];
//			echo $query_time;
?>
			<td class="pcs_td_spl">
			<?php //echo $sql_spl_arr['state'] ?>
<?php 			switch($sql_spl_arr['state']){
					case 'On_going'		:	echo '<font color = orange><strong>On welding</strong></font>';break;
					case 'Finished' 	:	echo '<font color = green><strong>Finished</strong></font>';break;
					default				:	echo '<font color = red><strong>Not start</strong></font>';	break;
				}
?>
			</td>
			<td class="pcs_td_spl"><?php echo $sql_spl_arr['st_weld'] ?></td>
			<td class="pcs_td_spl"><?php echo $sql_spl_arr['st_pwht'] ?></td>
			<td class="pcs_td_spl"><?php echo $sql_spl_arr['st_pmi'] ?></td>
			<td class="pcs_td_spl"><?php echo $sql_spl_arr['st_paint'] ?></td>
			<td class="pcs_td_spl"><?php echo $sql_spl_arr['location'] ?></td>
			<td class="pcs_td_spl"><?php if($lastdays){echo 'D +<b><font size = 4>'.$lastdays;} ?></td>
			<td class="pcs_td_spl"><?php if(substr($sql_spl_arr['chk_tm'],0,2)!='00'){$tmset = explode(' ',$sql_spl_arr['chk_tm']); echo $tmset[0].'<br>'.$tmset[1];} ?></td>

<?php							break;

			case 'iso' 	:					//	dwg_no 개시판에서만 도면 클릭 가능 
			

				$query_dwg = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$or_sub.'"';		// 쿼리문
				$sql_dwg = sql_query ($query_dwg);
				$sql_dwg_arr = sql_fetch_array ($sql_dwg);
			
				$con_EQ_array = explode(";",$sql_dwg_arr['con_eq']);
			
				$query_dwg_coor_check = 'SELECT dwg_state FROM '.G5_TABLE_PREFIX.'pcs_info_iso_coor WHERE dwg_no = "'.$or_sub.'" AND rev_no = "'.$sql_dwg_arr['rev_no'].'"';
				$sql_dwg_coor_check = sql_query ($query_dwg_coor_check);
				$sql_dwg_coor_array = sql_fetch_array ($sql_dwg_coor_check);
			
				if($sql_dwg_coor_array['dwg_state']=='Marked'){$state_info = '<font color = "blue">'.$sql_dwg_coor_array['dwg_state'].'</font>';}
				else if($sql_dwg_coor_array['dwg_state']=='Approved'){$state_info = '<font color = "green"><b>'.$sql_dwg_coor_array['dwg_state'].'</b></font>';}
				else {$state_info = 'Not Yet';}

?>
			<td class="pcs_td_pkg">
<?php 			if($member['mb_1']){echo '<a href = "javascript:document.shop'.$i.'.submit()" >'.$sql_dwg_arr['shop_dwg'].'</a>';	viewPDF('shop'.$i, 'shop', $or_sub, $sql_dwg_arr['shop_dwg']); } else {echo $sql_dwg_arr['shop_dwg'];}?>
			</td>
			<td class="pcs_td_dwg">
<?php 			switch($sql_dwg_arr['state']){
					case 'HOLD'	:	echo '<font color = "blue"><b>'.$sql_dwg_arr['state'].'</b></font>';break;
					case 'DEL' 	:	echo '<font color = "red"><b>'.$sql_dwg_arr['state'].'</b></font>';break;
					default				:	echo $sql_dwg_arr['state'];	break;
				}
?>
			</td>
			<td class="pcs_td_pkg">
<?php 			if($member['mb_1']){echo '<a href = "javascript:document.marked'.$i.'.submit()" >'.$state_info.'</a>';	viewPDF('marked'.$i, 'fab', $or_sub, $sql_dwg_arr['rev_no']); } else {echo $state_info;}?>
			</td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['rev_no'] ?></td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['line_size'] ?></td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['material'] ?></td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['nde_rate'].' %' ?></td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['pmi'].'/'.$sql_dwg_arr['pwht'] ?></td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['paint_code'] ?></td>
			<td class="pcs_td_dwg"><?php echo $sql_dwg_arr['line_insul'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_dwg_arr['reg_date'] ?></td>

<?php							break;


			case 'pnid' 	:

				$query_pnid = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid WHERE pnid_no = "'.$or_sub.'"';
				$sql_pnid = sql_query ($query_pnid);
				$sql_pnid_arr = sql_fetch_array ($sql_pnid);
			
				$query_pnid_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_no = "'.$or_sub.'"';
				$sql_pnid_coor_check = sql_query ($query_pnid_coor_check);
				$sql_pnid_coor_array = sql_fetch_array ($sql_pnid_coor_check);
			
				$joint_coor_info = $sql_pnid_coor_array['joint_info'];

?>
			<td class="pcs_td_nor">
<?php 			switch($sql_pnid_arr['unit']){
					case 'HOT'		:	echo '<font color = red>HOT</font>';break;
					case 'COLD' 	:	echo '<font color = blue><strong>COLD</strong></font>';break;
					default			:	echo $sql_pnid_arr['unit'];	break;
				}
?>
			</td>
			<td class="pcs_td_nor">
				<a href = 'javascript:document.ori_pnid<?php echo $i;?>.submit()'><b>VIEW</b></a><?php viewPDF('ori_pnid'.$i,'pnid',$or_sub,$sql_pnid_arr['rev_no']);?>
			</td>
			<td class="pcs_td_nor">
				<a href = 'javascript:document.cmt_pnid<?php echo $i;?>.submit()'><b>VIEW</b></a><?php viewPDF('cmt_pnid'.$i,'pnid',$or_sub,'CMT');?>
			</td>
			<td class="pcs_td_nor">
				<a href = 'javascript:document.pkg_pnid<?php echo $i;?>.submit()'><b>VIEW</b></a><?php viewPDF('pkg_pnid'.$i,'pnid',$or_sub,'SEBO');?>
			</td>
			<td class="pcs_td_pnid"><?php echo $sql_pnid_arr['rev_no'] ?></td>
			<td class="pcs_td_pnid"><?php echo $sql_pnid_arr['reg_date'] ?></td>

<?php							break;


			case 'plan' 	:

				$query_plan = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_plan WHERE plan_no = "'.$or_sub.'"';
				$sql_plan = sql_query ($query_plan);
				$sql_plan_arr = sql_fetch_array ($sql_plan);
			
?>
 			<td class="pcs_td_pkg"><?php echo $sql_plan_arr['rev_no']; ?> </td>
 			<td class="pcs_td_pkg"><?php echo $sql_plan_arr['unit']; ?> </td>
			<td class="pcs_td_pkg"> <a href='javascript:document.submit_for<?php echo $i;?>.submit()'> <b> <?php echo $sql_plan_arr['area']; ?>  </b> </a> </td>
				<form name='submit_for<?php echo $i;?>' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post" target="<?php echo $or_sub;?>" onSubmit="return doSumbit()"> 
					<input type="hidden" name="folder" value="plan/piping">
					<input type="hidden" name="file" value="<?php echo $or_sub;?>">
					<input type="hidden" name="rev" value="<?php echo $sql_plan_arr['rev_no'];?>">
				</form>
			<td class="pcs_td_pkg"><?php if($sql_plan_arr['tp_no']!='NO TP'){echo substr_count($sql_plan_arr['tp_no'],';').' ea';}else{echo 'NO TP';} ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_plan_arr['reg_date'] ?></td>

<?php							break;

			case 'work' 	:

				$query_work = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_work WHERE work_no = "'.$or_sub.'"';
				$sql_work = sql_query ($query_work);
				$sql_work_arr = sql_fetch_array ($sql_work);


				$work_file = PCS_WORK_PDF.'/'.$or_sub.'.pdf';

							
?>
			<td class="pcs_td_pkg"> <a href='javascript:document.submit_for<?php echo $i;?>.submit()'> <b> <?php echo $sql_work_arr['unit']; ?>  </b> </a> </td>
			<td class="pcs_td_nor"><?php if (file_exists($work_file)) {echo date ("Y-m-d", filemtime($work_file));} else {echo '-';} ?></td>
				<form name='submit_for<?php echo $i;?>' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post" target="<?php echo $or_sub;?>" onSubmit="return doSumbit()"> 
					<input type="hidden" name="folder" value="plan/working">
					<input type="hidden" name="file" value="<?php echo $or_sub;?>">

				</form>

<?php							break;


			case 'tp' 	:

				$query_tp = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_tp WHERE tp_no = "'.$or_sub.'"';
				$sql_tp = sql_query ($query_tp);
				$sql_tp_arr = sql_fetch_array ($sql_tp);
				

				$query_tp_plan = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_plan WHERE plan_no = "'.$sql_tp_arr['plan_no'].'"';
				$sql_tp_plan = sql_query ($query_tp_plan);
				$sql_tp_plan_arr = sql_fetch_array ($sql_tp_plan);

				$query_tp_pnid = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid WHERE pnid_no = "'.$sql_tp_arr['pnid_no'].'"';
				$sql_tp_pnid = sql_query ($query_tp_pnid);
				$sql_tp_pnid_arr = sql_fetch_array ($sql_tp_pnid);
			
				$query_tp_pt = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_tp_stat WHERE tp_no = "'.$or_sub.'"';
				$sql_tp_pt = sql_query ($query_tp_pt);
				$sql_tp_pt_arr = sql_fetch_array ($sql_tp_pt);

//				$ran = mt_rand(1, 10000);
				$tp_dwg = explode(';',$sql_tp_arr['dwg_no']);
?>
			<td class="pcs_td_pkg"><?php echo $sql_tp_arr['unit'] ?></td>

			<td class="pcs_td_nor"> <a href='javascript:document.submit_forplan<?php echo $i;?>.submit()'> <b> <?php echo $sql_tp_arr['plan_no']; ?>  </b> </a> </td>
				<form name='submit_forplan<?php echo $i;?>' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post" target="<?php echo $or_sub.'plan';?>" onSubmit="return doSumbit()"> 
					<input type="hidden" name="folder" value="plan/piping">
					<input type="hidden" name="file" value="<?php echo $sql_tp_plan_arr['plan_no'];?>">
					<input type="hidden" name="rev" value="<?php echo $sql_tp_plan_arr['rev_no'];?>">
				</form>

			<td class="pcs_td_nor"> <a href='javascript:document.submit_forpnid<?php echo $i;?>.submit()'> <b> <?php echo $sql_tp_arr['pnid_no']; ?>  </b> </a> </td>
				<form name='submit_forpnid<?php echo $i;?>' action="<?php echo PCS_WPV_URL; ?>/viewer.php" method="post" target="<?php echo $or_sub.'pnid';?>" onSubmit="return doSumbit()"> 
					<input type="hidden" name="folder" value="pnid">
					<input type="hidden" name="file" value="<?php echo $sql_tp_pnid_arr['pnid_no'];?>">
					<input type="hidden" name="rev" value="<?php echo $sql_tp_pnid_arr['rev_no'];?>">
				</form>
				
<?php 
				for($tpi=0;$tpi<3;$tpi++){
					$query_tpdwg = 'SELECT dwg_no, rev_no, state FROM '.G5_TABLE_PREFIX.'pcs_info_iso WHERE dwg_no = "'.$tp_dwg[$tpi].'"';
					$sql_tpdwg = sql_query ($query_tpdwg);
					$sql_tpdwg_arr = sql_fetch_array ($sql_tpdwg);
?>
			<td class="pcs_td_pkg">
<?php 			if($member['mb_1']&&$tp_dwg[$tpi]){echo '<a href = "javascript:document.marked'.$i.$tpi.'.submit()" >'.substr($tp_dwg[$tpi],-2,2).' - '.$sql_tpdwg_arr['state'].'</a>';	viewPDF('marked'.$i.$tpi, 'fab', $sql_tpdwg_arr['dwg_no'], $sql_tpdwg_arr['rev_no']); } else {echo $sql_tpdwg_arr['dwg_no'];}?>
			</td>
<?php 
				}
?>

			<td class="pcs_td_nor"> 
<?php 
			if($sql_tp_pt_arr['tp_photo1']){photo_thumb('tp', $sql_tp_pt_arr['tp_photo1'], 'photo1', 80, 'thumb_');}
?>
			</td>
			<td class="pcs_td_nor"> 
<?php 
			if($sql_tp_pt_arr['tp_photo2']){photo_thumb('tp', $sql_tp_pt_arr['tp_photo2'], 'photo1', 80, 'thumb_');}
?>
			</td>
			<td class="pcs_td_nor"> 
<?php 
			if($sql_tp_pt_arr['tp_photo3']){photo_thumb('tp', $sql_tp_pt_arr['tp_photo3'], 'photo1', 80, 'thumb_');}
?>
			</td>


<?php							break;


			case 'package' 	:

				$query_pkg = 'SELECT A.*, B.* FROM '.G5_TABLE_PREFIX.'pcs_info_package AS A JOIN '.G5_TABLE_PREFIX.'pcs_info_pkg_stat AS B ON A.pkg_no=B.pkg_no WHERE A.pkg_no = "'.$or_sub.'"';		// 쿼리문
				$sql_pkg = sql_query ($query_pkg);
				$sql_pkg_arr = sql_fetch_array ($sql_pkg);
?>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['test_type'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['class'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_wd'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_spt'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_pwht'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_pmi'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_a'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_b'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_c'] ?></td>
			<td class="pcs_td_pkg"><?php echo $sql_pkg_arr['total_rt'] ?></td>
			<td class="pcs_td_pkg"><?php if($sql_pkg_arr['last_chk'] == '0000-00-00') {echo '-';} else {echo $sql_pkg_arr['last_chk'];} ?></td>

<?php							break;

			case 'daily' 	:

				$query_daily = 'SELECT * FROM '.G5_TABLE_PREFIX.'write_daily WHERE wr_id = "'.$list[$i]['num'].'"';		// 쿼리문
				$sql_daily = sql_query ($query_daily);
				$sql_daily_arr = sql_fetch_array ($sql_daily);
				
				$daily_time = explode(';',$sql_daily_arr['wr_3']);

?>
			<td class="pcs_td_nor"><?php echo $sql_daily_arr['wr_1'] ?></td>
			<td class="pcs_td_nor"><?php echo $daily_time[$sql_daily_arr['wr_1']] ?></td>

<?php							break;

			default			:	break;
		}
?>

        </tr>
<?php 	} ?>
        <?php if (count($list) == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">No item.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>