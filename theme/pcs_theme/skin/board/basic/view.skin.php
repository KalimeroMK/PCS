<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css">', 0);

if (!$is_member) {
    echo "<p align='center'> <font color = red size = 5> <strong> 로그인이 필요합니다. </strong></font></p>";
} elseif (!$member['mb_1']) {
    echo "<p align='center'> <font color = red size = 5> <strong> 사용권한이 필요합니다.</strong></font></p>";
} else {
    ?>

    <!-- 게시물 읽기 시작 { -->

    <article id="bo_v" style="width:<?php echo $width; ?>">
        <header>
            <h2 id="bo_v_title">
                <?php if ($category_name) { ?>
                    <span class="bo_v_cate"><?php echo $view['ca_name']; // 분류 출력 끝 ?></span>
                <?php } ?>
                <span class="bo_v_tit">
            <?php
            echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
            ?></span>
            </h2>
        </header>


        <section id="bo_v_atc">
            <h2 id="bo_v_atc_title">본문</h2>

            <!-- 본문 내용 시작 { -->
            <?php include_once(PCS_LIB . '/pcs_view_board.php'); ?>
            <!-- } 본문 내용 끝 -->


    </article>
<?php } ?>

<!-- } 게시글 읽기 끝 -->