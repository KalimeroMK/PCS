<?php
if (!defined('_GNUBOARD_')) exit;

// 요금제에 따른 port 구분
function get_icode_port_type($id, $pw): int|false
{
    global $config;

    // 토큰키를 사용한다면 true 로 리턴
    if (isset($config['cf_icode_token_key']) && $config['cf_icode_token_key']) {
        return 1;
    }

    $userinfo = get_icode_userinfo($id, $pw);

    if ($userinfo['payment'] == 'A') {
        // 충전제
        return 1;
    } elseif ($userinfo['payment'] == 'C') {
        // 정액제
        return 2;
    } else {
        return false;
    }
}

/**
 * SMS 발송을 관장하는 메인 클래스이다.
 *
 * 접속, 발송, URL발송, 결과등의 실질적으로 쓰이는 모든 부분이 포함되어 있다.
 */
class LMS
{
    public $icode_id;
    public $icode_pw;
    public $socket_host;
    public $socket_port;
    public $socket_portcode;
    public $Data = array();
    public $Result = array();
    public $icode_key;

    // SMS 서버 접속
    function SMS_con($host, $id, $pw, $portcode): void
    {
        global $config;

        // 토큰키를 사용한다면
        if (isset($config['cf_icode_token_key']) && $config['cf_icode_token_key']) {
            $this->icode_key = $config['cf_icode_token_key'];
            $this->socket_host = ICODE_JSON_SOCKET_HOST;
            $this->socket_port = ICODE_JSON_SOCKET_PORT;
        } else {
            $this->socket_host = $host;
        }

        $this->socket_portcode = $portcode;
        $this->icode_id = FillSpace($id, 10);
        $this->icode_pw = FillSpace($pw, 10);
    }

    function Init(): void
    {
        $this->Data = array();    // 발송하기 위한 패킷내용이 배열로 들어간다.
        $this->Result = array();    // 발송결과값이 배열로 들어간다.
    }

    function Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate = "", $nCount): bool
    {
        global $config;

        // 문자 타입별 Port 설정.
        $sendType = strlen($strData) > 90 ? 1 : 0; // 0: SMS / 1: LMS
        $is_use_json = false;

        // 토큰키를 사용한다면
        if (isset($config['cf_icode_token_key']) && $config['cf_icode_token_key'] === $this->icode_key) {
            // 개행치환
            $strData = preg_replace("/\r\n/", "\n", $strData);
            $strData = preg_replace("/\r/", "\n", $strData);
            $checks = array('msg' => $strData, 'subject' => $strSubject);
            $tmps = array();
            foreach ($checks as $k => $v) {

                // 문자 내용이 euc-kr 인지 체크합니다.
                $enc = mb_detect_encoding($v, array('EUC-KR', 'UTF-8'));

                // 문자 내용이 euc-kr 이면 json_encode 에서 깨지기 때문에 utf-8 로 변환합니다.
                $tmps[$k] = ($enc === 'EUC-KR') ? iconv_utf8($v) : $v;
            }
            $strData = $tmps['msg'];
            $strSubject = $tmps['subject'];
            // 문자 타입별 Port 설정.
            $sendType = strlen($strData) > 90 ? 1 : 0;
            // 0: SMS / 1: LMS
            if ($sendType == 0) $strSubject = "";
            $is_use_json = true;
        } elseif ($this->socket_portcode == 1) {
            /* 개발 완료 후 아래 포트를 rand 함수를 이용하는 라인으로 변경 바랍니다.*/
            // 충전식
            if ($sendType && $sendType === 1) {
                //$this->socket_port = 8200;		// LMS
                $this->socket_port = rand(8200, 8201);    // LMS
            } else {
                //$this->socket_port = 6295;		// SMS
                $this->socket_port = rand(6295, 6297);    // SMS
            }
        } elseif ($sendType && $sendType === 1) {
            //$this->socket_port = 8300; //	LMS
            $this->socket_port = rand(8300, 8301);
            //	LMS
        } else {
            //$this->socket_port = 6291; //	SMS
            $this->socket_port = rand(6291, 6293); //	SMS
        }

        $strCallBack = FillSpace($strCallBack, 11);       // 회신번호
        $strDate = FillSpace($strDate, 12);           // 즉시(12byte 공백), 예약전송(YmdHi)

        if ($sendType && $sendType === 1) {
            /** LMS 제목 **/
            /*
            			제목필드의 값이 없을 경우 단말기 기종및 설정에 따라 표기 방법이 다름
            			1.설정에서 제목필드보기 설정 Disable -> 제목필드값을 넣어도 미표기
            			2.설정에서 제목필드보기 설정 Enable  -> 제목을 넣지 않을 경우 제목없음으로 자동표시
            
            			제목의 첫글자에 "<",">", 개행문자가 있을경우 단말기종류 및 통신사에 따라 메세지 전송실패 -> 글자를 체크하거나 취환처리요망
            */
            $strSubject = str_replace("\r\n", " ", $strSubject);
            $strSubject = str_replace("<", "[", $strSubject);
            $strSubject = str_replace(">", "]", $strSubject);
            $strSubject = FillSpace($strSubject, 30);
            $strData = $is_use_json ? CutCharUtf8($strData, G5_ICODE_JSON_MAX_LENGTH) : FillSpace(CutChar($strData, G5_ICODE_LMS_MAX_LENGTH), G5_ICODE_LMS_MAX_LENGTH);
        } elseif (!$strURL) {
            $strData = $is_use_json ? CutCharUtf8($strData, G5_ICODE_JSON_MAX_LENGTH) : FillSpace(CutChar($strData, 90), 90);
            $strCaller = FillSpace($strCaller, 10);
        } else {
            $strURL = FillSpace($strURL, 50);
        }

        $Error = CheckCommonTypeDest($strDest, $nCount);
        is_vaild_callback($strCallBack);
        CheckCommonTypeDate($strDate);

        for ($i = 0; $i < $nCount; $i++) {

            if ($is_use_json) {
                $list = array(
                    "key" => $this->icode_key,
                    "tel" => $strDest[$i],
                    "cb" => $strCallBack,
                    "msg" => $strData,
                    "title" => $strSubject ? $strSubject : "",
                    "date" => $strDate ? $strDate : ""
                );
                $packet = json_encode($list);

                if (!$packet) { // json_encode가 잘못되었으면 보내지 않습니다.
                    continue;
                }
                $this->Data[$i] = '06' . str_pad(strlen($packet), 4, "0", STR_PAD_LEFT) . $packet;
            } else {
                $strDest[$i] = FillSpace($strDest[$i], 11);
                if ($sendType && $sendType === 1) {
                    $this->Data[$i] = '01144 ' . $this->icode_id . $this->icode_pw . $strDest[$i] . $strCallBack . $strSubject . $strDate . $strData;
                } elseif (!$strURL) {
                    $this->Data[$i] = '01144 ' . $this->icode_id . $this->icode_pw . $strDest[$i] . $strCallBack . $strCaller . $strDate . $strData;
                } else {
                    $strData = FillSpace(CheckCallCenter($strURL, $strDest[$i], $strData), 80);
                    $this->Data[$i] = '05173 ' . $this->icode_id . $this->icode_pw . $strDest[$i] . $strCallBack . $strURL . $strDate . $strData;
                }
            }
        }
        return true;
    }


    function Send(): bool
    {
        global $config;

        // 토큰키를 사용한다면
        if (isset($config['cf_icode_token_key']) && $config['cf_icode_token_key'] === $this->icode_key) {
            $fsocket = @fsockopen($this->socket_host, $this->socket_port, $errno, $errstr, 2);
            if (!$fsocket) return false;
            set_time_limit(300);

            foreach ($this->Data as $puts) {
                fwrite($fsocket, $puts);
                $gets = '';
                while (!$gets) {
                    $gets = fgets($fsocket, 32);
                }
                $json = json_decode(substr($puts, 6), true);

                $dest = $json["tel"];
                if (substr($gets, 0, 20) === "0225  00" . FillSpace($dest, 12)) {
                    $this->Result[] = $dest . ":" . substr($gets, 20, 11);

                } else {
                    $this->Result[$dest] = $dest . ":Error(" . substr($gets, 6, 2) . ")";
                    if (substr($gets, 6, 2) >= "80") break;
                }
            }

            fclose($fsocket);
        } else {
            $fsocket = @fsockopen($this->socket_host, $this->socket_port, $errno, $errstr, 2);
            if (!$fsocket) return false;
            set_time_limit(300);

            foreach ($this->Data as $puts) {
                fwrite($fsocket, $puts);
                $gets = '';
                while (!$gets) {
                    $gets = fgets($fsocket, 30);
                }
                $dest = substr($puts, 26, 11);
                if (substr($gets, 0, 19) === "0223  00" . $dest) {
                    $this->Result[] = $dest . ":" . substr($gets, 19, 10);
                } else {
                    $this->Result[$dest] = $dest . ":Error(" . substr($gets, 6, 2) . ")";
                }
            }

            fclose($fsocket);
        }

        $this->Data = array();
        return true;
    }
}

/**
 * 원하는 문자열의 길이를 원하는 길이만큼 공백을 넣어 맞추도록 합니다.
 *
 * @param text    원하는 문자열입니다.
 *                size    원하는 길이입니다.
 * @return            변경된 문자열을 넘깁니다.
 */
function FillSpace(string $text, $size): string
{
    for ($i = 0; $i < $size; $i++) $text .= " ";
    return substr($text, 0, $size);
}

/**
 * 원하는 문자열을 원하는 길에 맞는지 확인해서 조정하는 기능을 합니다.
 *
 * @param word    원하는 문자열입니다.
 *            cut            원하는 길이입니다.
 * @return            변경된 문자열입니다.
 */
function CutChar($word, $cut): string
{
    $word = substr($word, 0, $cut);                                    // 필요한 길이만큼 취함.
    for ($k = $cut - 1; $k > 1; $k--) {
        if (ord(substr($word, $k, 1)) < 128) break;        // 한글값은 160 이상.
    }
    return substr($word, 0, $cut - ($cut - $k + 1) % 2);
}

function CutCharUtf8($word, $cut)
{
    preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $word, $match); // target for BMP

    $m = $match[0];
    $slen = strlen($word); // length of source string
    if ($slen <= $cut) return $word;

    $ret = array();
    $count = 0;
    for ($i = 0; $i < $cut; $i++) {
        $count += (strlen($m[$i]) > 1) ? 2 : 1;
        if ($count > $cut) break;
        $ret[] = $m[$i];
    }

    return implode('', $ret);
}

/**
 * 수신번호의 값이 정확한 값인지 확인합니다.
 *
 * @param strDest    발송번호 배열입니다.
 *                    nCount    배열의 크기입니다.
 * @return                    처리결과입니다.
 */
function CheckCommonTypeDest($strDest, $nCount): ?string
{
    for ($i = 0; $i < $nCount; $i++) {
        $strDest[$i] = preg_replace("/[^0-9]/", "", $strDest[$i]);
        if (!preg_match("/^01\\d{8,9}\$/", $strDest[$i]))
            return "수신번호오류";
    }
    return null;
}

/**
 * 회신번호 유효성 여부조회 *
 * @param string callback    회신번호
 * @return        처리결과입니다
 * 한국인터넷진흥원 권고
 */
function is_vaild_callback($callback): ?string
{

    $_callback = preg_replace('/[^0-9]/', '', $callback);

    if (!preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/", $_callback) &&
        !preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/", $_callback)) {
        return "회신번호오류";
    }

    if (preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/", $_callback)) {
        return "회신번호오류";
    }
    return null;
}


/**
 * 예약날짜의 값이 정확한 값인지 확인합니다.
 *
 * @param string    strDate (예약시간)
 * @return        처리결과입니다
 */
function CheckCommonTypeDate($strDate): string|false|null
{
    $strDate = preg_replace("/[^0-9]/", "", $strDate);
    if ($strDate) {
        if (!checkdate(substr($strDate, 4, 2), substr($strDate, 6, 2), substr($strDate, 0, 4)))
            return "예약날짜오류";
        if (substr($strDate, 8, 2) > 23 || substr($strDate, 10, 2) > 59) return false;
        return "예약날짜오류";
    }
    return null;
}

/**
 * URL콜백용으로 메세지 크기를 수정합니다.
 *
 * @param url        URL 내용입니다.
 *                msg        결과메시지입니다.
 *                desk    문자내용입니다.
 */
function CheckCallCenter($url, $dest, $data)
{
    switch (substr($dest, 0, 3)) {
        case '010':
        case '018':
        case '019': // 20바이트
            return CutChar($data, 20);
        case '011':
        case '016':
        default:
            return CutChar($data, 80);
        case '017': // URL 포함 80바이트
            return CutChar($data, 80 - strlen($url));
    }
}