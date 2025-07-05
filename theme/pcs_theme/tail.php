<?php

if (!defined('_GNUBOARD_')) exit;
// Individual page access not allowed
if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/tail.php');
    return;
}
?>

    </div>
    <div id="aside">
        <?php
        echo outlogin('theme/basic');
        // External login. To use the theme's skin, specify as theme/basic
        ?>

        <?php
        echo poll('theme/basic');
        // Poll. To use the theme's skin, specify as theme/basic
        ?>
    </div>
    </div>

    </div>
    <!-- } Content End -->

    <hr>

    <!-- Footer Start { -->
    <div id="ft">

        <div id="ft_wr">
            <div id="ft_link" class="ft_cnt">
                <a href="<?php
                echo get_pretty_url('content', 'company');
                ?>">About Us</a>
            </div>
            <div id="ft_link" class="ft_cnt">
                <a href="<?php
                echo get_pretty_url('content', 'privacy');
                ?>">Privacy Policy</a>
            </div>
            <div id="ft_link" class="ft_cnt">
                <a href="<?php
                echo get_pretty_url('content', 'provision');
                ?>">Terms of Service</a>
            </div>
            <div id="ft_link" class="ft_cnt">
                <a href="<?php
                echo get_device_change_url();
                ?>">Mobile Version</a>
            </div>
        </div>
        <!-- <div id="ft_catch"><img src="<?php
        echo G5_IMG_URL;
        ?>/ft_logo.png" alt="<?php
        echo G5_VERSION ?>
?>"></div> -->
        <div id="ft_copy">Copyright &copy; <b><a href="https://www.pcsbymd.com" target="_blank">www.pcsbymd.com</a></b>
            All rights reserved.
        </div>


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

    <!-- } Footer End -->

    <script>
        $(function () {
            // Execute if font resize cookie exists
            font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
        });
    </script>

<?php
include_once(G5_THEME_PATH . "/tail.sub.php");
