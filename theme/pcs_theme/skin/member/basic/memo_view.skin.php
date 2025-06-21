<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
if($kind == "recv") {
    $kind_str = "Sent";
    $kind_date = "Received";
}
else {
    $kind_str = "Received";
    $kind_date = "Sent";
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- View Message start { -->
<div id="memo_view" class="new_win">
    <h1 id="win_title"><?php echo $g5['title'] ?></h1>
    <div class="new_win_con2">
        <!-- Message Selection start { -->
        <ul class="win_ul">
            <li class="<?php if ($kind == 'recv') {  ?>selected<?php }  ?>"><a href="./memo.php?kind=recv">Received messages</a></li>
            <li class="<?php if ($kind == 'send') {  ?>selected<?php }  ?>"><a href="./memo.php?kind=send">Sent messages</a></li>
            <li><a href="./memo_form.php">Write message</a></li>
        </ul>
        <!-- } Message Selection end -->

        <article id="memo_view_contents">
            <header>
                <h2>Message Content</h2>
            </header>
            <div id="memo_view_ul">
                <div class="memo_view_li memo_view_name">
                	<ul class="memo_from">
                		<li class="memo_profile">
				            <?php echo get_member_profile_img($mb['mb_id']); ?>
				        </li>
						<li class="memo_view_nick"><?php echo $nick ?></li>
						<li class="memo_view_date"><span class="sound_only"><?php echo $kind_date ?> time</span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $memo['me_send_datetime'] ?></li> 
						<li class="memo_op_btn list_btn"><a href="<?php echo $list_link ?>" class="btn_b01 btn"><i class="fa fa-list" aria-hidden="true"></i><span class="sound_only">List</span></a></li>
						<li class="memo_op_btn del_btn"><a href="<?php echo $del_link; ?>" onclick="del(this.href); return false;" class="memo_del btn_b01 btn"><i class="fa fa-trash-o" aria-hidden="true"></i> <span class="sound_only">Delete</span></a></li>	
					</ul>
                    <div class="memo_btn">
                    	<?php if($prev_link) {  ?>
			            <a href="<?php echo $prev_link ?>" class="btn_left"><i class="fa fa-chevron-left" aria-hidden="true"></i> Previous message</a>
			            <?php }  ?>
			            <?php if($next_link) {  ?>
			            <a href="<?php echo $next_link ?>" class="btn_right">Next message <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
			            <?php }  ?>  
                    </div>
                </div>
            </div>
            <p>
                <?php echo conv_content($memo['me_memo'], 0) ?>
            </p>
        </article>
		<div class="win_btn">
			<?php if ($kind == 'recv') {  ?><a href="./memo_form.php?me_recv_mb_id=<?php echo $mb['mb_id'] ?>&amp;me_id=<?php echo $memo['me_id'] ?>" class="reply_btn">Reply</a><?php }  ?>
			<button type="button" onclick="window.close();" class="btn_close">Close</button>
    	</div>
    </div>
</div>
<!-- } View Message end -->