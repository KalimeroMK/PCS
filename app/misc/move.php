<?php

include_once(__DIR__ . '/../common.php');


$sw = isset($_REQUEST['sw']) ? clean_xss_tags($_REQUEST['sw'], 1, 1) : '';

if ($sw === 'move') {
    $act = 'Move';
} elseif ($sw === 'copy') {
    $act = 'Copy';
} else {
    alert('The value of sw was not passed correctly.');
}

// Only board, group, or super admins can copy or move
if ($is_admin != 'board' && $is_admin != 'group' && $is_admin != 'super') {
    alert_close("Only board, group, or super admins can access this feature.");
}

$g5['title'] = 'Post ' . $act;
include_once(G5_PATH . '/head.sub.php');

$wr_id_list = '';
if ($wr_id) {
    $wr_id_list = $wr_id;
} else {
    $comma = '';

    $count_chk_wr_id = (isset($_POST['chk_wr_id']) && is_array($_POST['chk_wr_id'])) ? count($_POST['chk_wr_id']) : 0;

    for ($i = 0; $i < $count_chk_wr_id; $i++) {
        $wr_id_val = isset($_POST['chk_wr_id'][$i]) ? preg_replace('/[^0-9]/', '', $_POST['chk_wr_id'][$i]) : 0;
        $wr_id_list .= $comma . $wr_id_val;
        $comma = ',';
    }
}

//$sql = " select * from {$g5['board_table']} a, {$g5['group_table']} b where a.gr_id = b.gr_id and bo_table <> '$bo_table' ";
// Allow selecting the original board.
$sql = " select * from {$g5['board_table']} a, {$g5['group_table']} b where a.gr_id = b.gr_id ";
if ($is_admin == 'group') {
    $sql .= " and b.gr_admin = '{$member['mb_id']}' ";
} elseif ($is_admin == 'board') {
    $sql .= " and a.bo_admin = '{$member['mb_id']}' ";
}
$sql .= " order by a.gr_id, a.bo_order, a.bo_table ";
$result = sql_query($sql);

$list = [];

for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list[$i] = $row;
}
?>

    <div id="copymove" class="new_win">
        <h1 id="win_title"><?php
            echo $g5['title'] ?></h1>
        <form name="fboardmoveall" method="post" action="./move_update.php"
              onsubmit="return fboardmoveall_submit(this);">
            <input type="hidden" name="sw" value="<?php
            echo $sw ?>">
            <input type="hidden" name="bo_table" value="<?php
            echo $bo_table ?>">
            <input type="hidden" name="wr_id_list" value="<?php
            echo $wr_id_list ?>">
            <input type="hidden" name="sfl" value="<?php
            echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php
            echo $stx ?>">
            <input type="hidden" name="spt" value="<?php
            echo $spt ?>">
            <input type="hidden" name="sst" value="<?php
            echo $sst ?>">
            <input type="hidden" name="sod" value="<?php
            echo $sod ?>">
            <input type="hidden" name="page" value="<?php
            echo $page ?>">
            <input type="hidden" name="act" value="<?php
            echo $act ?>">
            <input type="hidden" name="url" value="<?php
            echo get_text(clean_xss_tags($_SERVER['HTTP_REFERER'])); ?>">

            <div class="tbl_head01 tbl_wrap">
                <table>
                    <caption><?php
                        echo $act ?> the post to one of the following boards.
                    </caption>
                    <thead>
                    <tr>
                        <th scope="col">
                            <label for="chkall" class="sound_only">Select all boards on this page</label>
                            <input type="checkbox" id="chkall"
                                   onclick="if (this.checked) all_checked(true); else all_checked(false);">
                        </th>
                        <th scope="col">Board</th>
                    </tr>
                    </thead>
                    <tbody>

                    $counter = count($list);<?php
                    for ($i = 0; $i < $counter; $i++) {
                        $atc_mark = '';
                        $atc_bg = '';
                        if ($list[$i]['bo_table'] == $bo_table) { // The post is currently in this board
                            $atc_mark = '<span class="copymove_current">Current board</span>';
                            $atc_bg = 'copymove_currentbg';
                        }
                        ?>
                        <tr class="<?php
                        echo $atc_bg; ?>">
                            <td class="td_chk">
                                <label for="chk<?php
                                echo $i ?>" class="sound_only"><?php
                                    echo $list[$i]['bo_table'] ?></label>
                                <input type="checkbox" value="<?php
                                echo $list[$i]['bo_table'] ?>" id="chk<?php
                                echo $i ?>" name="chk_bo_table[]">
                            </td>
                            <td>
                                <label for="chk<?php
                                echo $i ?>">
                                    <?php
                                    echo $list[$i]['gr_subject'] . ' &gt; ';
                                    $save_gr_subject = $list[$i]['gr_subject'];
                                    ?>
                                    <?php
                                    echo $list[$i]['bo_subject'] ?> (<?php
                                    echo $list[$i]['bo_table'] ?>)
                                    <?php
                                    echo $atc_mark; ?>
                                </label>
                            </td>
                        </tr>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>

            <div class="win_btn">
                <input type="submit" value="<?php
                echo $act ?>" id="btn_submit" class="btn_submit">
            </div>
        </form>

    </div>

    <script>
        $(function () {
            $(".win_btn").append("<button type=\"button\" class=\"btn_cancel btn_close\">Close window</button>");

            $(".win_btn button").click(function () {
                window.close();
            });
        });

        function all_checked(sw) {
            var f = document.fboardmoveall;

            for (var i = 0; i < f.length; i++) {
                if (f.elements[i].name == "chk_bo_table[]")
                    f.elements[i].checked = sw;
            }
        }

        function fboardmoveall_submit(f) {
            var check = false;

            if (typeof (f.elements['chk_bo_table[]']) == 'undefined')
                ;
            else {
                if (typeof (f.elements['chk_bo_table[]'].length) == 'undefined') {
                    if (f.elements['chk_bo_table[]'].checked)
                        check = true;
                } else {
                    for (i = 0; i < f.elements['chk_bo_table[]'].length; i++) {
                        if (f.elements['chk_bo_table[]'][i].checked) {
                            check = true;
                            break;
                        }
                    }
                }
            }

            if (!check) {
                alert('Please select at least one board to ' + f.act.value + ' the post.');
                return false;
            }

            document.getElementById('btn_submit').disabled = true;

            f.action = './move_update.php';
            return true;
        }
    </script>

<?php
run_event('move_html_footer');
include_once(G5_PATH . '/tail.sub.php');
