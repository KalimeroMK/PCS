<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed

if(!$is_admin && $group['gr_device'] == 'pc')
    alert($group['gr_subject'].' 그룹은 PC에서만 접근할 수 있습니다.');

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>

<!-- 메인화면 최신글 시작 -->
<?php
//  Latest posts
$sql = " select bo_table, bo_subject
            from {$g5['board_table']}
            where gr_id = '{$gr_id}'
              and bo_list_level <= '{$member['mb_level']}'
              and bo_device <> 'pc' ";
if(!$is_admin)
    $sql .= " and bo_use_cert = '' ";
$sql .= " order by bo_order ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    // This function extracts the latest posts.
    // If no skin is specified, the default skin path from admin > settings will be used.

    // Usage
    // latest(skin, board ID, output lines, character count);
    echo latest('theme/basic', $row['bo_table'], 5, 70);
}
?>
<!-- 메인화면 최신글 끝 -->

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
?>
