<?php
if (!defined('_GNUBOARD_')) exit; // Individual page access not allowed
?>

<?php if ($is_admin == 'super') {  ?><!-- <div style='float:left; text-align:center;'>RUN TIME : <?php echo get_microtime()-$begin_time; ?><br></div> --><?php }  ?>

<?php run_event('tail_sub'); ?>

</body>
</html>
<?php echo html_end(); // HTML finalization function: Please make sure to include this.
