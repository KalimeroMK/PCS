<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Registration Result start { -->
<div id="reg_result" class="register">
    <p class="reg_result_p">
    	<i class="fa fa-gift" aria-hidden="true"></i><br>
        <strong><?php echo get_text($mb['mb_name']); ?></strong> Congratulations on your registration.
    </p>

    <?php if (is_use_email_certify()) {  ?>
    <p class="result_txt">
        A verification email has been sent to the email address you entered during registration.<br>
        After checking the verification email, please complete the verification process to use the site normally.
    </p>
    <div id="result_email">
        <span>ID</span>
        <strong><?php echo $mb['mb_id'] ?></strong><br>
        <span>Email address</span>
        <strong><?php echo $mb['mb_email'] ?></strong>
    </div>
    <p>
        If you entered your email address incorrectly, please contact the site administrator.
    </p>
    <?php }  ?>

    <p class="result_txt">
        Your password is stored as an encrypted code that nobody can know, so you can rest assured.<br>
        If you forget your ID or password, you can find it using the email address you provided during registration.
    </p>

    <p class="result_txt">
        You can withdraw your membership at any time, and after a certain period, your information will be deleted.<br>
        Thank you.
    </p>
</div>
<!-- } Registration Result end -->
<div class="btn_confirm_reg">
	<a href="<?php echo G5_URL ?>/" class="reg_btn_submit">Go to Main</a>
</div>