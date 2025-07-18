<?php
if ($_POST['Spec']) {
    $query_change = '
		UPDATE ' . G5_TABLE_PREFIX . 'pcs_dwgconfig SET
		fontsize = "' . $_POST['fontsize'] . '",
		linewidth = "' . $_POST['linewidth'] . '",
		j_shop = "' . $_POST[''] . '",
		j_field = "' . $_POST[''] . '",
		j_bolt = "' . $_POST[''] . '",
		j_support = "' . $_POST[''] . '",
		j_thread = "' . $_POST[''] . '",
		qr_x = "' . $_POST['qr_x'] . '",
		qr_y = "' . $_POST['qr_y'] . '",
		qr_size = "' . $_POST['qr_size'] . '",
		pkg_x = "' . $_POST['pkg_x'] . '",
		pkg_y = "' . $_POST['pkg_y'] . '"
	';
    sql_query($query_change);
//echo $query_change.'<br>';
}
$query_dwg_spec = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_dwgconfig';
$sql_dwg_spec = sql_query($query_dwg_spec);
$sql_dwg_spec_array = sql_fetch_array($sql_dwg_spec);
//	print_r($sql_dwg_spec_array);
?>
<script src="<?php echo PCS_LIB_URL; ?>/pdfjs_viewer/build/pdf.js"></script>
<script src="<?php echo PCS_LIB_URL; ?>/pdfjs_viewer/build/pdf.worker.js"></script>
<style>
    input {
        padding: 0px 0px 0px 5px;
        text-align: center;
        width: 95%;
        height: 50px;
        font-size: 30px;
        border: none;
        border-right: 0px;
        border-top: 0px;
        boder-left: 0px;
        boder-bottom: 0px;
    }
</style>

<p>&nbsp;
<p align="center">
    <canvas id='cv' width='1050' height='743' style='border:1px solid black;'></canvas>
</p>

<form name='submit_form2' method="post" onSubmit="return doSumbit()">
    <input type='hidden' name='Spec' value='Y'>
    <table class="main">
        <caption><a href='javascript:document.submit_form2.submit()'> Drawing SPECIFICATION </a></caption>
        <tbody>
        <tr>
            <td class="main_td td_sub" style="width:15%"> Font size</td>
            <td class="main_td" style="width:15%"><input type="text" name="fontsize"
                                                         value="<?php echo $sql_dwg_spec_array['fontsize']; ?>"></td>
            <td class="main_td td_sub" style="width:15%"> Line width</td>
            <td class="main_td" style="width:15%"><input type="text" name="linewidth"
                                                         value="<?php echo $sql_dwg_spec_array['linewidth']; ?>"></td>
            <td class="main_td td_sub" style="width:15%"> Package no.</td>
            <td class="main_td"><input type="text" name="pkg_x" value="<?php echo $sql_dwg_spec_array['pkg_x']; ?>">
            </td>
            <td class="main_td"><input type="text" name="pkg_y" value="<?php echo $sql_dwg_spec_array['pkg_y']; ?>">
            </td>
        </tr>
        </tbody>
    </table>
    <table class="main">
        <tbody>
        <tr>
            <td class="main_td td_sub" style="width:10%"> Shop</td>
            <td class="main_td" style="width:10%">Circle</td>
            <td class="main_td td_sub" style="width:10%"> Field</td>
            <td class="main_td" style="width:10%">Hexagon</td>
            <td class="main_td td_sub" style="width:10%"> Bolt</td>
            <td class="main_td" style="width:10%">Square</td>
            <td class="main_td td_sub" style="width:10%"> Thread</td>
            <td class="main_td" style="width:10%">Diamond</td>
            <td class="main_td td_sub" style="width:10%"> Support</td>
            <td class="main_td" style="width:10%">hexagon</td>
        </tr>
        </tbody>
    </table>
    <table class="main">
        <tbody>
        <tr>
            <td class="main_td td_sub" style="width:25%"> QR code</td>
            <td class="main_td" style="width:25%"><input type="text" name="qr_x" value="<?php
                echo $sql_dwg_spec_array['qr_x']; ?>"></td>
            <td class="main_td" style="width:25%"><input type="text" name="qr_y" value="<?php
                echo $sql_dwg_spec_array['qr_y']; ?>"></td>
            <td class="main_td" style="width:25%"><input type="text" name="qr_size" value="<?php
                echo $sql_dwg_spec_array['qr_size']; ?>"></td>
        </tr>
        </tbody>
    </table>
</form>

<script>
    const canvas = document.getElementById('cv');
    const context = canvas.getContext('2d');
    const qr_img = new Image();
    qr_img.src = '<?php echo PCS_CORE_URL;?>/qr.png';
    let qr_x =;
    let qr_y =;
    let qr_size =;
    let pkg_x =;
    let pkg_y =;


    const loadingTask = pdfjsLib.getDocument('<?php echo PCS_CORE_URL;?>/sampleISO.pdf');

    loadingTask.promise.then(function (pdfFile) {
        pdfFile.getPage(1).then(function (page) {
            const viewport = page.getViewport({scale: 1,});
            const scale = canvas.width / viewport.width;
            const scaledViewport = page.getViewport({scale: scale,});
            const renderContext = {
                canvasContext: context,
                viewport: scaledViewport
            };
            const rendertask = page.render(renderContext);
            rendertask.promise.then(function () {
                context.drawImage(qr_img, qr_x, qr_y, qr_size, qr_size);
                context.font = 11 + 'px verdana';
                context.fillStyle = 'blue';
                context.textAlign = 'center';
//			context.fillText ('PCS Welding Map', qr_x+30, qr_y+85);

                const tri = 18;
                const texWid = context.measureText("1").width / 2;
                const NoX = pkg_x;
                const NoY = pkg_y + 10;
                context.globalAlpha = .5;
                context.font = 20 + 'px verdana';
                context.strokeStyle = 'red';
                context.lineWidth = 3;
                context.beginPath();
                context.moveTo(pkg_x, pkg_y - tri);
                context.lineTo(pkg_x - tri, pkg_y + tri / 1.7);
                context.lineTo(pkg_x + tri, pkg_y + tri / 1.7);
                context.lineTo(pkg_x, pkg_y - tri);
                context.fillStyle = "blue";
                context.fillText("1", NoX, NoY - 5);
                context.closePath();
                context.stroke();
            });
        });
    });
</script>