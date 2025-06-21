<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Send Message start { -->
<div id="memo_write" class="new_win">
    <h1 id="win_title"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Message</h1>
    <div class="new_win_con2">
        <ul class="win_ul">
            <li><a href="./memo.php?kind=recv">Received messages</a></li>
            <li><a href="./memo.php?kind=send">Sent messages</a></li>
            <li class="selected"><a href="./memo_form.php">Write message</a></li>
        </ul>

        <form name="fmemoform" action="<?php echo $memo_action_url; ?>" onsubmit="return fmemoform_submit(this);" method="post" autocomplete="off">
        <div class="form_01">
            <h2 class="sound_only">Write message</h2>
            <ul>
                <li>
                    <label for="me_recv_mb_id" class="sound_only">Recipient Member ID<strong>Required</strong></label>
                    
                    <input type="text" name="me_recv_mb_id" value="<?php echo $me_recv_mb_id; ?>" id="me_recv_mb_id" required class="frm_input full_input required" size="47" placeholder="Recipient Member ID">
                    <span class="frm_info">To send to multiple members, separate with commas (,).</span>
                    <?php if ($config['cf_memo_send_point']) { ?>
                    <br ><span class="frm_info">When sending a message, <?php echo number_format($config['cf_memo_send_point']); ?> points will be deducted per message.</span>
                    <?php } ?>
                </li>
                <li>
                    <label for="me_memo" class="sound_only">Content</label>
                    <textarea name="me_memo" id="me_memo" required class="required"><?php echo $content ?></textarea>
                </li>
                <li>
                    <span class="sound_only">Prevent Auto Registration</span>
                    
                    <?php echo captcha_html(); ?>
                    
                </li>
            </ul>
        </div>

        <div class="win_btn">
        	<button type="submit" id="btn_submit" class="btn btn_b02 reply_btn">Send</button>
        	<button type="button" onclick="window.close();" class="btn_close">Close</button>
        </div>
    </div>
    </form>
</div>

<script>
function fmemoform_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}
</script>
<!-- } Send Message end -->