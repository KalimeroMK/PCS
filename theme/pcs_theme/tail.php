<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}
?>

    </div>
    <div id="aside">
        <?php echo outlogin('theme/basic'); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
        <?php echo poll('theme/basic'); // 설문조사, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
    </div>
</div>

</div>
<!-- } 콘텐츠 끝 -->

<hr>

<!-- 하단 시작 { -->
<div id="ft">

    <div id="ft_wr">
        <div id="ft_link" class="ft_cnt">
        	<a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a>
		</div>
        <div id="ft_link" class="ft_cnt">
			<a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a>
		</div>
        <div id="ft_link" class="ft_cnt">
            <a href="<?php echo get_pretty_url('content', 'provision'); ?>">서비스이용약관</a>
		</div>
        <div id="ft_link" class="ft_cnt">
            <a href="<?php echo get_device_change_url(); ?>">모바일버전</a>
        </div>
	</div>      
        <!-- <div id="ft_catch"><img src="<?php echo G5_IMG_URL; ?>/ft_logo.png" alt="<?php echo G5_VERSION ?>"></div> -->
        <div id="ft_copy">Copyright &copy; <b><a href="https://www.pcsbymd.com" target="_blank">www.pcsbymd.com</a></b> All rights reserved.</div>
    
    
    <button type="button" id="top_btn">
    	<i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span>
    </button>
    <script>
    $(function() {
        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });
    });
    </script>
</div>

<?php
if(G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>