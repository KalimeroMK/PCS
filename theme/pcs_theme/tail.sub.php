<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed
?>

<?php if ($is_admin == 'super') { ?><!-- <div style='float:left; text-align:center;'>RUN TIME : <?php echo get_microtime() - $begin_time; ?><br></div> --><?php } ?>

    <!-- Fix for IE6,7 where side view is covered by lower side view in board list -->
    <!--[if lte IE 7]>
    <script>
        $(function () {
            var $sv_use = $(".sv_use");
            var count = $sv_use.length;

            $sv_use.each(function () {
                $(this).css("z-index", count);
                $(this).css("position", "relative");
                count = count - 1;
            });
        });
    </script>
    <![endif]-->

<?php run_event('tail_sub'); ?>

    </body>
    </html>
<?php echo html_end(); // Final HTML processing function: Please make sure to include this. ?>