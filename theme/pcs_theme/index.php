<?php
//error_reporting(E_ALL);ini_set("display_errors", 1);
error_reporting(0);


if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/index.php');
    return;
}

include_once(G5_THEME_PATH . '/head.php');
?>

    <h2 class="sound_only">Latest Posts</h2>

    <div class="latest_wr">
        <!-- Latest Posts Start { -->
        <div>
            <?php
            if (!$is_member) {
                echo "<p align='center'> <font color = red size = 5> <strong> Login is required. </strong></font></p>";
            } elseif (!$member['mb_1']) {
                echo "<p align='center'> <font color = red size = 5> <strong> Permission is required.</strong></font></p>";
            }
            ?>
            <!-- } End Latest Posts -->
        </div>
    </div>

<?php
include_once(G5_THEME_PATH . '/tail.php');
?>