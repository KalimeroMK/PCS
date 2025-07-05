<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

// Selection options cause cell merging to vary
$colspan = 5;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css">', 0);

if (!$is_member) {
    echo "<p align='center'> <font color = red size = 5> <strong> Login is required. </strong></font></p>";
} elseif (!$member['mb_1']) {
    echo "<p align='center'> <font color = red size = 5> <strong> Permission is required.</strong></font></p>";
} else {
    ?>

    <!-- Board list start { -->
    <div id="bo_list" style="width:<?php echo $width; ?>">

        <!-- Board category start { -->
        <?php if ($is_category) { ?>
            <nav id="bo_cate">
                <h2><?php echo $board['bo_subject'] ?> Category</h2>
                <ul id="bo_cate_ul">
                    <?php echo $category_option ?>
                </ul>
            </nav>
        <?php } ?>
        <!-- } Board category end -->

        <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php"
              onsubmit="return fboardlist_submit(this);" method="post">

            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="sw" value="">


        </form>

        <!-- Board page info and buttons start { -->
        <div id="bo_btn_top">
            <div id="bo_list_total">
                <span>Total <?php echo number_format($total_count) ?> items</span>
                <?php echo $page ?> page
            </div>

            <?php if ($rss_href || $write_href) { ?>
                <ul class="btn_bo_user">
                    <li>
                        <button type="button" class="btn_bo_sch btn_b01 btn" title="Board Search"><i
                                    class="fa fa-search" aria-hidden="true"></i><span
                                    class="sound_only">Board Search</span></button>
                    </li>
                </ul>
            <?php } ?>
        </div>
        <!-- } Board page info and buttons end -->
        <?php include_once(PCS_LIB . '/pcs_list_default.php'); ?>


        <!-- Page -->
        <?php echo $write_pages; ?>
        <!-- Page -->


        <!-- Board search start { -->
        <div class="bo_sch_wrap">
            <fieldset class="bo_sch">
                <h3>Search</h3>
                <form name="fsearch" method="get">
                    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
                    <input type="hidden" name="sca" value="<?php echo $sca ?>">
                    <input type="hidden" name="sop" value="and">
                    <label for="sfl" class="sound_only">Search target</label>
                    <select name="sfl" id="sfl">
                        <?php echo pcs_sfl_select_options($sfl, $board['bo_subject']); ?>
                    </select>
                    <label for="stx" class="sound_only">Search keyword<strong class="sound_only">
                            required</strong></label>
                    <div class="sch_bar">
                        <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx"
                               class="sch_input" size="25" maxlength="40" placeholder="Enter search keyword">
                        <button type="submit" value="Search" class="sch_btn"><i class="fa fa-search"
                                                                                aria-hidden="true"></i><span
                                    class="sound_only">Search</span></button>
                    </div>
                    <button type="button" class="bo_sch_cls" title="Close"><i class="fa fa-times"
                                                                              aria-hidden="true"></i><span
                                class="sound_only">Close</span></button>
                </form>
            </fieldset>
            <div class="bo_sch_bg"></div>
        </div>
        <script>
            jQuery(function ($) {
                // Board search
                $(".btn_bo_sch").on("click", function () {
                    $(".bo_sch_wrap").toggle();
                })
                $('.bo_sch_bg, .bo_sch_cls').click(function () {
                    $('.bo_sch_wrap').hide();
                });
            });
        </script>
        <!-- } Board search end -->
    </div>

<?php } ?>
<!-- } Board list end -->
