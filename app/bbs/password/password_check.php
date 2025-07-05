<?php

include_once(__DIR__ . '/../common.php');


if ($w == 's') {
    $qstr = 'bo_table='.$bo_table.'&amp;sfl='.$sfl.'&amp;stx='.$stx.'&amp;sop='.$sop.'&amp;wr_id='.$wr_id.'&amp;page='.$page;
    $wr = get_write($write_table, $wr_id);
    if (!$wr['wr_password'] && $wr['mb_id'] && $mb = get_member($wr['mb_id'])) {
        $wr['wr_password'] = $mb['mb_password'];
    }
    if (!check_password($wr_password, $wr['wr_password'])) {
        run_event('password_is_wrong', 'bbs', $wr, $qstr);
        alert('The password is incorrect.');
    }
    // Store the information below in the session. This is because sub-numbers must be viewed without a password.
    //$ss_name = 'ss_secret.'_'.$bo_table.'_'.$wr_id';
    $ss_name = 'ss_secret_'.$bo_table.'_'.$wr['wr_num'];
    //set_session("ss_secret", "$bo_table|$wr[wr_num]");
    set_session($ss_name, true);
} elseif ($w == 'sc') {
    $qstr = 'bo_table='.$bo_table.'&amp;sfl='.$sfl.'&amp;stx='.$stx.'&amp;sop='.$sop.'&amp;wr_id='.$wr_id.'&amp;page='.$page;
    $wr = get_write($write_table, $wr_id);
    if (!$wr['wr_password'] && $wr['mb_id'] && $mb = get_member($wr['mb_id'])) {
        $wr['wr_password'] = $mb['mb_password'];
    }
    if (!check_password($wr_password, $wr['wr_password'])) {
        run_event('password_is_wrong', 'bbs', $wr, $qstr);
        alert('The password is incorrect.');
    }
    // Store the information below in the session. This is because sub-numbers must be viewed without a password.
    $ss_name = 'ss_secret_comment_'.$bo_table.'_'.$wr['wr_id'];
    //set_session("ss_secret", "$bo_table|$wr[wr_num]");
    set_session($ss_name, true);
} else {
    alert('The value of w was not passed correctly.');
}

goto_url(short_url_clean(G5_HTTP_BBS_URL.'/board.php?'.$qstr));