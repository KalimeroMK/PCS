<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/index.php');
    return;
}

if (G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH . '/index.php');
    return;
}

include_once(G5_THEME_PATH . '/head.php');
?>

    <h2 class="sound_only">Latest Posts</h2>

    <div class="latest_top_wr">
        <?php
        // This function extracts the latest posts.
        // Usage: latest(skin, board_id, lines, subject_length);
        // To use the theme's skin, specify as theme/basic
        echo latest('theme/pic_list', 'free', 4, 23);        // Free board automatically created during minimum installation
        echo latest('theme/pic_list', 'qa', 4, 23);            // Q&A board automatically created during minimum installation
        echo latest('theme/pic_list', 'notice', 4, 23);        // Notice board automatically created during minimum installation
        ?>
    </div>
    <div class="latest_wr">
        <!-- Photo Latest Posts 2 { -->
        <?php
        // This function extracts the latest posts.
        // Usage: latest(skin, board_id, lines, subject_length);
        // To use the theme's skin, specify as theme/basic
        echo latest('theme/pic_block', 'gallery', 4, 23);        // Gallery board automatically created during minimum installation
        ?>
        <!-- } End Photo Latest Posts 2 -->
    </div>

    <div class="latest_wr">
        <!-- Latest Posts Start { -->
        <?php
        //  Latest posts
        $sql = " select bo_table
                from `{$g5['board_table']}` a left join `{$g5['group_table']}` b on (a.gr_id=b.gr_id)
                where a.bo_device <> 'mobile' ";
        if (!$is_admin)
            $sql .= " and a.bo_use_cert = '' ";
        $sql .= " and a.bo_table not in ('notice', 'gallery') ";     // Exclude notice and gallery boards
        $sql .= " order by b.gr_order, a.bo_order ";
        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            $lt_style = '';
            if ($i % 3 !== 0) $lt_style = "margin-left:2%";
            ?>
            <div style="float:left;<?php echo $lt_style ?>" class="lt_wr">
                <?php
                // This function extracts the latest posts.
                // Usage: latest(skin, board_id, lines, subject_length);
                // To use the theme's skin, specify as theme/basic
                echo latest('theme/basic', $row['bo_table'], 6, 24);
                ?>
            </div>
            <?php
        }
        ?>
        <!-- } End Latest Posts -->
    </div>

<?php
include_once(G5_THEME_PATH . '/tail.php');