<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $outlogin_skin_url . '/style.css">', 0);
?>

<!-- 로그인 전 아웃로그인 시작 { -->
<section id="ol_before" class="ol">
    <h2>회원로그인</h2>
    <form name="foutlogin" action="<?php echo $outlogin_action_url ?>" onsubmit="return fhead_submit(this);"
          method="post" autocomplete="off">
        <fieldset>
            <input type="hidden" name="url" value="<?php echo $outlogin_url ?>">
            <label for="ol_id" id="ol_idlabel" class="sound_only">회원아이디<strong>필수</strong></label>
            <input type="text" id="ol_id" name="mb_id" required class="required frm_input" maxlength="20"
                   placeholder="아이디">
            <label for="ol_pw" id="ol_pwlabel" class="sound_only">비밀번호<strong>필수</strong></label>
            <input type="password" name="mb_password" id="ol_pw" required class="required frm_input" maxlength="20"
                   placeholder="비밀번호">
            <div id="ol_auto">
                <input type="checkbox" name="auto_login" value="1" id="auto_login">
                <label for="auto_login" id="auto_login_label">자동로그인</label>
            </div>
            <input type="submit" id="ol_submit" value="로그인" class="btn_b02">
            <div id="ol_svc">
                <a href="<?php echo G5_BBS_URL ?>/register.php"><b>회원가입</b></a> /
                <a href="<?php echo G5_BBS_URL ?>/password_lost.php">ID/PW 찾기</a>
            </div>

        </fieldset>
    </form>
</section>

<script>
    $omi = $('#ol_id');
    $omp = $('#ol_pw');
    $omi_label = $('#ol_idlabel');
    $omi_label.addClass('ol_idlabel');
    $omp_label = $('#ol_pwlabel');
    $omp_label.addClass('ol_pwlabel');

    $(function () {

        $("#auto_login").click(function () {
            if ($(this).is(":checked")) {
                if (!confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?"))
                    return false;
            }
        });
    });

    function fhead_submit(f) {
        return true;
    }
</script>
<!-- } 로그인 전 아웃로그인 끝 -->
