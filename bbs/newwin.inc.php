<?php

if (!defined("_GNUBOARD_")) {
    exit;
} // Individual page access not allowed

$pop_division = defined('_SHOP_') ? 'shop' : 'comm';

$sql = " select * from {$g5['new_win_table']}
          where '".G5_TIME_YMDHIS."' between nw_begin_time and nw_end_time
            and nw_device IN ( 'both', 'pc' ) and nw_division IN ( 'both', '".$pop_division."' )
          order by nw_id asc ";
$result = sql_query($sql, false);
?>

<!-- Popup Layer Start { -->
<div id="hd_pop">
    <h2>Popup Layer Notification</h2>

    <?php
    for ($i = 0; $nw = sql_fetch_array($result); $i++) {
        // If already checked, continue
        if (isset($_COOKIE["hd_pops_{$nw['nw_id']}"]) && $_COOKIE["hd_pops_{$nw['nw_id']}"]) {
            continue;
        }
        ?>

        <div id="hd_pops_<?php
        echo $nw['nw_id'] ?>" class="hd_pops" style="top:<?php
        echo $nw['nw_top'] ?>px;left:<?php
        echo $nw['nw_left'] ?>px">
            <div class="hd_pops_con" style="width:<?php
            echo $nw['nw_width'] ?>px;height:<?php
            echo $nw['nw_height'] ?>px">
                <?php
                echo conv_content($nw['nw_content'], 1); ?>
            </div>
            <div class="hd_pops_footer">
                <button class="hd_pops_reject hd_pops_<?php
                echo $nw['nw_id']; ?> <?php
                echo $nw['nw_disable_hours']; ?>"><strong><?php
                        echo $nw['nw_disable_hours']; ?></strong> hours - Do not show again during this period.
                </button>
                <button class="hd_pops_close hd_pops_<?php
                echo $nw['nw_id']; ?>">Close <i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
    <?php
    }
    if ($i == 0) {
        echo '<span class="sound_only">No popup notifications.</span>';
    }
    ?>
</div>

<script>
    $(function () {
        $(".hd_pops_reject").click(function () {
            var id = $(this).attr('class').split(' ');
            var ck_name = id[1];
            var exp_time = parseInt(id[2]);
            $("#" + id[1]).css("display", "none");
            set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
        });
        $('.hd_pops_close').click(function () {
            var idb = $(this).attr('class').split(' ');
            $('#' + idb[1]).css('display', 'none');
        });
        $("#hd").css("z-index", 1000);
    });
</script>
<!-- } Popup Layer End -->