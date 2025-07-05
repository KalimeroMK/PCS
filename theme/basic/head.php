<?php

if (!defined('_GNUBOARD_')) exit;
// Individual page access not allowed
if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/header.php');
    return;
}
if(G5_COMMUNITY_USE === false) {
    define('G5_IS_COMMUNITY_PAGE', true);
    include_once(G5_THEME_SHOP_PATH.'/shop.header.php');
    return;
}
include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>

<!-- Header Start { -->
<div id="hd">
    <h1 id="hd_h1"><?php 
echo $g5['title'] ?>
?></h1>
    <div id="skip_to_container"><a href="#container">Skip to main content</a></div>

    <?php 
if(defined('_INDEX_')) { // Only run on index page
        include G5_BBS_PATH.'/newwin.inc.php'; // Popup layer
    }
?>
    <div id="tnb">
    	<div class="inner">
            <?php 
?>
    		<ul id="hd_define">
    			<li class="active"><a href="<?php 
echo G5_URL ?>
?>/">Community</a></li>
                <?php 
if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
    			<li><a href="<?php echo G5_SHOP_URL ?>/">Shop</a></li>
            <?php }
?>
    		</ul>
            <?php 
?>
			<ul id="hd_qnb">
	            <li><a href="<?php 
echo G5_BBS_URL ?>
?>/faq.php">FAQ</a></li>
	            <li><a href="<?php 
echo G5_BBS_URL ?>
?>/qalist.php">Q&A</a></li>
	            <li><a href="<?php 
echo G5_BBS_URL ?>
?>/new.php">New Posts</a></li>
	            <li><a href="<?php 
echo G5_BBS_URL ?>
?>/current_connect.php" class="visit">Visitors<strong class="visit-num"><?php 
echo connect('theme/basic');
// Current visitors, to use the theme's skin specify as theme/basic
?></strong></a></li>
	        </ul>
		</div>
    </div>
    <div id="hd_wrapper">

        <div id="logo">
            <a href="<?php 
echo G5_URL ?>
?>"><img src="<?php 
echo G5_IMG_URL ?>
?>/logo.png" alt="<?php 
echo $config['cf_title'];
?>"></a>
        </div>
    
        <div class="hd_sch_wr">
            <fieldset id="hd_sch">
                <legend>Site-wide Search</legend>
                <form name="fsearchbox" method="get" action="<?php 
echo G5_BBS_URL ?>
?>/search.php" onsubmit="return fsearchbox_submit(this);">
                <input type="hidden" name="sfl" value="wr_subject||wr_content">
                <input type="hidden" name="sop" value="and">
                <label for="sch_stx" class="sound_only">Search term required</label>
                <input type="text" name="stx" id="sch_stx" maxlength="20" placeholder="Please enter a search term">
                <button type="submit" id="sch_submit" value="Search"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">Search</span></button>
                </form>

                <script>
                function fsearchbox_submit(f)
                {
                    var stx = f.stx.value.trim();
                    if (stx.length < 2) {
                        alert("Please enter at least two characters for the search term.");
                        f.stx.select();
                        f.stx.focus();
                        return false;
                    }

                    // Uncomment this if searching puts too much load on the server.
                    var cnt = 0;
                    for (var i = 0; i < stx.length; i++) {
                        if (stx.charAt(i) == ' ')
                            cnt++;
                    }

                    if (cnt > 1) {
                        alert("For faster searching, only one space is allowed in the search term.");
                        f.stx.select();
                        f.stx.focus();
                        return false;
                    }
                    f.stx.value = stx;

                    return true;
                }
                </script>

            </fieldset>
                
            <?php 
echo popular('theme/basic');
// Popular search terms. To use the theme's skin, specify as theme/basic
?>
        </div>
        <ul class="hd_login">        
            <?php 
if ($is_member) {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php">정보수정</a></li>
        <li><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
        <?php if ($is_admin) {  ?>
            <li class="tnb_admin"><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">관리자</a></li>
        <?php }  ?>
            <?php } else {  ?>
            <li><a href="<?php echo G5_BBS_URL ?>/register.php">회원가입</a></li>
        <li><a href="<?php echo G5_BBS_URL ?>/login.php">로그인</a></li>
        <?php }
?>

        </ul>
    </div>
    
    <nav id="gnb">
        <h2>Main Menu</h2>
        <div class="gnb_wrap">
            <ul id="gnb_1dul">
                <li class="gnb_1dli gnb_mnal"><button type="button" class="gnb_menu_btn" title="전체메뉴"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button></li>
                <?php 
$menu_datas = get_menu_db(0, true);
$gnb_zindex = 999;
// For setting gnb_1dli z-index value
$i = 0;
foreach( $menu_datas as $row ){
    if( empty($row) ) continue;
    $add_class = (isset($row['sub']) && $row['sub']) ? 'gnb_al_li_plus' : '';
?>
                <li class="gnb_1dli <?php echo $add_class; ?>" style="z-index:<?php echo $gnb_zindex--; ?>">
    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_1da"><?php echo $row['me_name'] ?></a>
    <?php
                    $k = 0;
    foreach( (array) $row['sub'] as $row2 ){

        if( empty($row2) ) continue; 

        if($k == 0)
            echo '<span class="bg">Subcategory</span><div class="gnb_2dul"><ul class="gnb_2dul_box">'.PHP_EOL;
    ?>
                        <li class="gnb_2dli"><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>" class="gnb_2da"><?php echo $row2['me_name'] ?></a></li>
    <?php
                    $k++;
    }   //end foreach $row2

    if($k > 0)
        echo '</ul></div>'.PHP_EOL;
    ?>
                </li>
<?php
                $i++;
}
//end foreach $row
if ($i == 0) {  ?>
                    <li class="gnb_empty">Menu is being prepared.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">Admin Mode &gt; Settings &gt; Menu Settings</a> can be set here.<?php } ?></li>
<?php }
?>
            </ul>
            <div id="gnb_all">
                <h2>All Menus</h2>
                <ul class="gnb_al_ul">
                    <?php 
$i = 0;
foreach( $menu_datas as $row ){
?>
                    <li class="gnb_al_li">
    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" class="gnb_al_a"><?php echo $row['me_name'] ?></a>
    <?php
                        $k = 0;
    foreach( (array) $row['sub'] as $row2 ){
        if($k == 0)
            echo '<ul>'.PHP_EOL;
    ?>
                            <li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a></li>
    <?php
                        $k++;
    }   //end foreach $row2

    if($k > 0)
        echo '</ul>'.PHP_EOL;
    ?>
                    </li>
<?php
                    $i++;
}
//end foreach $row
if ($i == 0) {  ?>
                        <li class="gnb_empty">Menu is being prepared.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">Admin Mode &gt; Settings &gt; Menu Settings</a> can be set here.<?php } ?></li>
<?php }
?>
                </ul>
                <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div id="gnb_all_bg"></div>
        </div>
    </nav>
    <script>
    
    $(function(){
        $(".gnb_menu_btn").click(function(){
            $("#gnb_all, #gnb_all_bg").show();
        });
        $(".gnb_close_btn, #gnb_all_bg").click(function(){
            $("#gnb_all, #gnb_all_bg").hide();
        });
    });

    </script>
</div>
<!-- } Header End -->


<hr>

<!-- Content Start { -->
<div id="wrapper">
    <div id="container_wr">
   
    <div id="container">
        <?php 
if (!defined("_INDEX_")) { ?><h2 id="container_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2><?php }
