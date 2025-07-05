<?php

include_once(__DIR__ . '/../common.php');

include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

$g5['title'] = 'Change Email Verification Address';
include_once(__DIR__ . '/../head.php');
$mb_id = isset($_GET['mb_id']) ? substr(clean_xss_tags($_GET['mb_id']), 0, 20) : '';
$sql = " select mb_email, mb_datetime, mb_ip, mb_email_certify, mb_id from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$mb = sql_fetch($sql);

if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
    alert("The member does not exist.", G5_URL);
}

if (substr($mb['mb_email_certify'], 0, 1) != 0) {
    alert("This member has already been email verified.", G5_URL);
}

$ckey = isset($_GET['ckey']) ? trim($_GET['ckey']) : '';
$key = md5($mb['mb_ip'] . $mb['mb_datetime']);

if (!$ckey || $ckey !== $key) {
    alert('Please use the correct method.', G5_URL);
}
?>

    <p class="rg_em_p">If you have not received an email verification, you can change the email address in your member information.</p>

    <form method="post" name="fregister_email" action="<?php
    echo G5_HTTPS_BBS_URL . '/register_email_update.php'; ?>" onsubmit="return fregister_email_submit(this);">
        <input type="hidden" name="mb_id" value="<?php
        echo $mb_id; ?>">

        <div class="tbl_frm01 tbl_frm rg_em">
            <table>
                <caption>Enter site usage information</caption>
                <tr>
                    <th scope="row"><label for="reg_mb_email">E-mail<strong class="sound_only">Required</strong></label></th>
                    <td><input type="text" name="mb_email" id="reg_mb_email" required class="frm_input email required"
                               size="30" maxlength="100" value="<?php
                        echo $mb['mb_email']; ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Anti-spam</th>
                    <td><?php
                        echo captcha_html(); ?></td>
                </tr>
            </table>
        </div>

        <div class="btn_confirm">
            <input type="submit" id="btn_submit" class="btn_submit" value="Change Verification Email">
            <a href="<?php
            echo G5_URL ?>" class="btn_cancel">Cancel</a>
        </div>

    </form>

    <script>
        function fregister_email_submit(f) {
            <?php echo chk_captcha_js();  ?>

            return true;
        }
    </script>
<?php
include_once(__DIR__ . '/tail.php');