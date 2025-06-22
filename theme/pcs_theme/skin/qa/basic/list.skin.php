<?php
if (!defined('_GNUBOARD_')) exit; // individual page access not allowed

// selection options cause cell merging to change dynamically
$colspan = 6;

if ($is_checkbox) $colspan++;

// add_stylesheet('css syntax', output order); the smaller the number, the earlier it is output
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>

<div id="bo_list">
	<?php if ($category_option) { ?>
    <!-- category start { -->
    <nav id="bo_cate">
        <h2><?php echo $qaconfig['qa_title'] ?> Category</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <!-- } category end -->
    <?php } ?>
    
	<!-- board page information and button start { -->
    <div id="bo_btn_top">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?> posts</span>
            <?php echo $page ?> page
        </div>

        <?php if ($admin_href || $write_href) { ?>
        <ul class="btn_bo_user">
        	<?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="Admin"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">Admin</span></a></li><?php } ?>
        	<li>
        		<button type="button" class="btn_bo_sch btn_b01 btn" title="Board Search"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">Board Search</span></button>
				<!-- board search start { -->
			    <div class="bo_sch_wrap">
				    <fieldset class="bo_sch">
				    	<h3>Search</h3>
				        <legend>Post Search</legend>
				        <form name="fsearch" method="get">
				        <input type="hidden" name="sca" value="<?php echo $sca ?>">
				        <label for="stx" class="sound_only">Search term<strong class="sound_only"> required</strong></label>
				        <div class="sch_bar">
				       		<input type="text" name="stx" value="<?php echo stripslashes($stx); ?>" id="stx" required class="sch_input" size="25" maxlength="15" placeholder="Enter search term">
							<button type="submit" value="Search" class="sch_btn" title="Search"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">Search</span></button>
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
			</li>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="Write Post"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">Write Post</span></a></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <!-- } board page information and button end -->
	
    <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
            
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $board['bo_subject'] ?> List</caption>
        <thead>
        <tr>
            <?php if ($is_checkbox) { ?>
            <th scope="col" class="all_chk chk_box">
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
            	<label for="chkall">
                	<span></span>
                	<b class="sound_only">Select all posts on this page</b>
                </label>
            </th>
            <?php } ?>
            <th scope="col">No.</th>
            <th scope="col">Title</th>
            <th scope="col">Author</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        
$counter = count($list);<?php
        for ($i=0; $i<$counter; $i++) {
        	$lt_class = $i % 2 == 0 ? "even" : "";
        ?>
        <tr class="<?php echo $lt_class ?>">
            <?php if ($is_checkbox) { ?>
            <td class="td_chk chk_box">
            	<input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id'] ?>" id="chk_qa_id_<?php echo $i ?>" class="selec_chk">
                <label for="chk_qa_id_<?php echo $i ?>">
            		<span></span>
            		<b class="sound_only"><?php echo $list[$i]['subject'] ?></b>
            	</label>
            </td>
            <?php } ?>
            <td class="td_num"><?php echo $list[$i]['num']; ?></td>
            <td class="td_subject">
                <span class="bo_cate_link"><?php echo $list[$i]['category']; ?></span>
                <a href="<?php echo $list[$i]['view_href']; ?>" class="bo_tit">
                    <?php echo $list[$i]['subject']; ?>
                    <?php if ($list[$i]['icon_file']) echo " <i class=\"fa fa-download\" aria-hidden=\"true\"></i>" ; ?>
                </a>
            </td>
            <td class="td_name"><?php echo $list[$i]['name']; ?></td>
            <td class="td_date"><?php echo $list[$i]['date']; ?></td>
            <td class="td_stat"><span class=" <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($list[$i]['qa_status'] ? 'Answered' : 'Waiting for answer'); ?></span></td>
        </tr>
        <?php
        }
        ?>

        <?php if ($i == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">No posts</td></tr>'; } ?>
        </tbody>
        </table>
    </div>
	<!-- page -->
	<?php echo $list_pages; ?>
	<!-- page -->
	
    <div class="bo_fx">
        <ul class="btn_bo_user">
        	<?php if ($is_checkbox) { ?>
            <li><button type="submit" name="btn_submit" value="Delete Selected" title="Delete Selected" onclick="document.pressed=this.value" class="btn btn_b01 btn_admin"><i class="fa fa-trash-o" aria-hidden="true"></i><span class="sound_only">Delete Selected</span></button></li>
            <?php } ?>
            <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01 btn" title="List"><i class="fa fa-list" aria-hidden="true"></i><span class="sound_only">List</span></a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="Write Post"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">Write Post</span></a></li><?php } ?>
        </ul>
    </div>
    </form>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>If you do not use JavaScript<br>Bulk selection and deletion will be processed immediately without separate confirmation, so please be careful.</p>
</noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "Please select at least one post to ");
        return false;
    }

    if(document.pressed == "Delete Selected") {
        if (!confirm("Are you sure you want to delete the selected posts?\n\nDeleted data cannot be recovered."))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } board list end -->
