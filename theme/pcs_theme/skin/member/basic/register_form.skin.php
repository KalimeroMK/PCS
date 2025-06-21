<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->

<div class="register">
<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
<?php } ?>

	<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="url" value="<?php echo $urlencode ?>">
	<input type="hidden" name="agree" value="<?php echo $agree ?>">
	<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
	<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
	<input type="hidden" name="cert_no" value="">
	<?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
	<?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
	<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
	<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
	<?php }  ?>
	
	<div id="register_form" class="form_01">   
	    <div class="register_form_inner">
	        <h2>Enter Site Information</h2>
	        <ul>
	            <li>
	                <label for="reg_mb_id">
	                	Username<strong class="sound_only">Required</strong>
	                	<button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
					<span class="tooltip">Only letters, numbers, and _ are allowed. Please enter at least 3 characters.</span>
	                </label>
	                <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" placeholder="Username">
	                <span id="msg_mb_id"></span>
	            </li>
	            <li class="half_input left_input margin_input">
	                <label for="reg_mb_password">Password<strong class="sound_only">Required</strong></label>
	                <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="Password">
				</li>
	            <li class="half_input left_input">
	                <label for="reg_mb_password_re">Confirm Password<strong class="sound_only">Required</strong></label>
	                <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input full_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="Confirm Password">
	            </li>
	        </ul>
	    </div>
	
	    <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <h2>Enter Personal Information</h2>
	        <ul>
	            <li>
	                <label for="reg_mb_name">Name<strong class="sound_only">Required</strong></label>
	                <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input full_input <?php echo $required ?> <?php echo $readonly ?>" size="10" placeholder="Name">
	                <?php
	                if($config['cf_cert_use']) {
	                    if($config['cf_cert_ipin'])
	                        echo '<button type="button" id="win_ipin_cert" class="btn_frmline">IPIN Verification</button>'.PHP_EOL;
	                    if($config['cf_cert_hp'])
	                        echo '<button type="button" id="win_hp_cert" class="btn_frmline">Mobile Phone Verification</button>'.PHP_EOL;
	    
	                    echo '<noscript>JavaScript must be enabled for identity verification.</noscript>'.PHP_EOL;
	                }
	                ?>
	                <?php
	                if ($config['cf_cert_use'] && $member['mb_certify']) {
	                    if($member['mb_certify'] == 'ipin')
	                        $mb_cert = 'IPIN';
	                    else
	                        $mb_cert = 'Mobile Phone';
	                ?>
	  
	                <div id="msg_certify">
	                    <strong><?php echo $mb_cert; ?> Verification</strong><?php if ($member['mb_adult']) { ?> and <strong>Adult Verification</strong><?php } ?> Complete
	                </div>
	                <?php } ?>
	                <?php if ($config['cf_cert_use']) { ?>
	                <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
	                <span class="tooltip">After IPIN verification, your name is automatically entered. After mobile phone verification, your name and phone number are automatically entered and cannot be entered manually.</span>
	                <?php } ?>
	            </li>
	            <?php if ($req_nick) {  ?>
	            <li>
	                <label for="reg_mb_nick">
	                	Nickname<strong class="sound_only">Required</strong>
	                	<button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
					<span class="tooltip">Only Korean, English, and numbers without spaces are allowed (at least 2 Korean characters or 4 English characters).<br> If you change your nickname, you cannot change it again for <?php echo (int)$config['cf_nick_modify'] ?> days.</span>
	                </label>
	                
                    <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
                    <input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required class="frm_input required nospace full_input" size="10" maxlength="20" placeholder="Nickname">
                    <span id="msg_mb_nick"></span>	                
	            </li>
	            <?php }  ?>
	
	            <li>
	                <label for="reg_mb_email">E-mail<strong class="sound_only">Required</strong>
	                
	                <?php if ($config['cf_use_email_certify']) {  ?>
	                <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
					<span class="tooltip">
	                    <?php if ($w=='') { echo "You must check the contents sent to your e-mail and verify to complete registration."; }  ?>
	                    <?php if ($w=='u') { echo "If you change your e-mail address, you must verify again."; }  ?>
	                </span>
	                <?php } ?>
					</label>
	                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
	                <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email full_input required" size="70" maxlength="100" placeholder="E-mail">
	            
	            </li>
	
	            <?php if ($config['cf_use_homepage']) {  ?>
	            <li>
	                <label for="reg_mb_homepage">Homepage<?php if ($config['cf_req_homepage']){ ?><strong class="sound_only">Required</strong><?php } ?></label>
	                <input type="text" name="mb_homepage" value="<?php echo get_text($member['mb_homepage']) ?>" id="reg_mb_homepage" <?php echo $config['cf_req_homepage']?"required":""; ?> class="frm_input full_input <?php echo $config['cf_req_homepage']?"required":""; ?>" size="70" maxlength="255" placeholder="Homepage">
	            </li>
	            <?php }  ?>
	
	            <li>
	            <?php if ($config['cf_use_tel']) {  ?>
	            
	                <label for="reg_mb_tel">Phone Number<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">Required</strong><?php } ?></label>
	                <input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> class="frm_input full_input <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" placeholder="Phone Number">
	            <?php }  ?>
	            </li>
				<li>
	            <?php if ($config['cf_use_hp'] || $config['cf_cert_hp']) {  ?>
	                <label for="reg_mb_hp">Mobile Number<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">Required</strong><?php } ?></label>
	                
	                <input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="frm_input full_input <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="20" placeholder="Mobile Number">
	                <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
	                <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
	                <?php } ?>
	            <?php }  ?>
	            </li>
	
	            <?php if ($config['cf_use_addr']) { ?>
	            <li>
	            	<label>Address</label>
					<?php if ($config['cf_req_addr']) { ?><strong class="sound_only">Required</strong><?php }  ?>
	                <label for="reg_mb_zip" class="sound_only">Postal Code<?php echo $config['cf_req_addr']?'<strong class="sound_only"> Required</strong>':''; ?></label>
	                <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input twopart_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6"  placeholder="Postal Code">
	                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">Find Address</button><br>
	                <input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address full_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="50"  placeholder="Basic Address">
	                <label for="reg_mb_addr1" class="sound_only">Basic Address<?php echo $config['cf_req_addr']?'<strong> Required</strong>':''; ?></label><br>
	                <input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address full_input" size="50" placeholder="Detailed Address">
	                <label for="reg_mb_addr2" class="sound_only">Detailed Address</label>
	                <br>
	                <input type="text" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address full_input" size="50" readonly="readonly" placeholder="Reference Item">
	                <label for="reg_mb_addr3" class="sound_only">Reference Item</label>
	                <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
	            </li>
	            <?php }  ?>
	        </ul>
	    </div>
	
	    <div class="tbl_frm01 tbl_wrap register_form_inner">
	        <h2>Other Personal Settings</h2>
	        <ul>
	            <?php if ($config['cf_use_signature']) {  ?>
	            <li>
	                <label for="reg_mb_signature">Signature<?php if ($config['cf_req_signature']){ ?><strong class="sound_only">Required</strong><?php } ?></label>
	                <textarea name="mb_signature" id="reg_mb_signature" <?php echo $config['cf_req_signature']?"required":""; ?> class="<?php echo $config['cf_req_signature']?"required":""; ?>"   placeholder="Signature"><?php echo $member['mb_signature'] ?></textarea>
	            </li>
	            <?php }  ?>
	
	            <?php if ($config['cf_use_profile']) {  ?>
	            <li>
	                <label for="reg_mb_profile">Introduction</label>
	                <textarea name="mb_profile" id="reg_mb_profile" <?php echo $config['cf_req_profile']?"required":""; ?> class="<?php echo $config['cf_req_profile']?"required":""; ?>" placeholder="Introduction"><?php echo $member['mb_profile'] ?></textarea>
	            </li>
	            <?php }  ?>
	
	            <?php if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) {  ?>
	            <li>
	                <label for="reg_mb_icon" class="frm_label">
	                	Member Icon
	                	<button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
	                	<span class="tooltip">Image size must be less than <?php echo $config['cf_member_icon_width'] ?>px width and <?php echo $config['cf_member_icon_height'] ?>px height.<br>
	gif, jpg, png files only, and must be less than <?php echo number_format($config['cf_member_icon_size']) ?> bytes.</span>
	                </label>
	                <input type="file" name="mb_icon" id="reg_mb_icon">
	
	                <?php if ($w == 'u' && file_exists($mb_icon_path)) {  ?>
	                <img src="<?php echo $mb_icon_url ?>" alt="Member Icon">
	                <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
	                <label for="del_mb_icon">Delete</label>
	                <?php }  ?>
	            
	            </li>
	            <?php }  ?>
	
	            <?php if ($member['mb_level'] >= $config['cf_icon_level'] && $config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {  ?>
	            <li class="reg_mb_img_file">
	                <label for="reg_mb_img" class="frm_label">
	                	Member Image
	                	<button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
	                	<span class="tooltip">Image size must be less than <?php echo $config['cf_member_img_width'] ?>px width and <?php echo $config['cf_member_img_height'] ?>px height.<br>
	                    gif, jpg, png files only, and must be less than <?php echo number_format($config['cf_member_img_size']) ?> bytes.</span>
	                </label>
	                <input type="file" name="mb_img" id="reg_mb_img">
	
	                <?php if ($w == 'u' && file_exists($mb_img_path)) {  ?>
	                <img src="<?php echo $mb_img_url ?>" alt="Member Image">
	                <input type="checkbox" name="del_mb_img" value="1" id="del_mb_img">
	                <label for="del_mb_img">Delete</label>
	                <?php }  ?>
	            
	            </li>
	            <?php } ?>
	
	            <li class="chk_box">
		        	<input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo ($w=='' || $member['mb_mailling'])?'checked':''; ?> class="selec_chk">
		            <label for="reg_mb_mailling">
		            	<span></span>
		            	<b class="sound_only">Mailing Service</b>
		            </label>
		            <span class="chk_li">I want to receive information emails.</span>
		        </li>
	
				<?php if ($config['cf_use_hp']) { ?>
		        <li class="chk_box">
		            <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w=='' || $member['mb_sms'])?'checked':''; ?> class="selec_chk">
		        	<label for="reg_mb_sms">
		            	<span></span>
		            	<b class="sound_only">SMS Reception</b>
		            </label>        
		            <span class="chk_li">I want to receive SMS messages.</span>
		        </li>
		        <?php } ?>
	
		        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능 ?>
		        <li class="chk_box">
		            <input type="checkbox" name="mb_open" value="1" id="reg_mb_open" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?> class="selec_chk">
		      		<label for="reg_mb_open">
		     			<span></span>
		     			<b class="sound_only">Public Information</b>
		     		</label>      
		            <span class="chk_li">Allow others to see my information.</span>
		            <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
		            <span class="tooltip">
		                If you change public information, you cannot change it again for <?php echo (int)$config['cf_open_modify'] ?> days.
		            </span>
		            <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>"> 
		        </li>		        
		        <?php } else { ?>
	            <li>
	                Public Information
	                <input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
	                <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i><span class="sound_only">Show Description</span></button>
	                <span class="tooltip">
	                    You cannot change public information until <?php echo (int)$config['cf_open_modify'] ?> days after modification, until <?php echo date("Y-m-d", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?>.<br>
	                    This is to prevent frequent changes and avoid missing messages after sending.
	                </span>
	            
	            </li>
	            <?php }  ?>
	
	            <?php
	            //회원정보 수정인 경우 소셜 계정 출력
	            if( $w == 'u' && function_exists('social_member_provider_manage') ){
	                social_member_provider_manage();
	            }
	            ?>
	            
	            <?php if ($w == "" && $config['cf_use_recommend']) {  ?>
	            <li>
	                <label for="reg_mb_recommend" class="sound_only">Referrer ID</label>
	                <input type="text" name="mb_recommend" id="reg_mb_recommend" class="frm_input" placeholder="Referrer ID">
	            </li>
	            <?php }  ?>
	
	            <li class="is_captcha_use">
	                Anti-spam
	                <?php echo captcha_html(); ?>
	            </li>
	        </ul>
	    </div>
	</div>
	<div class="btn_confirm">
	    <a href="<?php echo G5_URL ?>" class="btn_close">Cancel</a>
	    <button type="submit" id="btn_submit" class="btn_submit" accesskey="s"><?php echo $w==''?'Register':'Update Information'; ?></button>
	</div>
	</form>
</div>
<script>
$(function() {
    $("#reg_zip_find").css("display", "inline-block");

    <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
    // IPIN Verification
    $("#win_ipin_cert").click(function() {
        if(!cert_confirm())
            return false;

        var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
        certify_win_open('kcb-ipin', url);
        return;
    });

    <?php } ?>
    <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
    // Mobile Phone Verification
    $("#win_hp_cert").click(function() {
        if(!cert_confirm())
            return false;

        <?php
        switch($config['cf_cert_hp']) {
            case 'kcb':
                $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                $cert_type = 'kcb-hp';
                break;
            case 'kcp':
                $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                $cert_type = 'kcp-hp';
                break;
            case 'lg':
                $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                $cert_type = 'lg-hp';
                break;
            default:
                echo 'alert("Please set up mobile phone verification in the basic settings.");';
                echo 'return false;';
                break;
        }
        ?>

        certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
        return;
    });
    <?php } ?>
});

// submit 최종 폼체크
function fregisterform_submit(f)
{
    // 회원아이디 검사
    if (f.w.value == "") {
        var msg = reg_mb_id_check();
        if (msg) {
            alert(msg);
            f.mb_id.select();
            return false;
        }
    }

    if (f.w.value == "") {
        if (f.mb_password.value.length < 3) {
            alert("Please enter a password with at least 3 characters.");
            f.mb_password.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert("Passwords do not match.");
        f.mb_password_re.focus();
        return false;
    }

    if (f.mb_password.value.length > 0) {
        if (f.mb_password_re.value.length < 3) {
            alert("Please enter a password with at least 3 characters.");
            f.mb_password_re.focus();
            return false;
        }
    }

    // 이름 검사
    if (f.w.value=="") {
        if (f.mb_name.value.length < 1) {
            alert("Please enter your name.");
            f.mb_name.focus();
            return false;
        }

        /*
        var pattern = /([^가-힣\x20])/i;
        if (pattern.test(f.mb_name.value)) {
            alert("Please enter your name in Korean.");
            f.mb_name.select();
            return false;
        }
        */
    }

    <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
    // 본인확인 체크
    if(f.cert_no.value=="") {
        alert("Identity verification is required for registration.");
        return false;
    }
    <?php } ?>

    // 닉네임 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
        var msg = reg_mb_nick_check();
        if (msg) {
            alert(msg);
            f.reg_mb_nick.select();
            return false;
        }
    }

    // E-mail 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
        var msg = reg_mb_email_check();
        if (msg) {
            alert(msg);
            f.reg_mb_email.select();
            return false;
        }
    }

    <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
    // 휴대폰번호 체크
    var msg = reg_mb_hp_check();
    if (msg) {
        alert(msg);
        f.reg_mb_hp.select();
        return false;
    }
    <?php } ?>

    if (typeof f.mb_icon != "undefined") {
        if (f.mb_icon.value) {
            if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                alert("Member icon must be an image file.");
                f.mb_icon.focus();
                return false;
            }
        }
    }

    if (typeof f.mb_img != "undefined") {
        if (f.mb_img.value) {
            if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                alert("Member image must be an image file.");
                f.mb_img.focus();
                return false;
            }
        }
    }

    if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
        if (f.mb_id.value == f.mb_recommend.value) {
            alert("You cannot recommend yourself.");
            f.mb_recommend.focus();
            return false;
        }

        var msg = reg_mb_recommend_check();
        if (msg) {
            alert(msg);
            f.mb_recommend.select();
            return false;
        }
    }

    <?php echo chk_captcha_js();  ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

jQuery(function($){
	//tooltip
    $(document).on("click", ".tooltip_icon", function(e){
        $(this).next(".tooltip").fadeIn(400).css("display","inline-block");
    }).on("mouseout", ".tooltip_icon", function(e){
        $(this).next(".tooltip").fadeOut();
    });
});

</script>

<!-- } 회원정보 입력/수정 끝 -->