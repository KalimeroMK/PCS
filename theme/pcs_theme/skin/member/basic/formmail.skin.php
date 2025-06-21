<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Form Mail start { -->
<div id="formmail" class="new_win">
    <h1 id="win_title">Send Mail to <?php echo $name ?></h1>

    <form name="fformmail" action="./formmail_send.php" onsubmit="return fformmail_submit(this);" method="post" enctype="multipart/form-data" style="margin:0px;">
    <input type="hidden" name="to" value="<?php echo $email ?>">
    <input type="hidden" name="attach" value="2">
    <?php if ($is_member) { // If member ?>
    <input type="hidden" name="fnick" value="<?php echo get_text($member['mb_nick']) ?>">
    <input type="hidden" name="fmail" value="<?php echo $member['mb_email'] ?>">
    <?php }  ?>

    <div class="form_01 new_win_con">
        <h2 class="sound_only">Write Mail</h2>
        <ul>
            <?php if (!$is_member) {  ?>
            <li>
                <label for="fnick" class="sound_only">Name<strong>Required</strong></label>
                <input type="text" name="fnick" id="fnick" required class="frm_input full_input required" placeholder="Name">
            </li>
            <li>
                <label for="fmail" class="sound_only">E-mail<strong>Required</strong></label>
                <input type="text" name="fmail"  id="fmail" required class="frm_input full_input required" placeholder="E-mail">
            </li>
            <?php }  ?>
            <li>
                <label for="subject" class="sound_only">Subject<strong>Required</strong></label>
                <input type="text" name="subject" id="subject" required class="frm_input full_input required"  placeholder="Subject">
            </li>
            <li class="chk_box">
                <span class="sound_only">Format</span>
                <input type="radio" name="type" value="0" id="type_text" checked>
                <label for="type_text"><span></span>TEXT</label>
                
                <input type="radio" name="type" value="1" id="type_html">
                <label for="type_html"><span></span>HTML</label>
                
                <input type="radio" name="type" value="2" id="type_both">
                <label for="type_both"><span></span>TEXT+HTML</label>
            </li>
            <li>
                <label for="content" class="sound_only">Content<strong>Required</strong></label>
                <textarea name="content" id="content" required class="required"></textarea>
            </li>
            <li class="formmail_flie">
                <div class="file_wr">
                    <label for="file1" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only">Attachment File 1</span></label>
                    <input type="file" name="file1"  id="file1"  class="frm_file full_input">
               </div>
               <div class="frm_info">Attachments may be lost, so please check if the file was attached after sending the mail.</div>   
            </li>
            <li class="formmail_flie">
                <div class="file_wr">
                    <label for="file2" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only">Attachment File 2</span></label>
                    <input type="file" name="file2" id="file2" class="frm_file full_input">
                </div>
            </li>
            <li>
                <span class="sound_only">Anti-spam</span>
                <?php echo captcha_html(); ?>
            </li>
        </ul>
        <div class="win_btn">
        	<button type="submit" id="btn_submit" class="btn_b02 reply_btn">Send Mail</button>
            <button type="button" onclick="window.close();" class="btn_close">Close Window</button>
        </div>
    </div>


    </form>
</div>

<script>
with (document.fformmail) {
    if (typeof fname != "undefined")
        fname.focus();
    else if (typeof subject != "undefined")
        subject.focus();
}

function fformmail_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    if (f.file1.value || f.file2.value) {
        // 4.00.11
        if (!confirm("Sending large attachments may take a long time.\n\nPlease do not close or refresh the window until the mail is sent."))
            return false;
    }

    document.getElementById('btn_submit').disabled = true;

    return true;
}
</script>
<!-- } Form Mail end -->