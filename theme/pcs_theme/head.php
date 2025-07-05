<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/head.php');
    return;
}

include_once(G5_THEME_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');
include_once(G5_THEME_PATH . '/pcs_lib/pcs_core/pcs_config.php');
include_once(PCS_LIB . '/pcs_common_function.php');
?>

<!-- Header Start { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">Skip to main content</a></div>

    <?php
    if (defined('_INDEX_')) { // Execute only on index
        include G5_BBS_PATH . '/newwin.inc.php'; // Popup layer
    }
    ?>
    <div id="hd_wrapper">

        <div id="logo">
            <a href="<?php echo G5_URL ?>"><img src="<?php echo PCS_CORE_URL ?>/pcs_logo.png"
                                                alt="<?php echo $config['cf_title']; ?>"></a>
        </div>

    </div>
    <?php if ($member['mb_1']) { ?>
        <nav id="gnb">
            <h2>Main Menu</h2>
            <div class="gnb_wrap">
                <ul id="gnb_1dul">
                    <li class="gnb_1dli gnb_mnal">
                        <button type="button" class="gnb_menu_btn" title="All Menu"><i class="fa fa-bars"
                                                                                       aria-hidden="true"></i><span
                                    class="sound_only">Open all menu</span></button>
                    </li>
                    <?php
                    $menu_datas = get_menu_db(0, true);
                    $gnb_zindex = 999; // For setting gnb_1dli z-index value
                    $i = 0;
                    foreach ($menu_datas as $row) {
                        if ($i < ($member['mb_1'] + 3)) {
                            if (empty($row)) continue;
                            $add_class = (isset($row['sub']) && $row['sub']) ? 'gnb_al_li_plus' : '';
                            ?>
                        <li class="gnb_1dli <?php echo $add_class; ?>" style="z-index:<?php echo $gnb_zindex--; ?>">
                            <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>"
                               class="gnb_1da"><?php echo $row['me_name'] ?></a>
                            <?php
                            $k = 0;
                            foreach ((array)$row['sub'] as $row2) {

                                if (empty($row2)) continue;

                                if ($k == 0)
                                    echo '<span class="bg">Subcategory</span><div class="gnb_2dul"><ul class="gnb_2dul_box">' . PHP_EOL;
                                ?>
                                <li class="gnb_2dli"><a
                                            href="<?php echo str_replace('http://127.0.0.1/demo', G5_URL, $row2['me_link']); ?>"
                                            target="_<?php echo $row2['me_target']; ?>"
                                            class="gnb_2da"><?php echo $row2['me_name'] ?></a></li>
                                <?php
                                $k++;
                            }   //end foreach $row2

                            if ($k > 0)
                                echo '</ul></div>' . PHP_EOL;
                            ?>
                            </li>
                            <?php
                            $i++;
                        }
                    }   //end foreach $row

                    if ($i == 0) { ?>
                        <li class="gnb_empty">Menu is being prepared.<?php if ($is_admin) { ?> <a
                                    href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">Admin Mode &gt; Settings &gt; Menu
                                Settings</a> can be set here.<?php } ?></li>
                    <?php } ?>
                </ul>
                <div id="gnb_all">
                    <h2>All Menu</h2>
                    <ul class="gnb_al_ul">
                        <?php

                        $i = 0;
                        foreach ($menu_datas as $row) {
                            if ($i < ($member['mb_1'] + 3)) {
                                ?>
                                <li class="gnb_al_li">
                                <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>"
                                   class="gnb_al_a"><?php echo $row['me_name'] ?></a>
                                <?php
                                $k = 0;
                                foreach ((array)$row['sub'] as $row2) {
                                    if ($k == 0)
                                        echo '<ul>' . PHP_EOL;
                                    ?>
                                    <li>
                                        <a href="<?php echo str_replace('http://127.0.0.1/demo', G5_URL, $row2['me_link']); ?>"
                                           target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a>
                                    </li>
                                    <?php
                                    $k++;
                                }   // end foreach $row2

                                if ($k > 0)
                                    echo '</ul>' . PHP_EOL;
                                ?>
                                </li>
                                <?php
                                $i++;
                            }
                        }   //end foreach $row

                        if ($i == 0) { ?>
                            <li class="gnb_empty">Menu is being prepared.<?php if ($is_admin) { ?> <br><a
                                        href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">Admin Mode &gt; Settings &gt;
                                    Menu Settings</a> can be set here.<?php } ?></li>
                        <?php } ?>
                    </ul>
                    <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div id="gnb_all_bg"></div>
            </div>
        </nav>
    <?php } ?>
    <script>

        $(function () {
            $(".gnb_menu_btn").click(function () {
                $("#gnb_all, #gnb_all_bg").show();
            });
            $(".gnb_close_btn, #gnb_all_bg").click(function () {
                $("#gnb_all, #gnb_all_bg").hide();
            });
        });

    </script>
</div>
<!-- } End Header -->


<hr>

<!-- Content Start { -->
<div id="wrapper">
    <div id="container_wr">

        <div id="container">
            <?php if (!defined("_INDEX_")) { ?><?php } ?>
