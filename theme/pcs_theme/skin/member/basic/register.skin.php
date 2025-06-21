<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- Terms of Service Agreement start { -->
<div class="register">

    <form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

    <p><i class="fa fa-check-circle" aria-hidden="true"></i> You must agree to the Terms of Service and Privacy Policy to register.</p>
    
    <?php
    // Social login button if used
    @include_once(get_social_skin_path().'/social_register.skin.php');
    ?>
    <section id="fregister_term">
        <h2>Terms of Service</h2>
        <textarea readonly><?php echo get_text($config['cf_stipulation']) ?></textarea>
        <fieldset class="fregister_agree">
            <input type="checkbox" name="agree" value="1" id="agree11" class="selec_chk">
            <label for="agree11"><span></span><b class="sound_only">I agree to the Terms of Service.</b></label>
        </fieldset>
    </section>

    <section id="fregister_private">
        <h2>Privacy Policy</h2>
        <div>
            <table>
                <caption>Privacy Policy</caption>
                <thead>
                <tr>
                    <th>Purpose</th>
                    <th>Items</th>
                    <th>Retention Period</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>User identification and verification</td>
                    <td>ID, Name, Password</td>
                    <td>Until membership withdrawal</td>
                </tr>
                <tr>
                    <td>Notifications for customer service use,<br>User identification for CS response</td>
                    <td>Contact information (Email, Mobile number)</td>
                    <td>Until membership withdrawal</td>
                </tr>
                </tbody>
            </table>
        </div>

        <fieldset class="fregister_agree">
            <input type="checkbox" name="agree2" value="1" id="agree21" class="selec_chk">
            <label for="agree21"><span></span><b class="sound_only">I agree to the Privacy Policy.</b></label>
       </fieldset>
    </section>
	
	<div id="fregister_chkall" class="chk_all fregister_agree">
        <input type="checkbox" name="chk_all" id="chk_all" class="selec_chk">
        <label for="chk_all"><span></span>I agree to all Terms of Service</label>
    </div>
	    
    <div class="btn_confirm">
    	<a href="<?php echo G5_URL ?>" class="btn_close">Cancel</a>
        <button type="submit" class="btn_submit">Register</button>
    </div>

    </form>

    <script>
    function fregister_submit(f)
    {
        if (!f.agree.checked) {
            alert("You must agree to the Terms of Service to register.");
            f.agree.focus();
            return false;
        }

        if (!f.agree2.checked) {
            alert("You must agree to the Privacy Policy to register.");
            f.agree2.focus();
            return false;
        }

        return true;
    }
    
    jQuery(function($){
        // Select all
        $("input[name=chk_all]").click(function() {
            if ($(this).prop('checked')) {
                $("input[name^=agree]").prop('checked', true);
            } else {
                $("input[name^=agree]").prop("checked", false);
            }
        });
    });

    </script>
</div>
<!-- } Terms of Service Agreement end -->
