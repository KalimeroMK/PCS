<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Login start { -->
<div id="mb_login" class="mbskin">
    <div class="mbskin_box">
        <h1><?php echo $g5['title'] ?></h1>
        <div class="mb_log_cate">
            <h2><span class="sound_only">Member</span>Login</h2>
            <a href="<?php echo G5_BBS_URL ?>/register.php" class="join">Sign Up</a>
        </div>
        <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
        <input type="hidden" name="url" value="<?php echo $login_url ?>">
        
        <fieldset id="login_fs">
            <legend>Member Login</legend>
            <label for="login_id" class="sound_only">Username<strong class="sound_only"> Required</strong></label>
            <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" placeholder="Username">
            <label for="login_pw" class="sound_only">Password<strong class="sound_only"> Required</strong></label>
            <input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxLength="20" placeholder="Password">
            <button type="submit" class="btn_submit">Login</button>
            
            <div id="login_info">
                <div class="login_if_auto chk_box">
                    <input type="checkbox" name="auto_login" id="login_auto_login" class="selec_chk">
                    <label for="login_auto_login"><span></span> Auto Login</label>  
                </div>
                <div class="login_if_lpl">
                    <a href="<?php echo G5_BBS_URL ?>/password_lost.php" target="_blank" id="login_password_lost">Find Info</a>  
                </div>
            </div>
        </fieldset> 
        </form>
        <?php @include_once(get_social_skin_path().'/social_login.skin.php'); // Social login button if used ?>
    </div>
</div>

<script>
jQuery(function($){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("If you use auto login, you will not need to enter your username and password next time.\n\nPlease refrain from using this feature in public places as your personal information may be leaked.\n\nDo you want to use auto login?");
        }
    });
});

function flogin_submit(f)
{
    if( $( document.body ).triggerHandler( 'login_sumit', [f, 'flogin'] ) !== false ){
        return true;
    }
    return false;
}
</script>
<!-- } Login end -->
