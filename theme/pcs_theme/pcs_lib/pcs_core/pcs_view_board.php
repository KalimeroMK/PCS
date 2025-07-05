<?php
if ($member['mb_id']) {

    if ($board['bo_table'] == 'equipment') {
        include_once(PCS_LIB . '/pcs_info_eq.php');
    }

    if ($board['bo_table'] == 'spool') {
        include_once(PCS_LIB . '/pcs_info_spool.php');
    }

    if ($board['bo_table'] == 'iso') {
        include_once(PCS_LIB . '/pcs_info_iso.php');
    }

    if ($board['bo_table'] == 'pnid') {
        include_once(PCS_LIB . '/pcs_info_pnid.php');
    }

    if ($board['bo_table'] == 'plan') {
        include_once(PCS_LIB . '/pcs_info_plan.php');
    }

    if ($board['bo_table'] == 'tp') {
        include_once(PCS_LIB . '/pcs_info_tp.php');
    }

    if ($board['bo_table'] == 'inspection') {
        include_once(PCS_LIB . '/pcs_info_insp.php');
    }

    if ($board['bo_table'] == 'daily') {
        switch ($_POST['issue_mode']) {
            case 'issue'    :
                include_once(PCS_LIB . '/pcs_daily_update.php');
                break;
            default        :
                include_once(PCS_LIB . '/pcs_info_daily.php');
                break;
        }
    }

    if ($board['bo_table'] == 'package') {
        switch ($_POST['p_page'] ?? '') {
            case 'p_list'    :
                include_once(PCS_LIB . '/pcs_pkg_punch_list.php');
                break;
            case 'p_cont'    :
                include_once(PCS_LIB . '/pcs_pkg_punch_control.php');
                break;
            case 'p_upda'    :
                include_once(PCS_LIB . '/pcs_pkg_punch_update.php');
                break;
            case 'm_list'    :
                include_once(PCS_LIB . '/pcs_punchList.php');
                break;
            default        :
                include_once(PCS_LIB . '/pcs_info_pkg.php');
                break;
        }
    }

    if ($board['bo_table'] == 'status' && $member['mb_1'] > 2 && !G5_IS_MOBILE) {
        switch ($wr_id) {
            case 1 :
                include_once(PCS_LIB . '/pcs_mg_01.php');
                break;
            case 2 :
                include_once(PCS_LIB . '/pcs_mg_02.php');
                break;
            case 3 :
                include_once(PCS_LIB . '/pcs_mg_03.php');
                break;
            case 4 :
                include_once(PCS_LIB . '/pcs_mg_04.php');
                break;
            case 5 :
                include_once(PCS_LIB . '/pcs_mg_05.php');
                break;
            case 6 :
                include_once(PCS_LIB . '/pcs_mg_06.php');
                break;
            case 7 :
                include_once(PCS_LIB . '/pcs_mg_07.php');
                break;
            default :
                break;
        }
    }

    if ($board['bo_table'] == 'admin' && $member['mb_1'] > 3) {
        if (!empty($_GET['rlt'])) {
            include_once(PCS_LIB . '/pcs_DB_result.php');
        } else {
            switch ($wr_id) {
                case 1  :
                    include_once(PCS_LIB . '/pcs_admin_01.php');
                    break;
//				case 3  :	include_once (PCS_LIB.'/pcs_admin_02.php');		break;
                case 13 :
                    include_once(PCS_LIB . '/pcs_admin_04.php');
                    break;
                case 14 :
                    include_once(PCS_LIB . '/pcs_admin_05.php');
                    break;
                default :
                    include_once(PCS_LIB . '/pcs_admin_03.php');
                    break;
            }
        }
    }


}
