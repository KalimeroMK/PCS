<?php

include_once(__DIR__ . '/../common.php');

// If the board table is not specified, display an error message
if (!$board['bo_table']) {
    alert('The specified board does not exist.', G5_URL);
}

check_device($board['bo_device']);

// If the post is a comment, redirect to the parent post
if (isset($write['wr_is_comment']) && $write['wr_is_comment']) {
    goto_url(get_pretty_url($bo_table, $write['wr_parent'], '#c_' . $wr_id));
}

// If the board table is not specified, display an error message
if (!$bo_table) {
    $msg = "The board table value was not passed.\n\nPlease pass the value in the format board.php?bo_table=code.";
    alert($msg);
}

// Set the board title based on the device
$g5['board_title'] = ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']);

// If the post ID is present, read the post
if ((isset($wr_id) && $wr_id) || (isset($wr_seo_title) && $wr_seo_title)) {
    // If there is no post, move to the board list
    if (!isset($write['wr_id'])) {
        $msg = 'The post does not exist.\n\nIt may have been deleted or moved.';
        alert($msg, get_pretty_url($bo_table));
    }

    // Use group access
    if (isset($group['gr_use_access']) && $group['gr_use_access']) {
        if ($is_guest) {
            $msg = "Guests do not have permission to access this board.\n\nIf you are a member, please log in and try again.";
            alert($msg,
                G5_BBS_URL . '/login.php?wr_id=' . $wr_id . $qstr . '&amp;url=' . urlencode(get_pretty_url($bo_table, $wr_id,
                    $qstr)));
        }

        // If not a group admin or higher, check access
        if ($is_admin != "super" && $is_admin != "group") {
            // Group access
            $sql = " select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$board['gr_id']}' and mb_id = '{$member['mb_id']}' ";
            $row = sql_fetch($sql);
            if (!$row['cnt']) {
                alert("You do not have permission to access this post.\n\nPlease contact the administrator for inquiries.", G5_URL);
            }
        }
    }

    // If the member's read level is lower than the board's required read level
    if ($member['mb_level'] < $board['bo_read_level']) {
        if ($is_member) {
            alert('You do not have permission to read this post.', G5_URL);
        } else {
            alert('You do not have permission to read this post.\n\nIf you are a member, please log in and try again.',
                G5_BBS_URL . '/login.php?wr_id=' . $wr_id . $qstr . '&amp;url=' . urlencode(get_pretty_url($bo_table, $wr_id,
                    $qstr)));
        }
    }

    // If using identity verification
    if ($board['bo_use_cert'] != '' && $config['cf_cert_use'] && !$is_admin) {
        // Only members who have been verified can access
        if ($is_guest) {
            alert('Only members who have completed identity verification can read this post.\n\nIf you are a member, please log in and try again.',
                G5_BBS_URL . '/login.php?wr_id=' . $wr_id . $qstr . '&amp;url=' . urlencode(get_pretty_url($bo_table, $wr_id,
                    $qstr)));
        }

        if (strlen($member['mb_dupinfo']) == 64 && $member['mb_certify']) { // If the account is verified and stored as di
            goto_url(G5_BBS_URL . "/member_cert_refresh.php?url=" . urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        if ($board['bo_use_cert'] == 'cert' && !$member['mb_certify']) {
            alert('Only members who have completed identity verification can read this post.\n\nPlease complete identity verification in your member information settings.', G5_URL);
        }

        if ($board['bo_use_cert'] == 'adult' && !$member['mb_adult']) {
            alert('Only adult-verified members can read this post.\n\nIf you are not currently adult-verified, please complete adult verification in your member information settings.', G5_URL);
        }
    }

    // If the post is private, check access
    if (!($write['mb_id'] && $write['mb_id'] === $member['mb_id']) && !$is_admin && strstr($write['wr_option'], "secret")) {
        // If the member is not the author and not an admin, check access
        $is_owner = false;
        if ($write['wr_reply'] && $member['mb_id']) {
            $sql = " select mb_id from {$write_table}
                            where wr_num = '{$write['wr_num']}'
                            and wr_reply = ''
                            and wr_is_comment = 0 ";
            $row = sql_fetch($sql);
            if ($row['mb_id'] === $member['mb_id']) {
                $is_owner = true;
            }
        }
        $ss_name = 'ss_secret_' . $bo_table . '_' . $write['wr_num'];
        if (!$is_owner && !get_session($ss_name)) {
            goto_url(G5_BBS_URL . '/password.php?w=s&amp;bo_table=' . $bo_table . '&amp;wr_id=' . $wr_id . $qstr);
        }
        set_session($ss_name, true);
    }

    // Increment the post view count
    $ss_name = 'ss_view_' . $bo_table . '_' . $wr_id;
    if (!get_session($ss_name)) {
        sql_query(" update {$write_table} set wr_hit = wr_hit + 1 where wr_id = '{$wr_id}' ");

        // If the member is not the author, increment the read point
        if (!($write['mb_id'] && $write['mb_id'] === $member['mb_id'])) {
            if ($is_guest && $board['bo_read_level'] == 1 && $write['wr_ip'] == $_SERVER['REMOTE_ADDR']) {
                // If the member is a guest and the read level is 1, and the IP address matches, do not increment the read point
            } else {
                // If the board has a read point, increment the member's point
                if ($config['cf_use_point'] && $board['bo_read_point'] && $member['mb_point'] + $board['bo_read_point'] < 0) {
                    alert('You do not have enough points to read this post.\n\nPlease earn more points and try again.');
                }

                insert_point($member['mb_id'], $board['bo_read_point'],
                    ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']) . ' ' . $wr_id . ' read',
                    $bo_table, $wr_id, 'read');
            }
        }

        set_session($ss_name, true);
    }

    $g5['title'] = strip_tags(conv_subject($write['wr_subject'], 255)) . " > " . $g5['board_title'];
} else {
    // If the member's level is lower than the board's list level, display an error message
    if ($member['mb_level'] < $board['bo_list_level']) {
        if ($member['mb_id']) {
            alert('You do not have permission to view the list.', G5_URL);
        } else {
            alert('You do not have permission to view the list.\n\nIf you are a member, please log in and try again.',
                G5_BBS_URL . '/login.php?' . $qstr . '&url=' . urlencode(G5_BBS_URL . '/board.php?bo_table=' . $bo_table . ($qstr ? '&amp;' : '')));
        }
    }

    // If using identity verification
    if ($board['bo_use_cert'] != '' && $config['cf_cert_use'] && !$is_admin) {
        // Only members who have been verified can access
        if ($is_guest) {
            alert('Only members who have completed identity verification can view the list.\n\nIf you are a member, please log in and try again.',
                G5_BBS_URL . '/login.php?wr_id=' . $wr_id . $qstr . '&amp;url=' . urlencode(get_pretty_url($bo_table, $wr_id,
                    $qstr)));
        }

        if (strlen($member['mb_dupinfo']) == 64 && $member['mb_certify']) { // If the account is verified and stored as di
            goto_url(G5_BBS_URL . "/member_cert_refresh.php?url=" . urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        if ($board['bo_use_cert'] == 'cert' && !$member['mb_certify']) {
            alert('Only members who have completed identity verification can view the list.\n\nPlease complete identity verification in your member information settings.', G5_URL);
        }

        if ($board['bo_use_cert'] == 'adult' && !$member['mb_adult']) {
            alert('Only adult-verified members can view the list.\n\nIf you are not currently adult-verified, please complete adult verification in your member information settings.', G5_URL);
        }
    }

    // If the page number is not set, set it to 1
    if (!isset($page) || (isset($page) && $page == 0)) {
        $page = 1;
    }

    $g5['title'] = $g5['board_title'] . ' ' . $page . ' page';
}

$is_auth = (bool)$is_admin;

include_once(G5_PATH . '/head.sub.php');

// Set the board width
$width = $board['bo_table_width'];
if ($width <= 100) {
    $width .= '%';
} else {
    $width .= 'px';
}

// IP display settings
$ip = "";
$is_ip_view = $board['bo_use_ip_view'];
if ($is_admin) {
    $is_ip_view = true;
    if ($write && array_key_exists('wr_ip', $write)) {
        $ip = $write['wr_ip'];
    }
} elseif (isset($write['wr_ip'])) {
    // If not an admin, display the IP address with the last 3 digits hidden
    $ip = substr($write['wr_ip'], 0, strlen($write['wr_ip']) - 3) . '***';
}

// Category settings
$is_category = false;
$category_name = '';
if ($board['bo_use_category']) {
    $is_category = true;
    if (array_key_exists('ca_name', $write)) {
        $category_name = $write['ca_name']; // Category name
    }
}

// Recommendation settings
$is_good = false;
if ($board['bo_use_good']) {
    $is_good = true;
}

// Non-recommendation settings
$is_nogood = false;
if ($board['bo_use_nogood']) {
    $is_nogood = true;
}

// Admin link
$admin_href = "";
// If the member is a super admin or group admin, display the admin link
if ($member['mb_id'] && ($is_admin === 'super' || $group['gr_admin'] === $member['mb_id'])) {
    $admin_href = G5_ADMIN_URL . '/board_form.php?w=u&amp;bo_table=' . $bo_table;
}

include_once(G5_PATH . '/head.php');

// If the post ID is present, include the post view file
if (isset($wr_id) && $wr_id) {
    include_once(G5_PATH . '/app/view/view.php');
}

// If the member has permission to view the list, include the list file
//if ($board['bo_use_list_view'] || empty($wr_id))
if ($member['mb_level'] >= $board['bo_list_level'] && $board['bo_use_list_view'] || empty($wr_id)) {
    include_once(G5_PATH . '/app/misc/list.php');
}

include_once(G5_PATH . '/app/board/board_tail.php');

echo "\n<!-- Using skin : " . (G5_IS_MOBILE ? $board['bo_mobile_skin'] : $board['bo_skin']) . " -->\n";

include_once(G5_PATH . '/tail.sub.php');
