<?php
ini_set('display_errors', '0');
include_once(__DIR__ . '/../common.php');

include_once(__DIR__ . '/pcs_config.php');
include_once(__DIR__ . '/pcs_common_function.php');
if (!defined('_GNUBOARD_')) exit;

$pnid_file = $_POST['fn'];

	

if (!$_POST['key']) {
	if ($_POST['pnid_txt']) {

		$query_pnid_coor_check = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_no = "'.$_POST['mapped_pnid'].'"';
		$sql_pnid_coor_check = sql_query ($query_pnid_coor_check);
		$sql_pnid_coor_array = sql_fetch_array ($sql_pnid_coor_check);
		$pnid_coor_info = $sql_pnid_coor_array['pnid_coor'];
		
		if ($_POST['pnid_txt']=='clear') {
            $query_pnid_coor = 'DELETE FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_no = "'.$_POST['mapped_pnid'].'" AND pnid_no = "'.$_POST['mapped_pnid'].'"';
            sql_query ($query_pnid_coor);
        } elseif ($pnid_coor_info) {
            $query_pnid_coor = 'UPDATE '.G5_TABLE_PREFIX.'pcs_info_pnid_coor SET pnid_coor = "'.$_POST['pnid_txt'].'", rev_no = "'.$_POST['rev_pnid'].'" WHERE pnid_no = "'.$_POST['mapped_pnid'].'"';
            sql_query ($query_pnid_coor);
        } else {
				$query_pnid_coor = 'INSERT INTO '.G5_TABLE_PREFIX.'pcs_info_pnid_coor SET
										pnid_no = "'.$_POST['mapped_pnid'].'",
										rev_no = "'.$_POST['rev_pnid'].'",
										pnid_coor = "'.$_POST['pnid_txt'].'",
										time = "'.G5_TIME_YMDHIS.'",
										pnid_state = "Marked"';		sql_query ($query_pnid_coor);
			}
	}

echo '
<script>
opener.document.location.reload();
</script>
';
include_once(__DIR__ . '/pcs_mark_masterpnid.php');

}
else{
/*		$query_pkg = 'SELECT * FROM '.G5_TABLE_PREFIX.'pcs_info_package WHERE unit = "'.$_POST['unit'].'"';
		$sql_pkg = sql_query ($query_pkg);*/

		$query_pnid_coor_check = 'SELECT pnid_coor FROM '.G5_TABLE_PREFIX.'pcs_info_pnid_coor WHERE pnid_no = "'.$_POST['fn'].'"';
		$sql_pnid_coor_check = sql_query ($query_pnid_coor_check);
		$sql_pnid_coor_array = sql_fetch_array ($sql_pnid_coor_check);
		$pnid_coor_info = $sql_pnid_coor_array['pnid_coor'];
		


		$pnid_txt = explode(';',$pnid_coor_info);
		foreach($pnid_txt as $pkg_txt){
			$pkg = explode(',',$pkg_txt);
			if($pkg[1] !== '' && $pkg[1] !== '0'){$pkg_array[$pkg[1]] = $pkg[1];}
		}


		$products = array('#FFFFFF','#FF4000','#FF8000','#FFBF00','#FFFF00','#BFFF00','#00FF00','#00FFFF','#00BFFF','#0040FF','#0000FF','#BF00FF','#FF00FF');
		$color_qty = count($products);
		
?>
<html>
<head>
<title> Package Numbering Page </title>

</head>
<script src="../jquery/jquery-3.2.1.min.js"></script>
<script src="../pdfjs_viewer/build/pdf.js"></script>
<script src="../pdfjs_viewer/build/pdf.worker.js"></script>

<body>

<div style='width:100%; height:100%; border:1px solid blue;' >
	<div style = 'overflow:scroll; max-width:100%; height:100%; margin-right:350px; cursor:crosshair;' >
		<canvas id='cv' width='2100' height='1485' style='border:1px solid black;' onmousedown='mPush(event, this)' onmouseup='mRelease(event, this)'> </canvas>
	</div>
	<div style='max-width:350px; position: absolute; top: 10px; right: 10px ; height:98.5%;' >
		<div style='width:99%; height:250px; border:1px solid red;font-size:25px;'>
		
			<form method="post" id='frm1'> 
				<input type='hidden' id='pnid_txt' name='pnid_txt'>
				<input type="hidden" name="mapped_pnid" value="<?php echo $pnid_file;?>">
				<input type="hidden" name="rev_pnid" value="<?php echo $_POST['rev'];?>">
						
				<p align="center">	<input type="submit" id="nClear" name="nClear" value="Mapping finish" style="height:35px; width:150px; font-size:18px;">
							
				<?//if($member['mb_3'] == 'Manager') {?>
				&nbsp; <input type="button" id="allClear" name="allClear" value="Clear all Joints" style="height:35px; width:150px; font-size:18px;" onclick="return allClear_onclick()" />
				<?// } ?>
				</p>
						
			</form> 
			<input type='radio'	name='mark_type' id='rad_line' style="height:25px; width: 25px;" value='line' checked>Line &nbsp; &nbsp; 
			<input type='radio'	name='mark_type' style="height:25px; width: 25px;" value='pkg' >PKG No.<br>
			<input type='radio'	name='mark_type' style="height:25px; width: 25px;" value='blind'>Blind  &nbsp; &nbsp; 
			<input type='text'	name='text_pkg' id='text_pkg' style="height:30px; width: 150px; font-size:18px; padding:5px;" disabled>
<!--			<p align="center"><input type="button" id="Undo" name="Undo" value="Undo" style="height:35px; width:150px; font-size:18px;" onclick="Undo_onclick()" /></p>		-->
			
			<p align="center"><input type='text' name='new_pkg' id='new_pkg' style="height:50px; width: 300px; font-size:18px; padding:5px;" onchange='addRowInTable()'></p>
		</div>

		<div style='overflow:scroll;width:99%; height:55%; border:1px solid yellow;'>
		
			<table id='jnt_table' style="font-size:12px; border-width: 1px 1px 0px 0px; border-style: solid solid none none;" width='100%' border="0" cellspacing="0" cellpadding="0">
				<caption> <span style="font-size: 20px;"> PACKAGE List </span> </caption>
				<tbody>
					<tr style="height: 30px; background-color: aquamarine;">
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 12%" ><p align="center">No</p></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 70%" ><p align="center">PACKAGE</p></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 18%" ><p align="center">Color</p></td>
					</tr>

<?php $pkgqty = 0;
	foreach($pkg_array as $pkg_order){
		if(strpos($pnid_coor_info,$pkg_order)){$trcolor=true;}
?>
					<tr style="height: 30px;">
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 12%" onclick="return erasepkg('<?php echo $pkg_order; ?>')"><p align="center"><?php echo ++$pkgqty; ?></p></td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 70%" ><p align="left">
							<input type='radio'	name='marked_pkg' style='height:25px; width: 25px;' value='<?php echo $pkg_order; ?>' onclick="return filltext()"><?php echo $pkg_order; ?>	</td>
						<td style="border-width: 0px 0px 1px 1px; border-style: none none solid solid; width: 18%" ><p align="center">
							<select id='<?php echo $pkg_order; ?>' style='WIDTH: 95%; height: 30px; font-size:15px;' onchange='return cngcolor()'>
<?php for($i=0;$i<$color_qty;$i++){ ?>
								<option value='<?php echo $i; ?>' style='background-color: <?php echo $products[$i]; ?>;'><?php echo $i; ?></option>
<?php } ?>
							</select>
						
						</td>
					</tr>
			
		
<?php } ?>		
				</tbody>
			</table>

		</div>
		<div style='overflow:scroll;width:100%; height:20%; border:1px solid green;'>
			<img id='preview' src='' width='99.9%' alt='Image come to here.'/>
		</div>
	</div>
</div>

</body>

<script>
var addValue = new Array();
var jointDataInfo = '';
var color_arr = <?php echo json_encode($products)?>; 


var Sno = 0;
var markType = '';
var markPKG = '';
var jntxf = 0;
var jntyf = 0;
var jntxt = 0;
var jntyt = 0;
var tmptf = true;


var radio_btn_mark = document.getElementsByName('mark_type');
var radio_btn_pkg = document.getElementsByName('marked_pkg');

var canvas = document.getElementById('cv');
var context = canvas.getContext('2d');

var rec_Jinfo = '<?php echo $pnid_coor_info;?>';

var image = new Image();	var tmpimage = new Image();

var loadingTask = pdfjsLib.getDocument('<?php echo PCS_PNID_URL;?>/' + '<?php echo $_POST['fn'];?>' + '_' + <?php echo $_POST['rev'];?> + '.pdf');

loadingTask.promise.then(function(pdfFile) {
	pdfFile.getPage(1).then(function(page) {
		var viewport = page.getViewport({ scale: 1, });
		var scale = canvas.width / viewport.width;
		var scaledViewport = page.getViewport({ scale: scale, });
		var renderContext = {
			canvasContext: context,
			viewport: scaledViewport
		};
		var rendertask = page.render(renderContext);
		rendertask.promise.then(function(){
			var renderIniDwg = ini_pnid(rec_Jinfo);
//			ini_img.src = document.getElementById("cv").toDataURL("image/png");
			renderIniDwg.promise.then(function(){});
		});
	});
});

function ini_pnid(Jinfo) {
	dwgrefresh(Jinfo);
	jointDataInfo = Jinfo;
	document.getElementById('pnid_txt').value = Jinfo;
	minimap();
	if(Jinfo){Sno++;}
}

function Undo_onclick() {
	context.globalAlpha = 1;

	if(Sno!=0) {
		Sno--;
		jointDataInfo = jointDataInfo.replace(addValue[Sno],'');
	}
	document.getElementById('pnid_txt').value = jointDataInfo;
	dwgrefresh(jointDataInfo);
	minimap();
}

function dwgrefresh(jData) {
	
	var jointArray = jData.split(';');

	for (var i=0; i<jointArray.length-1; i++) {

		var jointData = jointArray[i].split(',');

			switch (jointData[2]) {
				case 'line' :
					drawLine(jointData[3],jointData[4],jointData[5],jointData[6],jointData[7]);
					break;
				case 'blind':
					drawBlind(jointData[3],jointData[4],jointData[5],jointData[6]);
					break;
				case 'pkg' :
					drawPKG(jointData[1],jointData[3],jointData[4],jointData[5]*1,jointData[6]*1,jointData[7]);
					break;
				default :	break;
			}
	document.getElementById(jointData[1]).value = jointData[7];
	document.getElementById(jointData[1]).style = 'WIDTH: 95%; height: 30px; font-size:15px; background-color:'+color_arr[jointData[7]]+';';
	}

	minimap();
	
}


function mPush(evt, currentObj) {
	
	var mouseF = getMousePos(canvas, evt);
	jntxf = Math.round(mouseF.x);
	jntyf = Math.round(mouseF.y);

	canvas.addEventListener('mousedown', function(evt) {tmptf = true;}, false);

	canvas.addEventListener('mousemove', function(evt) {

		if(event.button=='0'){
			var mousePos = getMousePos(canvas, evt);
			context.drawImage(tmpimage,0,0,canvas.width,canvas.height);
			drawPNIDmark(jntxf,jntyf,mousePos.x,mousePos.y,tmptf,markPKG);

		}
	}, false);

	canvas.addEventListener('mouseup', function(evt) {tmptf = false;}, false);
}


function mRelease(evt, currentObj) {
	if(!document.getElementById('text_pkg').value){alert('SELECT PACKAGE !!!');}
	if(event.button=='0'){
		var mouseT = getMousePos(canvas, evt);
			jntxt = Math.round(mouseT.x);
			jntyt = Math.round(mouseT.y);

			context.drawImage(tmpimage,0,0,canvas.width,canvas.height);

	
	for (var i=0; i<radio_btn_mark.length; i++){
		if(radio_btn_mark[i].checked) {
			markType = radio_btn_mark[i].value;
			markColor = document.getElementById(markPKG).value;
			if(document.getElementById(markPKG).value!='0'){
			switch (markType) {
				case 'line' :
					drawLine(jntxf,jntyf,jntxt,jntyt,markColor);
					break;
				case 'blind':
					drawBlind(jntxf,jntyf,jntxt,jntyt);
					break;
				case 'pkg' :
					drawPKG(markPKG,jntxf,jntyf,jntxt,jntyt,markColor);
					break;
				default :	break;
			}
			}
		}
	}
	addValue[Sno] =	Sno + ',' + markPKG+','+markType+','+jntxf+','+jntyf+','+jntxt+','+jntyt+','+markColor+',;';
		
	}
	if(document.getElementById(markPKG).value!='0'){
	minimap();
	jointDataPush();}
	else {alert('SELECT COLOR !!!');}

}

function drawPNIDmark(xf,yf,xt,yt,t_f,pno) {
if(t_f){
	for (var i=0; i<radio_btn_mark.length; i++){
		if(radio_btn_mark[i].checked) {
			markType = radio_btn_mark[i].value;
		
			switch (markType) {
				case 'line' :
					drawLine(xf,yf,xt,yt,0);
					break;
				case 'blind':
					drawBlind(xf,yf,xt,yt);
					break;
				case 'pkg' :
					drawPKG(pno,xf,yf,xt,yt,0);
					break;
				default :	break;
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



function drawLine(xf,yf,xt,yt,mColor) {
	if(mColor){context.strokeStyle = color_arr[mColor];}
	else {context.strokeStyle = "greenyellow";}
	context.lineWidth = 4;
	context.globalAlpha = .5;
	context.beginPath();
	context.moveTo(xf,yf);
	context.lineTo(xt,yt);
	context.closePath();

	context.stroke();
}


function drawPKG(pn,xf,yf,xt,yt,mColor) {
	context.lineWidth = 1;
	context.globalAlpha = 1;
	if(mColor){context.strokeStyle = color_arr[mColor];}
	else {context.strokeStyle = "greenyellow";}
	context.font = 15+'px verdana';
	var texWid = context.measureText(pn).width/2;
	var NoX = xt - texWid;
	var NoY = yt + 10;
	texWid += 10;
	context.beginPath();
			context.moveTo(xf,yf);
		if((xf-xt)>0){
			context.lineTo(xt+texWid,yt+15);
			context.lineTo(xt-texWid,yt+15);
			context.fillText (pn,NoX,NoY);
		}
		else{
			context.lineTo(xt-texWid,yt+15);
			context.lineTo(xt+texWid,yt+15);
			context.fillText (pn,NoX,NoY);
		}
	context.stroke();		
	context.closePath();


}


function drawBlind(xf,yf,xt,yt) {

	var ang
	if(xf==xt) 	{if((yt-yf)>0)	{ang = Math.PI;} 	else {ang = 0;}}
	else 		{if((xf-xt)<0)	{ang = Math.atan((yt-yf)/(xt-xf)) + 90*Math.PI/180;}	else {ang = Math.atan((yt-yf)/(xt-xf)) - 90*Math.PI/180;}}
	context.save();
	context.globalAlpha = .7;

	context.setTransform(1,0,0,1,0,0);
	context.translate(xf,yf);
	context.rotate(ang);
	
	context.strokeStyle = "red";
	context.lineWidth = 3;
	context.beginPath();
	context.moveTo(-20,0);
	context.lineTo(+20,0);
/*	context.lineTo(+15,-8);
	context.lineTo(+10,0);*/
	context.lineTo(-10,0);
	context.lineTo(-15,-8);
	context.lineTo(-20,0);
	context.fillStyle = "red";
	context.fill();
	context.closePath();

	context.stroke();
	context.restore();

}


function minimap() {
	document.getElementById('preview').src = document.getElementById("cv").toDataURL("image/png");
	tmpimage.src = document.getElementById("cv").toDataURL("image/png");
}


function jointDataPush() {
	jointDataInfo += addValue[Sno];
	document.getElementById('pnid_txt').value = jointDataInfo;
	Sno++;
}


function allClear_onclick(){
	context.drawImage(image,0,0,canvas.width,canvas.height);
	document.getElementById('pnid_txt').value = 'clear';
	$("#nClear").trigger('click');
}

function filltext(){
	for (var j=0; j<radio_btn_pkg.length; j++){
		if(radio_btn_pkg[j].checked) {
			markPKG = radio_btn_pkg[j].value;
			document.getElementById('text_pkg').value = markPKG;
			document.getElementById(markPKG).style = 'WIDTH: 95%; height: 30px; font-size:15px; background-color:'+color_arr[document.getElementById(markPKG).value]+';';
			document.getElementById("rad_line").checked = true;
		}
	}
}
function cngcolor(){
	for (var j=0; j<radio_btn_pkg.length; j++){
		if(radio_btn_pkg[j].checked) {
			markPKG = radio_btn_pkg[j].value;
			document.getElementById(markPKG).style = 'WIDTH: 95%; height: 30px; font-size:15px; background-color:'+color_arr[document.getElementById(markPKG).value]+';';
		}
	}
}


function erasepkg(pkg){
	var pkgdelYN = false;
	dwgMarkedpkg(jointDataInfo, pkg);
	setTimeout(function () {
		pkgdelYN = confirm('Do you want to delete PKG : ' + pkg + ' ?');
		if(pkgdelYN){
			var realry = false;
			realry = confirm('DELETE PKG : ' + pkg + ' ?');
				if(realry){document.getElementById("frm1").submit();}
				else {
					document.getElementById('pnid_txt').value = jointDataInfo;
					dwgrefresh(jointDataInfo);
					
				}
		}
		else {
			document.getElementById('pnid_txt').value = jointDataInfo;
			dwgrefresh(jointDataInfo);
		}
	}, 0);
}

function dwgMarkedpkg(jData, delpkg) {
	canvas.width = canvas.width;
	context.drawImage(image,0,0,canvas.width,canvas.height);
	
	var jointArray = jData.split(';');
	var tmpMarkarray = '';

	for (var i=0; i<jointArray.length-1; i++) {

		var jointData = jointArray[i].split(',');
		if(jointData[1]==delpkg){
			switch (jointData[2]) {
				case 'line' :
					drawLine(jointData[3],jointData[4],jointData[5],jointData[6],jointData[7]);
					break;
				case 'blind':
					drawBlind(jointData[3],jointData[4],jointData[5],jointData[6]);
					break;
				case 'pkg' :
					drawPKG(jointData[1],jointData[3],jointData[4],jointData[5]*1,jointData[6]*1,jointData[7]);
					break;
				default :	break;
			}
		}
		else{
			tmpMarkarray += jointArray[i] + ';';
		}
	document.getElementById('pnid_txt').value = tmpMarkarray;
	}

	minimap();
	
}


function addRowInTable() {
	var jntTable = document.getElementById('jnt_table')
	var rowIndex = jntTable.rows.length;
	var intxt = ''
	var jono = ''
	var tabstyle = 'border-width: 0px 0px 1px 1px; border-style: none none solid solid; border-color: currentColor black; height:20px';

	document.getElementById('text_pkg').value = document.getElementById('new_pkg').value;
	markPKG = document.getElementById('text_pkg').value;
	
	newTr = jntTable.insertRow(rowIndex);
		newTr.align = 'center';
		newTr.idName = 'newTr'+rowIndex;
	

	newTd1 = newTr.insertCell(0);
		newTd1.style = tabstyle;
		var radioPkg = document.createElement('input');
		radioPkg.type = 'radio';
		radioPkg.name = 'selectPKG';
		radioPkg.style="height:25px; width: 25px;";
		radioPkg.value= document.getElementById('new_pkg').value;
		radioPkg.checked = true;
//		radioPkg.addEventListener('click', filltext(document.getElementById('new_pkg').value));
		newTd1.appendChild(radioPkg);


//		$(radioPkg).on("click", radioPkg, filltext(document.getElementById('new_pkg').value));

	newTd2 = newTr.insertCell(1);
		newTd2.style = tabstyle;
		newTd2.innerHTML = document.getElementById('new_pkg').value;

	newTd3 = newTr.insertCell(2);
		newTd3.style = tabstyle;
		var selectCOLOR = document.createElement('select');
		selectCOLOR.id = document.getElementById('new_pkg').value;
		selectCOLOR.style='WIDTH: 95%; height: 30px; font-size:15px;';
		
<?php for($i=0;$i<$color_qty;$i++){ ?>
		var selectOPTION = document.createElement('option');
		selectOPTION.value = '<?php echo $i; ?>';
		selectOPTION.style='background-color: <?php echo $products[$i]; ?>;'
		selectOPTION.innerText = '<?php echo $i; ?>';
		selectCOLOR.appendChild(selectOPTION);		

<?php } ?>


		newTd3.appendChild(selectCOLOR);
		
//<option value='<?php echo $i; ?>' style='background-color: <?php echo $products[$i]; ?>;'><?php echo $i; ?></option>

//	newTd4 = newTr.insertCell(3);
//		newTd4.style = tabstyle;
//		newTd4.innerHTML = rowIndex;
//		newTd4.innerHTML = markPKG;
/*
		var txbSpl = document.createElement('input');
		txbSpl.type = 'text';
		txbSpl.id = 'SPno_' + (rowIndex-2);
		txbSpl.style = 'height:20px; width: 100%; font-size:15px; padding:5px; text-align:center';
		if(spl1==''){txbSpl.value = spl1;}
		else if(spl1.search(dwgNo)<0){txbSpl.value = '<' + right(spl1,2)*1 + '>';}
		else if(txbSpl.value = right(spl1,2)*1) {txbSpl.value = right(spl1,2)*1;}

		if(jntMarkType == 'shop') {
			if(!txbSpl.value) {$(txbSpl).css('background-color','red');};
			newTd4.appendChild(txbSpl);
			$(txbSpl).focusin(function(){
				txbSpl.value = ''; $(txbSpl).css('background-color','white');
				fromtxb = rowIndex-1;
			});
			$(txbSpl).focusout(function(){
				splmark(rowIndex-1,txbSpl.value);
				if(!txbSpl.value) {$(txbSpl).css('background-color','red');};
			});
		}
		else {txbSpl.value = 'N/A'; txbSpl.disabled = true; newTd4.appendChild(txbSpl); $(txbSpl).css('background-color','gray');}
*/

	document.getElementById('new_pkg').value = '';

}
</script>

</html>
<?php } ?>