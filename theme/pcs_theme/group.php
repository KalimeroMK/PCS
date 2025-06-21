<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/group.php');
    return;
}

if(!$is_admin && $group['gr_device'] == 'mobile')
    alert($group['gr_subject'].' group can only be accessed from mobile devices.');

$g5['title'] = $group['gr_subject'];
include_once(G5_THEME_PATH.'/head.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>

<div class="latest_wr">

<!-- Main Page Latest Posts Start -->
<?php
//  Latest posts
$sql = " select bo_table, bo_subject
            from {$g5['board_table']}
            where gr_id = '{$gr_id}'
              and bo_list_level <= '{$member['mb_level']}'
              and bo_device <> 'mobile' ";
if(!$is_admin)
    $sql .= " and bo_use_cert = '' ";
$sql .= " order by bo_order ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $lt_style = $i % 3 !== 0 ? "margin-left:2%" : "";
    ?>
    <div style="float:left;<?php 
    echo $lt_style ?>
    ?>"  class="lt_wr">
    <?php 
    // This function extracts the latest posts.
    // Usage: latest(skin, board_id, number_of_posts, character_limit);
    echo latest('theme/basic', $row['bo_table'], 6, 25);
    ?>
    </div>
<?php 
}
?>
<!-- Main Page Latest Posts End -->
</div>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>
