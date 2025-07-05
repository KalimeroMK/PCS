<?php

if (!defined('_GNUBOARD_')) exit;
// Individual page access not allowed
if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/tail.php');
    return;
}
if (G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH . '/shop.tail.php');
    return;
}
?>

    </div>
    <div id="aside">
        <?php
        echo outlogin('theme/basic');
        // External login, to use the theme's skin specify as theme/basic
        ?>
        <?php
        echo poll('theme/basic');
        // Poll, to use the theme's skin specify as theme/basic
        ?>
    </div>
    </div>

    </div>
    <!-- } End Content -->

    <hr>

    <!-- Footer Start { -->
    <div id="ft">

        <div id="ft_wr">
            <div id="ft_link" class="ft_cnt">
                <a href="<?php
                echo get_pretty_url('content', 'company');
                ?>">About Us</a>
                <a href="<?php
                echo get_pretty_url('content', 'privacy');
                ?>">Privacy Policy</a>
                <a href="<?php
                echo get_pretty_url('content', 'provision');
                ?>">Terms of Service</a>
                <a href="<?php
                echo get_device_change_url();
                ?>">Mobile Version</a>
            </div>
            <div id="ft_company" class="ft_cnt">
                <h2>Site Information</h2>
                <p class="ft_info">
                    Company Name: Company Name / CEO: CEO Name<br>
                    Address: 123-45, OO-dong, OO-gu, OO-si, OO-do<br>
                    Business Registration Number: 123-45-67890<br>
                    Tel: 02-123-4567 Fax: 02-123-4568<br>
                    E-commerce Permit Number: OO-gu - 123<br>
                    Personal Information Manager: Manager Name<br>
                </p>
            </div>
            <?php
            // Notice
            // This function extracts the latest posts.
            // Usage: latest(skin, board_id, lines, subject_length);
            // To use the theme's skin, specify as theme/basic
            echo latest('theme/notice', 'notice', 4, 13);
            ?>

            <?php
            echo visit('theme/basic');
            // Visitor statistics, to use the theme's skin specify as theme/basic
            ?>
        </div>
        <!-- <div id="ft_catch"><img src="<?php
        echo G5_IMG_URL;
        ?>/ft_logo.png" alt="<?php
        echo G5_VERSION ?>
?>"></div> -->
        <div id="ft_copy">Copyright &copy; <b>Your Domain.</b> All rights reserved.</div>


        <button type="button" id="top_btn">
            <i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">Go to top</span>
        </button>
        <script>
            $(function () {
                $("#top_btn").on("click", function () {
                    $("html, body").animate({scrollTop: 0}, '500');
                    return false;
                });
            });
        </script>
    </div>

<?php
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

    <!-- } End Footer -->

    <script>
        $(function () {
            // If font resize cookies exist, execute
            font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
        });
    </script>

<?php
include_once(G5_THEME_PATH . "/tail.sub.php");
