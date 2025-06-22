<?php
if (!defined('_GNUBOARD_')) exit; // individual page access not allowed
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>


<!-- board list start { -->
<div id="bo_gall" style="width:<?php echo $width; ?>">

    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo $board['bo_subject'] ?> Category</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>

    <form name="fboardlist"  id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <!-- board page info and button start { -->
    <div id="bo_btn_top">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?> posts</span>
            <?php echo $page ?> page
        </div>

        <ul class="btn_bo_user">
        	<?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="Admin"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">Admin</span></a></li><?php } ?>
            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
            <li>
            	<button type="button" class="btn_bo_sch btn_b01 btn" title="Board Search"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">Board Search</span></button> 
            </li>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="Write"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">Write</span></a></li><?php } ?>
        	<?php if ($is_admin == 'super' || $is_auth) {  ?>
        	<li>
        		<button type="button" class="btn_more_opt is_list_btn btn_b01 btn" title="Board List Options"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">Board List Options</span></button>
        		<?php if ($is_checkbox) { ?>	
		        <ul class="more_opt is_list_btn">  
		            <li><button type="submit" name="btn_submit" value="Delete Selected" onclick="document.pressed=this.value"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Selected</button></li>
		            <li><button type="submit" name="btn_submit" value="Copy Selected" onclick="document.pressed=this.value"><i class="fa fa-files-o" aria-hidden="true"></i> Copy Selected</button></li>
		            <li><button type="submit" name="btn_submit" value="Move Selected" onclick="document.pressed=this.value"><i class="fa fa-arrows" aria-hidden="true"></i> Move Selected</button></li>
		        </ul>
		        <?php } ?>
        	</li>
        	<?php }  ?>
        </ul>
    </div>
    <!-- } board page info and button end -->

    <?php if ($is_checkbox) { ?>
    <div id="gall_allchk" class="all_chk chk_box">
        <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
    	<label for="chkall">
        	<span></span>
        	<b class="sound_only">Select all posts on this page</b>
        </label>
    </div>
    <?php } ?>

    <ul id="gall_ul" class="gall_row">
        
$counter = count($list);<?php for ($i=0; $i<$counter; $i++) {

            $classes = array();
            
            $classes[] = 'gall_li';
            $classes[] = 'col-gn-'.$bo_gallery_cols;

            if( $i && ($i % $bo_gallery_cols == 0) ){
                $classes[] = 'box_clear';
            }

            if( $wr_id && $wr_id == $list[$i]['wr_id'] ){
                $classes[] = 'gall_now';
            }
         ?>
        <li class="<?php echo implode(' ', $classes); ?>">
            <div class="gall_box">
                <div class="gall_chk chk_box">
	                <?php if ($is_checkbox) { ?>
					<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="selec_chk">
	                <label for="chk_wr_id_<?php echo $i ?>">
	                	<span></span>
	                	<b class="sound_only"><?php echo $list[$i]['subject'] ?></b>
	                </label>
	                
	                <?php } ?>
	                <span class="sound_only">
	                    <?php
	                    if ($wr_id == $list[$i]['wr_id'])
	                        echo "<span class=\"bo_current\">Currently viewing</span>";
	                    else
	                        echo $list[$i]['num'];
	                     ?>
	                </span>
                </div>
                <div class="gall_con">
                    <div class="gall_img">
                        <a href="<?php echo $list[$i]['href'] ?>">
                        <?php
                        if ($list[$i]['is_notice']) { // notice ?>
                            <span class="is_notice">Notice</span>
                        <?php } else {
                            $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height'], false, true);

                            if($thumb['src']) {
                                $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" >';
                            } else {
                                $img_content = '<span class="no_image">no image</span>';
                            }

                            echo $img_content;
                        }
                         ?>
                        </a>
                    </div>
                    <div class="gall_text_href">
                        <?php if ($is_category && $list[$i]['ca_name']) { ?>
                        <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                        <?php } ?>
                        <a href="<?php echo $list[$i]['href'] ?>" class="bo_tit">
                            
                            <?php // echo $list[$i]['icon_reply']; ?>
                        	<!-- Please remove the comment function in the gallery if not used. -->
                        
                            <?php echo $list[$i]['subject'] ?>                      
                            <?php
                            // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }
                            if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">N<span class=\"sound_only\">New</span></span>";
                            if (isset($list[$i]['icon_hot'])) echo rtrim($list[$i]['icon_hot']);
                            //if (isset($list[$i]['icon_file'])) echo rtrim($list[$i]['icon_file']);
                            //if (isset($list[$i]['icon_link'])) echo rtrim($list[$i]['icon_link']);
                            if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
							?>
							<?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">Comments</span><span class="cnt_cmt"><?php echo $list[$i]['wr_comment']; ?></span><span class="sound_only"> posts</span><?php } ?>
                         </a>
                         <span class="bo_cnt"><?php echo utf8_strcut(strip_tags($list[$i]['wr_content']), 68, '..'); ?></span>
                    </div>

                    <div class="gall_info">
                    	<span class="sound_only">Author </span><?php echo $list[$i]['name'] ?>
                        <span class="gall_date"><span class="sound_only">Date </span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $list[$i]['datetime2'] ?></span>
                    	<span class="gall_view"><span class="sound_only">Views </span><i class="fa fa-eye" aria-hidden="true"></i> <?php echo $list[$i]['wr_hit'] ?></span>
                    </div>
                    <div class="gall_option">
                    	<?php if ($is_good) { ?><span class="sound_only">Like</span><strong><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <?php echo $list[$i]['wr_good'] ?></strong><?php } ?>
                        <?php if ($is_nogood) { ?><span class="sound_only">Dislike</span><strong><i class="fa fa-thumbs-o-down" aria-hidden="true"></i> <?php echo $list[$i]['wr_nogood'] ?></strong><?php } ?>           
                    </div>
                </div>
            </div>
        </li>
        <?php } ?>
        <?php if (count($list) == 0) { echo "<li class=\"empty_list\">No posts available.</li>"; } ?>
    </ul>
	
	<!-- page -->
	<?php echo $write_pages; ?>
	<!-- page -->
	
	<?php if ($list_href || $is_checkbox || $write_href) { ?>
    <div class="bo_fx">
        <?php if ($list_href || $write_href) { ?>
        <ul class="btn_bo_user">
        	<?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="Admin"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">Admin</span></a></li><?php } ?>
            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="Write"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">Write</span></a></li><?php } ?>
        </ul>	
        <?php } ?>
    </div>
    <?php } ?> 
    </form>

    <!-- board search start { -->
    <div class="bo_sch_wrap">	
        <fieldset class="bo_sch">
            <h3>Search</h3>
            <form name="fsearch" method="get">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sop" value="and">
            <label for="sfl" class="sound_only">Search Target</label>
            <select name="sfl" id="sfl">
                <?php echo get_board_sfl_select_options($sfl); ?>
            </select>
            <label for="stx" class="sound_only">Search Term<strong class="sound_only"> required</strong></label>
            <div class="sch_bar">
                <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="sch_input" size="25" maxlength="20" placeholder="Please enter a search term">
                <button type="submit" value="Search" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">Search</span></button>
            </div>
            <button type="button" class="bo_sch_cls"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">Close</span></button>
            </form>
        </fieldset>
        <div class="bo_sch_bg"></div>
    </div>
    <script>
        // board search
        $(".btn_bo_sch").on("click", function() {
            $(".bo_sch_wrap").toggle();
        })
        $('.bo_sch_bg, .bo_sch_cls').click(function(){
            $('.bo_sch_wrap').hide();
        });
    </script>
    <!-- } board search end -->
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>Please note that JavaScript is not enabled. Posts will be deleted without confirmation.</p>
</noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + " Please select at least one post.");
        return false;
    }

    if(document.pressed == "Copy Selected") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "Move Selected") {
        select_copy("move");
        return;
    }

    if(document.pressed == "Delete Selected") {
        if (!confirm("Are you sure you want to delete the selected posts?\n\nOnce deleted, posts cannot be recovered.\n\nIf you have selected a post with replies, please select the replies as well to delete the post."))
            return false;

        f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
    }

    return true;
}

// select and copy/move posts
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
        str = "Copy";
    else
        str = "Move";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url+"/move.php";
    f.submit();
}

// board list admin options
jQuery(function($){
    $(".btn_more_opt.is_list_btn").on("click", function(e) {
        e.stopPropagation();
        $(".more_opt.is_list_btn").toggle();
    });
    $(document).on("click", function (e) {
        if(!$(e.target).closest('.is_list_btn').length) {
            $(".more_opt.is_list_btn").hide();
        }
    });
});
</script>
<?php } ?>
<!-- } board list end -->
