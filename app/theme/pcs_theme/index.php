<?php
//error_reporting(E_ALL);ini_set("display_errors", 1);
error_reporting(0);


if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
?>

<h2 class="sound_only">최신글</h2>

<div class="latest_wr">
<!-- 최신글 시작 { -->
<div>
<?php
 	if(!$is_member) { echo "<p align='center'> <font color = red size = 5> <strong> 로그인이 필요합니다. </strong></font></p>"; }
	elseif(!$member['mb_1']) { echo "<p align='center'> <font color = red size = 5> <strong> 사용권한이 필요합니다.</strong></font></p>"; }
?>
<!-- } 최신글 끝 -->
</div>
</div>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>