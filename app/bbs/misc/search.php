<?php

include_once(__DIR__ . '/../common.php');

// Set the title of the page
$g5['title'] = 'Search Results';
include_once(__DIR__ . '/../head.php');
$search_table = [];
$table_index = 0;
$write_pages = "";
$text_stx = "";
$srows = 0;

// Remove special characters
$stx = strip_tags($stx);
//$stx = preg_replace('/[[:punct:]]/u', '', $stx); // Remove special characters
$stx = get_search_string($stx); // Remove special characters
if ($stx) {
    $stx = preg_replace('/\//', '\/', trim($stx));
    $sop = strtolower($sop);
    if (!$sop || $sop !== 'and' && $sop !== 'or') {
        $sop = 'and';
    } // Operator: and, or
    $srows = isset($_GET['srows']) ? (int)preg_replace('#[^0-9]#', '', $_GET['srows']) : 10;
    if ($srows === 0) {
        $srows = 10;
    } // Number of search rows per page

    $g5_search['tables'] = [];
    $g5_search['read_level'] = [];
    $sql = " select gr_id, bo_table, bo_read_level from {$g5['board_table']} where bo_use_search = 1 and bo_list_level <= '{$member['mb_level']}' ";
    if ($gr_id) {
        $sql .= " and gr_id = '{$gr_id}' ";
    }
    $onetable = isset($onetable) ? preg_replace('/[^a-z0-9_]/i', '', $onetable) : '';
    // Only search one board if specified
    if ($onetable) // Only search one board if specified
    {
        $sql .= " and bo_table = '{$onetable}' ";
    }
    $sql .= " order by bo_order, gr_id, bo_table ";
    $result = sql_query($sql);
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        if ($is_admin != 'super') {
            // Restrict search by group access
            $sql2 = " select gr_use_access, gr_admin from {$g5['group_table']} where gr_id = '{$row['gr_id']}' ";
            $row2 = sql_fetch($sql2);
            // If using group access
            // If there is a group admin and the current member is the group admin, allow
            if ($row2['gr_use_access'] && !($row2['gr_admin'] && $row2['gr_admin'] == $member['mb_id'])) {
                $sql3 = " select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$row['gr_id']}' and mb_id = '{$member['mb_id']}' and mb_id <> '' ";
                $row3 = sql_fetch($sql3);
                if (!$row3['cnt']) {
                    continue;
                }
            }
        }
        $g5_search['tables'][] = $row['bo_table'];
        $g5_search['read_level'][] = $row['bo_read_level'];
    }

    $op1 = '';

    // Split search terms by space. Whitespace only here
    $s = explode(' ', strip_tags($stx));

    if (count($s) > 1) {
        $s = array_slice($s, 0, 2);
        $stx = implode(' ', $s);
    }

    $text_stx = get_text(stripslashes($stx));

    $search_query = 'sfl='.urlencode($sfl).'&amp;stx='.urlencode($stx).'&amp;sop='.$sop;

    // Split search fields by delimiter. + used here
    $field = explode('||', trim($sfl));

    $str = '(';
    $counter = count($s);
    for ($i = 0; $i < $counter; $i++) {
        if (trim($s[$i]) === '') {
            continue;
        }

        $search_str = $s[$i];

        // Popular search terms
        insert_popular($field, $search_str);

        $str .= $op1;
        $str .= "(";

        $op2 = '';
        // Search multiple fields (field1+field2...)
        for ($k = 0; $k < count($field); $k++) {
            $str .= $op2;
            switch ($field[$k]) {
                case 'mb_id' :
                case 'wr_name' :
                    $str .= "$field[$k] = '$s[$i]'";
                    break;
                case 'wr_subject' :
                case 'wr_content' :
                    if (preg_match("/[a-zA-Z]/", $search_str)) {
                        $str .= "INSTR(LOWER({$field[$k]}), LOWER('{$search_str}'))";
                    } else {
                        $str .= "INSTR({$field[$k]}, '{$search_str}')";
                    }
                    break;
                default :
                    $str .= "1=0"; // Always false
                    break;
            }
            $op2 = " or ";
        }
        $str .= ")";

        $op1 = " {$sop} ";
    }
    $str .= ")";

    $sql_search = $str;

    $str_board_list = "";
    $board_count = 0;

    $time1 = get_microtime();

    $total_count = 0;
    $counter = count($g5_search['tables']);
    for ($i = 0; $i < $counter; $i++) {
        $tmp_write_table = $g5['write_prefix'].$g5_search['tables'][$i];

        $sql = " select wr_id from {$tmp_write_table} where {$sql_search} ";
        $result = sql_query($sql, false);
        $row['cnt'] = @sql_num_rows($result);

        $total_count += $row['cnt'];
        if ($row['cnt']) {
            $board_count++;
            $search_table[] = $g5_search['tables'][$i];
            $read_level[] = $g5_search['read_level'][$i];
            $search_table_count[] = $total_count;

            $sql2 = " select bo_subject, bo_mobile_subject from {$g5['board_table']} where bo_table = '{$g5_search['tables'][$i]}' ";
            $row2 = sql_fetch($sql2);
            $sch_class = "";
            $sch_all = "";
            if ($onetable == $g5_search['tables'][$i]) {
                $sch_class = "class=sch_on";
            } else {
                $sch_all = "class=sch_on";
            }
            $str_board_list .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?'.$search_query.'&amp;gr_id='.$gr_id.'&amp;onetable='.$g5_search['tables'][$i].'" '.$sch_class.'><strong>'.((G5_IS_MOBILE && $row2['bo_mobile_subject']) ? $row2['bo_mobile_subject'] : $row2['bo_subject']).'</strong><span class="cnt_cmt">'.$row['cnt'].'</span></a></li>';
        }
    }

    $rows = $srows;
    $total_page = ceil($total_count / $rows);  // Calculate total pages
    if ($page < 1) {
        $page = 1;
    }                                          // If no page, set to first page (1)
    $from_record = ($page - 1) * $rows;
    // Calculate start record
    $counter = count($search_table);        // Calculate start record

    for ($i = 0; $i < $counter; $i++) {
        if ($from_record < $search_table_count[$i]) {
            $table_index = $i;
            $from_record -= $i > 0 ? $search_table_count[$i - 1] : 0;
            break;
        }
    }

    $bo_subject = [];
    $list = [];

    $k = 0;
    $counter = count($search_table);
    for ($idx = $table_index; $idx < $counter; $idx++) {
        $sql = " select bo_subject, bo_mobile_subject from {$g5['board_table']} where bo_table = '{$search_table[$idx]}' ";
        $row = sql_fetch($sql);
        $bo_subject[$idx] = ((G5_IS_MOBILE && $row['bo_mobile_subject']) ? $row['bo_mobile_subject'] : $row['bo_subject']);

        $tmp_write_table = $g5['write_prefix'].$search_table[$idx];

        $sql = " select * from {$tmp_write_table} where {$sql_search} order by wr_id desc limit {$from_record}, {$rows} ";
        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            // Search terms should not link to the board to avoid load
            $list[$idx][$i] = $row;
            $list[$idx][$i]['href'] = get_pretty_url($search_table[$idx], $row['wr_parent']);

            if ($row['wr_is_comment']) {
                $sql2 = " select wr_subject, wr_option from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ";
                $row2 = sql_fetch($sql2);
                //$row['wr_subject'] = $row2['wr_subject'];
                $row['wr_subject'] = get_text($row2['wr_subject']);
            }

            // Private posts are not searchable
            if (strstr($row['wr_option'].(isset($row2['wr_option']) ? $row2['wr_option'] : ''), 'secret')) {
                $row['wr_content'] = '[Private post]';
            }

            $subject = get_text($row['wr_subject']);
            if (strstr($sfl, 'wr_subject')) {
                $subject = search_font($stx, $subject);
            }

            if ($read_level[$idx] <= $member['mb_level']) {
                //$content = cut_str(get_text(strip_tags($row['wr_content'])), 300, "…");
                $content = strip_tags($row['wr_content']);
                $content = get_text($content, 1);
                $content = strip_tags($content);
                $content = str_replace('&nbsp;', '', $content);
                $content = cut_str($content, 300, "…");

                if (strstr($sfl, 'wr_content')) {
                    $content = search_font($stx, $content);
                }
            } else {
                $content = '';
            }

            $list[$idx][$i]['subject'] = $subject;
            $list[$idx][$i]['content'] = $content;
            $list[$idx][$i]['name'] = get_sideview($row['mb_id'],
                get_text(cut_str($row['wr_name'], $config['cf_cut_name'])), $row['wr_email'], $row['wr_homepage']);

            $k++;
            if ($k >= $rows) {
                break;
            }
        }
        sql_free_result($result);

        if ($k >= $rows) {
            break;
        }

        $from_record = 0;
    }

    $write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page,
        $_SERVER['SCRIPT_NAME'].'?'.$search_query.'&amp;gr_id='.$gr_id.'&amp;srows='.$srows.'&amp;onetable='.$onetable.'&amp;page=');
}

// Board group selection
$group_select = '<label for="gr_id" class="sound_only">Select Board Group</label><select name="gr_id" id="gr_id" class="select"><option value="">All Groups';
$sql = " select gr_id, gr_subject from {$g5['group_table']} order by gr_id ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $group_select .= "<option value=\"".$row['gr_id']."\"".get_selected($gr_id,
            $row['gr_id']).">".$row['gr_subject']."</option>";
}
$group_select .= '</select>';

if (!$sfl) {
    $sfl = 'wr_subject';
}
if (!$sop) {
    $sop = 'or';
}

include_once($search_skin_path.'/search.skin.php');

include_once(__DIR__ . '/tail.php');