<?php

global $lo_location;
global $lo_url;

include_once(__DIR__ . '/../common.php');


$g5['title'] = $error ? "Error Notice Page" : "Result Notice Page";
include_once(G5_PATH.'/head.sub.php');
// Required input.
// Remove spaces on both sides
// Required (selection or input)
// The phone number format is incorrect. Please include hyphens (-).
// The email address format is incorrect.
// Korean is not allowed. (Korean with only consonants or vowels is not processed.)
// Korean is not allowed.
// Korean, English, and numbers are not allowed.
// Korean and English are not allowed.
// Numbers are not allowed.
// English is not allowed.
// English or numbers are not allowed.
// English, numbers, and _ are not allowed.
// Please enter at least the minimum number of characters.
// The image file is not allowed. Only .gif, .jpg, .png files are allowed.
// Only files are allowed.
// Spaces are not allowed.

$msg = isset($msg) ? strip_tags($msg) : '';
$msg2 = str_replace("\\n", "<br>", $msg);

$url = isset($url) ? clean_xss_tags($url, 1) : '';
if (!$url) {
    $url = isset($_SERVER['HTTP_REFERER']) ? clean_xss_tags($_SERVER['HTTP_REFERER'], 1) : '';
}

$url = preg_replace("/[\<\>\'\"\\\'\\\"\(\)]/", "", $url);
$url = preg_replace('/\r\n|\r|\n|[^\x20-\x7e]/', '', $url);

// url check
check_url_host($url, $msg);

$header2 = $error ? "There is an error in the following item." : "Please check the following content.";
?>

    <script>
        alert("<?php echo $msg; ?>");
        <?php if ($url) { ?>
        document.location.replace("<?php echo str_replace('&amp;', '&', $url); ?>");
        <?php } else { ?>
        history.back();
        <?php } ?>
    </script>

    <noscript>
        <div id="validation_check">
            <h1><?php
                echo $header2 ?></h1>
            <p class="cbg">
                <?php
                echo $msg2 ?>
            </p>
            <?php
            if ($post) { ?>
                <form method="post" action="<?php
                echo $url ?>">
                    <?php
                    foreach ($_POST as $key => $value) {
                        $key = clean_xss_tags($key);
                        $value = clean_xss_tags($value);

                        if (strlen($value) < 1) {
                            continue;
                        }

                        if (preg_match("/pass|pwd|capt|url/", $key)) {
                            continue;
                        }
                        ?>
                        <input type="hidden" name="<?php
                        echo htmlspecialchars($key); ?>" value="<?php
                        echo htmlspecialchars($value); ?>">
                        <?php
                    }
                    ?>
                    <input type="submit" value="Back">
                </form>
            <?php
            } else { ?>
                <div class="btn_confirm">
                    <a href="<?php
                    echo $url ?>">Back</a>
                </div>
            <?php
            } ?>

            <?php
            /*
           <article id="validation_check">
           <header>
               <hgroup>
                   <!-- <h1>회원가입 정보 입력 확인</h1> --> <!-- 수행 중이던 작업 내용 -->
                   <h1><?php echo $header ?></h1> <!-- 수행 중이던 작업 내용 -->
                   <h2><?php echo $header2 ?></h2>
               </hgroup>
           </header>
           <p>
               <!-- <strong>항목</strong> 오류내역 -->
               <!--
               <strong>이름</strong> 필수 입력입니다. 한글만 입력할 수 있습니다.<br>
               <strong>이메일</strong> 올바르게 입력하지 않았습니다.<br>
               -->
               <?php echo $msg2 ?>
           </p>

           <a href="<?php echo $url ?>">Back</a>
           </article>
           */ ?>
        </div>
    </noscript>

<?php
include_once(G5_PATH.'/tail.sub.php');