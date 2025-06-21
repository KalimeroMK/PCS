<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_MOBILE_PATH.'/head.php');

 	if(!$is_member) { echo "<p align='center'> <font color = red size = 5> <strong> 로그인이 필요합니다. </strong></font></p>"; }
	elseif(!$member['mb_1']) { echo "<p align='center'> <font color = red size = 5> <strong> 사용권한이 필요합니다.</strong></font></p>"; }

include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>