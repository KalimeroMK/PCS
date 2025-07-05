<?php
if (!defined('_GNUBOARD_')) exit;

// 방문자수 출력
function visit($skin_dir = 'basic'): string|false
{
    global $config, $g5;

    // visit 배열변수에
    // $visit[1] = 오늘
    // $visit[2] = 어제
    // $visit[3] = 최대
    // $visit[4] = 전체
    // 숫자가 들어감
    preg_match("/오늘:(.*),어제:(.*),최대:(.*),전체:(.*)/", $config['cf_visit'], $visit);
    $visit[1] = (int)$visit[1];
    $visit[2] = (int)$visit[2];
    $visit[3] = (int)$visit[3];
    $visit[4] = (int)$visit[4];

    if (preg_match('#^theme/(.+)$#', $skin_dir, $match)) {
        if (G5_IS_MOBILE) {
            $visit_skin_path = G5_THEME_MOBILE_PATH . '/' . G5_SKIN_DIR . '/visit/' . $match[1];
            if (!is_dir($visit_skin_path))
                $visit_skin_path = G5_THEME_PATH . '/' . G5_SKIN_DIR . '/visit/' . $match[1];
            $visit_skin_url = str_replace(G5_PATH, G5_URL, $visit_skin_path);
        } else {
            $visit_skin_path = G5_THEME_PATH . '/' . G5_SKIN_DIR . '/visit/' . $match[1];
            $visit_skin_url = str_replace(G5_PATH, G5_URL, $visit_skin_path);
        }
        $skin_dir = $match[1];
    } elseif (G5_IS_MOBILE) {
        $visit_skin_path = G5_MOBILE_PATH . '/' . G5_SKIN_DIR . '/visit/' . $skin_dir;
        $visit_skin_url = G5_MOBILE_URL . '/' . G5_SKIN_DIR . '/visit/' . $skin_dir;
    } else {
        $visit_skin_path = G5_SKIN_PATH . '/visit/' . $skin_dir;
        $visit_skin_url = G5_SKIN_URL . '/visit/' . $skin_dir;
    }

    ob_start();
    include_once($visit_skin_path . '/visit.skin.php');
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

// get_browser() 함수는 이미 있음
function get_brow($agent): string
{
    $agent = strtolower($agent);

    //echo $agent; echo "<br/>";

    if (preg_match("/msie ([1-9]\\d\\.\\d+)/", $agent, $m)) {
        $s = 'MSIE ' . $m[1];
    } elseif (preg_match("/firefox/", $agent)) {
        $s = "FireFox";
    } elseif (preg_match("/chrome/", $agent)) {
        $s = "Chrome";
    } elseif (preg_match("/x11/", $agent)) {
        $s = "Netscape";
    } elseif (preg_match("/opera/", $agent)) {
        $s = "Opera";
    } elseif (preg_match("/gec/", $agent)) {
        $s = "Gecko";
    } elseif (preg_match("/bot|slurp/", $agent)) {
        $s = "Robot";
    } elseif (preg_match("/internet explorer/", $agent)) {
        $s = "IE";
    } elseif (preg_match("/mozilla/", $agent)) {
        $s = "Mozilla";
    } else {
        $s = "기타";
    }

    return $s;
}

function get_os($agent): string
{
    $agent = strtolower($agent);

    //echo $agent; echo "<br/>";

    if (preg_match("/windows 98/", $agent)) {
        $s = "98";
    } elseif (preg_match("/windows 95/", $agent)) {
        $s = "95";
    } elseif (preg_match("/windows nt 4\\.\\d*/", $agent)) {
        $s = "NT";
    } elseif (preg_match("/windows nt 5\.0/", $agent)) {
        $s = "2000";
    } elseif (preg_match("/windows nt 5\.1/", $agent)) {
        $s = "XP";
    } elseif (preg_match("/windows nt 5\.2/", $agent)) {
        $s = "2003";
    } elseif (preg_match("/windows nt 6\.0/", $agent)) {
        $s = "Vista";
    } elseif (preg_match("/windows nt 6\.1/", $agent)) {
        $s = "Windows7";
    } elseif (preg_match("/windows nt 6\.2/", $agent)) {
        $s = "Windows8";
    } elseif (preg_match("/windows 9x/", $agent)) {
        $s = "ME";
    } elseif (preg_match("/windows ce/", $agent)) {
        $s = "CE";
    } elseif (preg_match("/mac/", $agent)) {
        $s = "MAC";
    } elseif (preg_match("/linux/", $agent)) {
        $s = "Linux";
    } elseif (preg_match("/sunos/", $agent)) {
        $s = "sunOS";
    } elseif (preg_match("/irix/", $agent)) {
        $s = "IRIX";
    } elseif (preg_match("/phone/", $agent)) {
        $s = "Phone";
    } elseif (preg_match("/bot|slurp/", $agent)) {
        $s = "Robot";
    } elseif (preg_match("/internet explorer/", $agent)) {
        $s = "IE";
    } elseif (preg_match("/mozilla/", $agent)) {
        $s = "Mozilla";
    } else {
        $s = "기타";
    }

    return $s;
}