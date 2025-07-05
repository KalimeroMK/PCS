<?php
include_once(__DIR__ . '/../common.php');

include_once(__DIR__ . '/pcs_config.php');

$dwg_file = $_POST['fn'];


if (!$_POST['key']) {
    if ($_POST['PM_txt']) {

        $query_pkg_coor_check = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor WHERE dwg_no = "' . $_POST['mapped_dwg'] . '" AND pkg_no = "' . $_POST['mapped_pkg'] . '" AND rev_no = "' . $_POST['rev_dwg'] . '"';
        $sql_pkg_coor_check = sql_query($query_pkg_coor_check);
        $sql_pkg_coor_array = sql_fetch_array($sql_pkg_coor_check);
        $pkg_coor_info = $sql_pkg_coor_array['joint_info'];

        if ($_POST['PM_txt'] == 'clear') {
            $query_pkg_coor = 'DELETE FROM ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor WHERE dwg_no = "' . $_POST['mapped_dwg'] . '" AND pkg_no = "' . $_POST['mapped_pkg'] . '" AND rev_no = "' . $_POST['rev_dwg'] . '"';
            sql_query($query_pkg_coor);
        } else {
            $query_pkg_coor = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor SET latest = "N" WHERE dwg_no = "' . $_POST['mapped_dwg'] . '" AND pkg_no = "' . $_POST['mapped_pkg'] . '"';
            sql_query($query_pkg_coor);

            if ($pkg_coor_info) {
                $query_pkg_coor = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor SET
									latest = "Y",
									joint_info = "' . $_POST['PM_txt'] . '",
									tb_qty = "' . $_POST['TB_qty'] . '" 
								WHERE dwg_no = "' . $_POST['mapped_dwg'] . '" AND pkg_no = "' . $_POST['mapped_pkg'] . '" AND rev_no = "' . $_POST['rev_dwg'] . '"';
                sql_query($query_pkg_coor);

            } else {
                $query_pkg_coor = 'INSERT INTO ' . G5_TABLE_PREFIX . 'pcs_info_pkg_coor SET
									dwg_no = "' . $_POST['mapped_dwg'] . '",
									pkg_no = "' . $_POST['mapped_pkg'] . '",
									rev_no = "' . $_POST['rev_dwg'] . '",
									joint_info = "' . $_POST['PM_txt'] . '",
									tb_qty = "' . $_POST['TB_qty'] . '",
									time = "' . G5_TIME_YMDHIS . '",
									dwg_state = "Marked",
									latest = "Y"';
                sql_query($query_pkg_coor);
            }

        }
    }

    $query_sort = 'UPDATE ' . G5_TABLE_PREFIX . 'pcs_info_pkg_stat SET dwg_qty = ' . $_POST['qty'] . ' , dwg_order = "' . $_POST['order'] . '" WHERE pkg_no = "' . $_POST['mapped_pkg'] . '"';
    sql_query($query_sort);

    echo '
	<script>
	opener.document.location.reload();
	</script>
	';

    include_once(__DIR__ . '/pcs_mark_NumPkg.php');

}

$query_pkg_coor_check = "SELECT * FROM " . G5_TABLE_PREFIX . "pcs_info_pkg_coor WHERE dwg_no = '" . $_POST['fn'] . "' AND pkg_no = '" . $_POST['pn'] . "' AND rev_no = '" . $_POST['rev'] . "'";
$sql_pkg_coor_check = sql_query($query_pkg_coor_check);
$sql_pkg_coor_array = sql_fetch_array($sql_pkg_coor_check);
$pkg_coor_info = $sql_pkg_coor_array['joint_info'];

$query_dwg_spec = 'SELECT * FROM ' . G5_TABLE_PREFIX . 'pcs_dwgconfig';
$sql_dwg_spec = sql_query($query_dwg_spec);
$sql_dwg_spec_array = sql_fetch_array($sql_dwg_spec);

?>
<html>
<head>
    <title> Package Numbering Page </title>
</head>
<script src="../jquery/jquery-3.2.1.min.js"></script>
<script src="../pdfjs_viewer/build/pdf.js"></script>
<script src="../pdfjs_viewer/build/pdf.worker.js"></script>

<body>

<div style='width:100%; height:99.5%; border:1px solid blue;'>
    <div style='overflow:scroll; max-width:100%; height:100%; margin-right:350px; cursor:crosshair;'>
        <canvas id='cv' width='2100' height='1485' style='border:1px solid black;' onmousedown='mPush(event, this)'
                onmouseup='mRelease(event, this)'></canvas>
    </div>
    <div style='max-width:350px; position: absolute; top: 10px; right: 10px ; height:98%;'>
        <div style='width:100%; height:30%; border:1px solid red;font-size:25px;'>

            <form method="post">
                <input type='hidden' id='PM_txt' name='PM_txt'>
                <input type="hidden" name="s_no" value="<?php echo $_POST['sn']; ?>">
                <input type="hidden" name="mapped_dwg" value="<?php echo $dwg_file; ?>">
                <input type="hidden" name="shop_dwg" value="<?php echo $_POST['sd']; ?>">
                <input type="hidden" name="mapped_pkg" value="<?php echo $_POST['pn']; ?>">
                <input type="hidden" name="rev_dwg" value="<?php echo $_POST['rev']; ?>">
                <input type="hidden" id='TB_qty' name="TB_qty">
                <input type="hidden" name="TB_sum" value="<?php echo $_POST['tb']; ?>">
                <input type="hidden" name="qty" value="<?php echo $_POST['dwg_qty']; ?>">
                <input type="hidden" name="order" value="<?php echo $_POST['dwg_order']; ?>">

                <p align="center"><input type="submit" id="n_finish" name="n_finish" value="Mapping finish"
                                         style="height:35px; width:150px; font-size:18px;">

                    <? //if($member['mb_3'] == 'Manager') {?>
                    &nbsp; <input type="button" id="allClear" name="allClear" value="Clear all Joints"
                                  style="height:35px; width:150px; font-size:18px;"
                                  onclick="return allClear_onclick()"/>
                    <? // } ?>
                </p>

            </form>
            <input type='radio' name='mark_type' style="height:25px; width: 25px;" value='line' checked>Line &nbsp;
            <input type='radio' name='mark_type' style="height:25px; width: 25px;" value='blind'>Blind &nbsp;
            <input type='radio' name='mark_type' style="height:25px; width: 25px;" value='tbno'>TB no.<br>
            <p>
                <input type='radio' name='mark_type' style="height:25px; width: 25px;" value='page'>Page
                <input type='text' id='pg_no' value='No.' style='height:35px; width:60px; font-size:25px; padding:5px;'
                       onclick="clear_txt('pg_no')"> &nbsp;
                <input type='radio' name='mark_type' style="height:25px; width: 25px;" value='mark'>Mark
                <input type='text' id='cmnt' value='cmt.' style='height:35px; width:60px; font-size:25px; padding:5px;'
                       onclick="clear_txt('cmnt')"><br>
                <!--<p align="center"><input type="button" id="Undo" name="Undo" value="Undo" style="height:35px; width:150px; font-size:18px;" onclick="Undo_onclick()" /></p>-->
        </div>

        <div style='overflow:scroll; width:100%; height:44%; border:1px solid green;font-size:25px;'>
            <table id='jnt_table'
                   style="font-size:15px; border-width: 1px 1px 0px 0px; border-style: solid solid none none;"
                   width='330px' border="0" cellspacing="0" cellpadding="0">
                <caption><span style="font-size: 20px;"> Marking List </span></caption>
                <tbody>
                <tr style="height: 30px; background-color: aquamarine;">
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 50px"><p
                                align="center">no.</p></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 50px"><p
                                align="center">seq.</p></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 50px"><p
                                align="center">Mark<br></p></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 50px"><p
                                align="center">TB no</p></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 80px"><p
                                align="center">content</p></td>
                </tr>
                <tr style="height: 2px; background-color: aquamarine;">
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; "></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; "></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; "></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; "></td>
                    <td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; "></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div style='width:100%; height:25%; border:1px solid yellow;'>
            <img id='preview' src='' width='99.9%' alt='Image come to here.'/>
        </div>
    </div>
</div>

</body>

<script>

    var addValue = new Array();
    var PMDataInfo = '';

    var Sno = 0;
    var markType = '';
    var jntxf = 0;
    var jntyf = 0;
    var jntxt = 0;
    var jntyt = 0;
    var TBsum = <?php echo $_POST['tb']?>*
    1;
    var TBqty = 0;
    var TBsize = 0;
    var tab_tf = false;
    var TableIdx = 0;
    var tmptf = true;
    var pkg_x = <?php echo $sql_dwg_spec_array['pkg_x']; ?>;
    var pkg_y = <?php echo $sql_dwg_spec_array['pkg_y']; ?>;

    <?php
    if (file_exists(PCS_DWG_ISO . '/' . $_POST['fn'] . '/' . $_POST['fn'] . '_' . $_POST['rev'] . '.pdf')) {
        echo 'var dwgpath = "' . PCS_ISO_URL . '/' . $_POST['fn'] . '/' . $_POST['fn'] . '_' . $_POST['rev'] . '.pdf";';
    }
    /*	if(file_exists(PCS_DWG_ISO.'/'.$_POST['fn'].'/'.$_POST['sd'].'.pdf')){
            echo 'var dwgpath = "'.PCS_ISO_URL.'/'.$_POST['fn'].'/'.$_POST['sd'].'.pdf";';
        }*/
    ?>


    var radio_btn = document.getElementsByName('mark_type');

    var canvas = document.getElementById('cv');
    var context = canvas.getContext('2d');

    var rec_Pinfo = '<?php echo $pkg_coor_info;?>';


    var ini_img = new Image();
    var tmpimage = new Image();

    /*
    pdfjsLib.getDocument(dwgpath).then(function(pdfFile) {
        var pageNumber = 1;
        pdfFile.getPage(pageNumber).then(function(page) {
            var scale = canvas.width / page.getViewport(1).width;
            var viewport = page.getViewport(scale);
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            page.render(renderContext).then(function() {
                ini_img.src = document.getElementById('cv').toDataURL('image/png');
                ini_dwg(rec_Pinfo);
            });
        });
    });*/

    var loadingTask = pdfjsLib.getDocument(dwgpath);

    loadingTask.promise.then(function (pdfFile) {
        pdfFile.getPage(1).then(function (page) {
            var viewport = page.getViewport({scale: 1,});
            var scale = canvas.width / viewport.width;
            var scaledViewport = page.getViewport({scale: scale,});
            var renderContext = {
                canvasContext: context,
                viewport: scaledViewport
            };
            var rendertask = page.render(renderContext);
            rendertask.promise.then(function () {
                var renderIniDwg = ini_dwg(rec_Pinfo);
                ini_img.src = canvas.toDataURL("image/png");
                renderIniDwg.promise.then(function () {
                });
            });
        });
    });


    function ini_dwg(rec_Pinfo) {
        tab_tf = true;

//	context.drawImage(ini_img,0,0,canvas.width,canvas.height);
        context.font = '30px verdana';

        drawPage(0, 0, pkg_x, pkg_y,<?php echo $_POST['sn']; ?>);
//	drawMark();

        dwgrefresh(rec_Pinfo);
        PMDataInfo = rec_Pinfo;
        document.getElementById('PM_txt').value = rec_Pinfo;
        document.getElementById('TB_qty').value = TBqty;
        minimap();
        if (rec_Pinfo) {
            Sno++;
        }
        tab_tf = false;
    }

    function Undo_onclick() {
        context.globalAlpha = 1;
        context.drawImage(tmpimage, 0, 0, canvas.width, canvas.height);

        drawPage(0, 0, pkg_x, pkg_y,<?php echo $_POST['sn']; ?>);
//	drawMark();

        if (Sno >= 0) {
            Sno--;
            delRowInTable();
            PMDataInfo = PMDataInfo.replace(addValue[Sno], '');
        }
        document.getElementById('PM_txt').value = PMDataInfo;
        dwgrefresh(PMDataInfo);
        minimap();

    }

    function dwgrefresh(rec_Pinfo) {
        var PMArray = rec_Pinfo.split(';');

        for (var i = 0; i < PMArray.length - 1; i++) {
            var PMData = PMArray[i].split(',');
            if (tab_tf) {
                Sno = i;
            }


            switch (PMData[5]) {
                case 'line' :
                    if (PMData[8] == 'Act') {
                        drawLine(PMData[1], PMData[2], PMData[3], PMData[4]);
                    }
                    break;
                case 'page' :
                    if (PMData[8] == 'Act') {
                        drawPage(PMData[1] * 1, PMData[2] * 1, PMData[3] * 1, PMData[4] * 1, PMData[7]);
                    }
                    if (tab_tf) {
                        addRowInTable(PMData[8], TBqty, PMData[6], PMData[5], PMData[7]);
                    }
                    break;
                case 'mark' :
                    if (PMData[8] == 'Act') {
                        drawPage(PMData[1] * 1, PMData[2] * 1, PMData[3] * 1, PMData[4] * 1, PMData[7]);
                    }
                    if (tab_tf) {
                        addRowInTable(PMData[8], TBqty, PMData[6], PMData[5], PMData[7]);
                    }
                    break;
                case 'blind':
                    if (PMData[8] == 'Act') {
                        drawBlind(PMData[1], PMData[2], PMData[3], PMData[4]);
                    }
                    if (tab_tf) {
                        addRowInTable(PMData[8], TBqty, PMData[6], PMData[5], PMData[7]);
                    }
                    break;
                case 'tbno' :
                    if (PMData[8] == 'Act') {
                        drawTB(PMData[1] * 1, PMData[2] * 1, PMData[3] * 1, PMData[4] * 1, PMData[7] * 1 + TBsum);
                        TBqty = PMData[7] * 1;
                    }
                    if (tab_tf) {
                        addRowInTable(PMData[8], TBqty, PMData[6], PMData[5], PMData[7]);
                    }
                    break;
                default :
                    break;
            }

        }

    }

    function minimap() {
        document.getElementById('preview').src = document.getElementById('cv').toDataURL('image/png');
        tmpimage.src = document.getElementById('cv').toDataURL('image/png');
    }

    function updateData() {
//	minimap();
//	addRowInTable('Act','','','');
        PMDataPush();
    }

    function PMDataPush() {
        PMDataInfo += addValue[Sno];
        document.getElementById('PM_txt').value = PMDataInfo;
        document.getElementById('TB_qty').value = TBqty;
        Sno++;
    }


    function mPush(evt, currentObj) {

        var mouseF = getMousePos(canvas, evt);
        jntxf = Math.round(mouseF.x);
        jntyf = Math.round(mouseF.y);

        canvas.addEventListener('mousedown', function (evt) {
            tmptf = true;
//		context.drawImage(tmpimage,0,0,canvas.width,canvas.height);
//		drawPkgmark(jntxf,jntyf,jntxf,jntyf,tmptf,document.getElementById('pg_no').value);
        }, false);

        canvas.addEventListener('mousemove', function (evt) {

            if (event.button == '0') {
                var mousePos = getMousePos(canvas, evt);
                context.drawImage(tmpimage, 0, 0, canvas.width, canvas.height);
                drawPkgmark(jntxf, jntyf, mousePos.x, mousePos.y, tmptf, document.getElementById('pg_no').value);

            }
        }, false);

        canvas.addEventListener('mouseup', function (evt) {
            tmptf = false;
        }, false);
    }


    function mRelease(evt, currentObj) {

        if (event.button == '0') {

            var mouseT = getMousePos(canvas, evt);
            jntxt = Math.round(mouseT.x);
            jntyt = Math.round(mouseT.y);

            for (var i = 0; i < radio_btn.length; i++) {
                if (radio_btn[i].checked) {
                    markType = radio_btn[i].value;

                    switch (markType) {
                        case 'line' :
                            addValue[Sno] = Sno + ',' + jntxf + ',' + jntyf + ',' + jntxt + ',' + jntyt + ',' + markType + ',,,Act,;';
                            break;
                        case 'page' :
                            addValue[Sno] = Sno + ',' + jntxf + ',' + jntyf + ',' + jntxt + ',' + jntyt + ',' + markType + ',,' + document.getElementById('pg_no').value + ',Act,;';
                            addRowInTable('Act', TBqty, document.getElementById('pg_no').value, markType, document.getElementById('pg_no').value);
                            break;
                        case 'mark' :
                            addValue[Sno] = Sno + ',' + jntxf + ',' + jntyf + ',' + jntxt + ',' + jntyt + ',' + markType + ',,' + document.getElementById('cmnt').value + ',Act,;';
                            addRowInTable('Act', TBqty, document.getElementById('cmnt').value, markType, document.getElementById('cmnt').value);
                            break;
                        case 'blind':
                            addValue[Sno] = Sno + ',' + jntxf + ',' + jntyf + ',' + jntxt + ',' + jntyt + ',' + markType + ',,,Act,;';
                            addRowInTable('Act', TBqty, document.getElementById('pg_no').value, markType);
                            break;
                        case 'tbno' :
                            TBqty = TBqty + 1
                            addValue[Sno] = Sno + ',' + jntxf + ',' + jntyf + ',' + jntxt + ',' + jntyt + ',' + markType + ',DN,' + TBqty + ',Act,;';
                            addRowInTable('Act', TBqty, 'DN', markType);
                            break;
                        default :
                            break;
                    }
                }
            }

            updateData(PMDataInfo);
//		dwgrefresh();
            minimap();
        }

    }


    function drawPkgmark(xf, yf, xt, yt, t_f, pno) {
        if (t_f) {
            for (var i = 0; i < radio_btn.length; i++) {
                if (radio_btn[i].checked) {
                    markType = radio_btn[i].value;

                    switch (markType) {
                        case 'line' :
                            drawLine(xf, yf, xt, yt);
                            break;
                        case 'page' :
                            drawPage(xf, yf, xt, yt, document.getElementById('pg_no').value);
                            break;
                        case 'mark' :
                            drawPage(xf, yf, xt, yt, document.getElementById('cmnt').value);
                            break;
                        case 'blind':
                            drawBlind(xf, yf, xt, yt);
                            break;
                        case 'tbno' :
                            drawTB(xf, yf, xt, yt, TBqty + TBsum + 1);
                            break;
                        default :
                            break;
                    }
                }
            }
        }
    }

    function getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    }


    function drawLine(xf, yf, xt, yt) {
        context.strokeStyle = 'greenyellow';
        context.lineWidth = 20;
        context.globalAlpha = .5;
        context.beginPath();
        context.moveTo(xf, yf);
        context.lineTo(xt, yt);
        context.closePath();

        context.stroke();
        context.globalAlpha = 1;
    }


    function drawMark() {
        var texWid = context.measureText('<?php echo $_POST['pn']; ?>').width / 2;
        context.strokeStyle = 'blue';
        context.globalAlpha = 1;
        context.lineWidth = 3;
        context.beginPath();
        var wtx = 250;
        var hty = 40;
        var tx = 1940;
        var ty = 1000;
        context.moveTo(tx - wtx, ty - hty);
        context.lineTo(tx - wtx, ty + hty);
        context.lineTo(tx + wtx, ty + hty);
        context.lineTo(tx + wtx, ty - hty);
        context.lineTo(tx - wtx, ty - hty);
        context.closePath();
        context.font = 40 + 'px verdana';
        context.fillStyle = "black";
        context.fillText('<?php echo $_POST['pn']; ?>', tx - (context.measureText('<?php echo $_POST['pn']; ?>').width / 2), ty + 10);

        context.stroke();
        context.globalAlpha = 1;
    }


    function drawPage(xf, yf, xt, yt, pno) { //xf,yf,pno) {
//	alert(isNaN(pno));
        context.font = 30 + 'px verdana';

        var tri = 35;
        if (isNaN(pno)) {
            var texWid = context.measureText(pno).width / 2;
            context.globalAlpha = .5;

            context.strokeStyle = "red";
            context.lineWidth = 3;
            context.beginPath();
            context.arc(xt, yt, tri, 0, 2 * Math.PI, false);
            context.fillText(pno, xt - context.measureText(pno).width / 2, yt + 10);
            context.closePath();

        } else {
            var texWid = context.measureText(pno).width / 2;
            var NoX = xt - texWid;
            var NoY = yt + 15;
            context.globalAlpha = .5;

            context.strokeStyle = 'red';
            context.lineWidth = 3;
            context.beginPath();
            context.moveTo(xt, yt - tri);
            context.lineTo(xt - tri, yt + tri / 2);
            context.lineTo(xt + tri, yt + tri / 2);
            context.lineTo(xt, yt - tri);
            context.fillStyle = "blue";
            context.fillText(pno, NoX, NoY - 5);
            context.closePath();

        }
        context.stroke();
        context.globalAlpha = 1;

    }


    function drawBlind(xf, yf, xt, yt) {

        var ang
        if (xf == xt) {
            if ((yt - yf) > 0) {
                ang = Math.PI;
            } else {
                ang = 0;
            }
        } else {
            if ((xf - xt) < 0) {
                ang = Math.atan((yt - yf) / (xt - xf)) + 90 * Math.PI / 180;
            } else {
                ang = Math.atan((yt - yf) / (xt - xf)) - 90 * Math.PI / 180;
            }
        }
        context.save();
        context.globalAlpha = .7;

        context.setTransform(1, 0, 0, 1, 0, 0);
        context.translate(xf, yf);
        context.rotate(ang);

        context.strokeStyle = 'red';
        context.lineWidth = 3;
        context.beginPath();
        context.moveTo(-40, 0);
        context.lineTo(+40, 0);
        /*	context.lineTo(+30,-16);
            context.lineTo(+20,0);*/
        context.lineTo(-20, 0);
        context.lineTo(-30, -16);
        context.lineTo(-40, 0);
        context.fillStyle = "red";
        context.fill();
        context.closePath();

        context.stroke();
        context.restore();
        context.globalAlpha = 1;

    }


    function drawTB(xf, yf, xt, yt, pno) {
        context.font = 30 + 'px verdana';
        if (pno < 10) {
            pno = '0' + pno;
        }
        var tri = 35;
        var texWid = context.measureText(pno).width / 2;
//	var NoX = xt - texWid;
//	var NoY = yt + tri/3;
        context.globalAlpha = .5;

        context.strokeStyle = 'red';
        context.lineWidth = 3;
        context.beginPath();
        context.arc(xt, yt, tri, 0, 2 * Math.PI, false);
        context.fillText('TB', xt - context.measureText('TB').width / 2, yt - 5);
        context.fillText(pno, xt - context.measureText(pno).width / 2, yt + 25);
        context.closePath();

        context.stroke();
        context.globalAlpha = 1;

    }


    function allClear_onclick() {
        context.drawImage(ini_img, 0, 0, canvas.width, canvas.height);
        document.getElementById('PM_txt').value = 'clear';
        $("#n_finish").trigger('click');
    }


    function TBsizemark(jntidx, oldvalue, newvalue) {
//	if(isNaN(newvalue)){alert('Input only digit!');}
//	else{
        var jointArray = PMDataInfo.split(';');
// alert(PMDataInfo);
        PMDataInfo = '';
        for (var j = 0; j < jointArray.length - 1; j++) {
            var tmpsno = oldvalue;
            var tmpsz = newvalue;

            if (j == jntidx) {
                var jointValue = jointArray[j].split(',');
                if (jointValue[5] == 'tbno') {
                    PMDataInfo += jointArray[j].replace('tbno,' + tmpsno + ',', 'tbno,' + tmpsz + ',') + ';';
                } else if (jointValue[5] == 'page') {
                    PMDataInfo += jointArray[j].replace('page,,' + tmpsno + ',', 'page,,' + tmpsz + ',') + ';';
                } else if (jointValue[5] == 'mark') {
                    PMDataInfo += jointArray[j].replace('mark,,' + tmpsno + ',', 'mark,,' + tmpsz + ',') + ';';
                }

            } else {
                PMDataInfo += jointArray[j] + ';';
            }
//		}

            document.getElementById('PM_txt').value = PMDataInfo;
        }
    }

    function delRowInTable() {
        var jntTable = document.getElementById('jnt_table')
        var rowIndex = jntTable.rows.length - 1;
        if (rowIndex > 0) jntTable.deleteRow(rowIndex);
    }

    function trDelete(tabidx, jntidx) {
        canvas.width = canvas.width;
        context.drawImage(ini_img, 0, 0, canvas.width, canvas.height);

        var jointindex = jntidx;
        var jointArray = PMDataInfo.split(';');
        var jointData = jointArray[jointindex].split(',');
//	alert(jntidx);

        if (jointData[8] == 'Act') {
            PMDataInfo = PMDataInfo.replace(jointArray[jointindex], jointArray[jointindex].replace('Act', 'Del'));
            $('#jnt_table tr:eq(' + (tabidx + 1) + ')').css("background-color", "red");
//	alert('act');
        } else {
            PMDataInfo = PMDataInfo.replace(jointArray[jointindex], jointArray[jointindex].replace('Del', 'Act'));
            $('#jnt_table tr:eq(' + (tabidx + 1) + ')').css("background-color", "white");
//	alert('del');
        }

        document.getElementById('PM_txt').value = PMDataInfo;
        dwgrefresh(PMDataInfo);
        minimap();
    }

    function addRowInTable(jntState, tbnum, tbsize, typ, pc) {
        var jntTable = document.getElementById('jnt_table')
        var rowIndex = jntTable.rows.length;
        var sarr = new Array();
        sarr[rowIndex] = Sno;

        var intxt = ''
        var jono = ''
        newTr = jntTable.insertRow(rowIndex);
        newTr.align = 'center';
        if (jntState == 'Del') {
            newTr.style = 'background-color: red';
        }
        newTr.idName = 'newTr' + rowIndex;


        newTd1 = newTr.insertCell(0);
        newTd1.style = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:40px';

        newTd1.innerHTML = ++TableIdx;

        newTd2 = newTr.insertCell(1);
        newTd2.style = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:40px';

        newTd2.innerHTML = Sno;

        newTd3 = newTr.insertCell(2);
        newTd3.style = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:40px';
        if (typ == 'page' || typ == 'mark') {
            newTd3.innerHTML = typ;
            intxt = pc;
        } else {
            newTd3.innerHTML = typ;
            intxt = tbsize
        }
        $(newTd3).on("click", newTd3, function () {
            trDelete(rowIndex - 1, sarr[rowIndex]);
        });

        newTd4 = newTr.insertCell(3);
        newTd4.style = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:40px';
        switch (typ) {
            case 'blind':
                newTd4.innerHTML = '-';
                break;
            case 'tbno':
                newTd4.innerHTML = tbnum + TBsum;
                break;
            case 'page':
                newTd4.innerHTML = '-';
                break;
            default :
                break;
        }

        newTd5 = newTr.insertCell(4);
        newTd5.style = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:40px';
        if (typ == 'tbno' || typ == 'page' || typ == 'mark') {
            var txb3 = document.createElement('input');
            var bftxb = '';
            txb3.type = "text";
            txb3.id = "sno_" + Sno;
            txb3.style = "height:35px; width: 80px; font-size:20px; padding:5px; text-align:center;";

            if (typ == 'tbno') {
                txb3.value = tbsize;
            } else if (typ == 'page' || typ == 'mark') {
                txb3.value = pc;
            }

            if (!txb3.value) {
                $(txb3).css('background-color', 'red');
            }
            ;

            newTd5.appendChild(txb3);

            $(txb3).focusin(function () {
                bftxb = txb3.value;
                txb3.value = '';
                $(txb3).css('background-color', 'white');
            });

            $(txb3).focusout(function () {
                if (!txb3.value) {
                    txb3.value = bftxb;
                }
                ;
                TBsizemark(sarr[rowIndex], intxt, txb3.value);
            });
        }
    }


    function clear_txt(txbx) {

        document.getElementById(txbx).value = '';

    }
</script>

</html>