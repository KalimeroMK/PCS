<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Find Member Information start { -->
<div id="find_info" class="new_win">
    <h1 id="win_title">Find Member Information</h1>
    <div class="new_win_con">
        <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
        <fieldset id="info_fs">
            <p>
                Please enter the email address you registered with.<br>
                Your ID and password information will be sent to that email.
            </p>
            <label for="mb_email" class="sound_only">E-mail address<strong class="sound_only">Required</strong></label>
            <input type="text" name="mb_email" id="mb_email" required class="required frm_input full_input email" size="30" placeholder="E-mail address">
        </fieldset>
        <?php echo captcha_html();  ?>
        <div class="win_btn">
            <button type="submit" class="btn_submit">Confirm</button>
            <button type="button" onclick="window.close();" class="btn_close">Close</button>  
        </div>
        </form>
    </div>
</div>

<script>
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}

$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>
<!-- } Find Member Information end -->