<?php

include_once(__DIR__ . '/../common.php');


if (defined('G5_THEME_PATH')) {
    $group_file = G5_THEME_PATH.'/group.php';
    if (is_file($group_file)) {
        require_once($group_file);
        return;
    }
    unset($group_file);
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/group.php');
    return;
}

if (!$is_admin && $group['gr_device'] == 'mobile') {
    alert($group['gr_subject'].' group can only be accessed on mobile.');
}

$g5['title'] = $group['gr_subject'];
include_once(__DIR__ . '/../head.php');include_once(G5_LIB_PATH.'/latest.lib.php');
?>

    <div class="latest_wr">

        <!-- Latest posts on main screen start -->
        <?php
        // Latest posts
        $sql = " select bo_table, bo_subject
            from {$g5['board_table']}
            where gr_id = '{$gr_id}'
              and bo_list_level <= '{$member['mb_level']}'
              and bo_device <> 'mobile' ";
        if (!$is_admin) {
            $sql .= " and bo_use_cert = '' ";
        }
        $sql .= " order by bo_order ";
        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            $lt_style = $i % 3 !== 0 ? "margin-left:2%" : "";
            ?>
            <div style="float:left;<?php 
            echo $lt_style ?>
            ?>" class="lt_wr">
                <?php 
            // This function directly outputs the latest posts.
            // Usage: latest(skin, board_id, output_row, subject_length);
            echo latest('basic', $row['bo_table'], 6, 25);
            ?>
            </div>
            <?php 
        }
        ?>
        <!-- Latest posts on main screen end -->
    </div>
<?php
include_once(__DIR__ . '/tail.php');