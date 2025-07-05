<?php
if ($member['mb_2']) {
    ?>
    <script src="<?php echo PCS_LIB_URL; ?>/jsQR/jsQR.js"></script>
    <div id="loadingMessage">Unable to access video stream (please make sure you have a webcam enabled)</div>
    <canvas id="canvas" hidden></canvas>
    <canvas id="canvas_2" hidden></canvas>
    <div id="output" hidden>
        <div style="font-weight: bold; font-size: 20px; color: green; text-align:center;"><span id="outputData">SCAN QRcode</span>
        </div>
    </div>
    <script>
        var lat = '';
        var lon = '';

        var video = document.createElement("video");
        var canvasElement = document.getElementById("canvas");
        var canvas = canvasElement.getContext("2d");
        var canvasElement2 = document.getElementById("canvas_2");
        var canvas2 = canvasElement2.getContext("2d");
        var loadingMessage = document.getElementById("loadingMessage");
        var outputContainer = document.getElementById("output");
        //var outputMessage = document.getElementById("outputMessage");
        var outputData = document.getElementById("outputData");

        function drawLine(begin, end, color) {
            canvas.beginPath();
            canvas.moveTo(begin.x, begin.y);
            canvas.lineTo(end.x, end.y);
            canvas.lineWidth = 4;
            canvas.strokeStyle = color;
            canvas.stroke();
        }

        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({video: {facingMode: "environment"}}).then(function (stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            requestAnimationFrame(tick);
        });

        function tick() {
            loadingMessage.innerText = "??Loading video..."
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                loadingMessage.hidden = true;
//		canvasElement.hidden = false;
                canvasElement2.hidden = false;
                outputContainer.hidden = false;

                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvasElement2.width = <?php if (G5_IS_MOBILE) {
                    echo 'document.body.clientWidth';
                } else {
                    echo '800';
                } ?>;
                if (window.matchMedia('(orientation: portrait)').matches) {
                    canvasElement2.height = canvasElement2.width * 4 / 3;
                } else {
                    canvasElement2.height = canvasElement2.width * 3 / 4;
                }

                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                canvas2.drawImage(canvasElement, 0, 0, canvasElement2.width, canvasElement2.height);
                var code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    var getTable;
                    var scanedtext = code.data.substr(4);
                    drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                    drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                    drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                    drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                    outputData.parentElement.hidden = false;

                    outputData.innerText = scanedtext;
                    if (code.data.substr(0, 3) == 'spl') {
                        getTable = 'spool';
                        location.href = '<?php echo G5_URL?>/app/board/board.php?bo_table=' + getTable + '&stx=' + scanedtext + '&x=' + lat + '&y=' + lon;
                    } else if (code.data.substr(0, 3) == 'dwg') {
                        getTable = 'drawing';
                        location.href = '<?php echo G5_URL?>/app/board/board.php?bo_table=' + getTable + '&stx=' + scanedtext + '&x=' + lat + '&y=' + lon;
                    } else if (code.data.substr(0, 3) == 'pkg') {
                        getTable = 'package';
                        location.href = '<?php echo G5_URL?>/app/board/board.php?bo_table=' + getTable + '&stx=' + scanedtext + '&x=' + lat + '&y=' + lon;
                    } else if (code.data.substr(0, 3) == 'tag') {
                        getTable = 'tp';
                        location.href = '<?php echo G5_URL?>/app/board/board.php?bo_table=' + getTable + '&stx=' + scanedtext + '&x=' + lat + '&y=' + lon;
                    } else {
                        location.reload();
                    }

                }
            }
            requestAnimationFrame(tick);
        }

        <?php
        if(G5_IS_MOBILE && $member['mb_2'] > 1){
        ?>

        $(document).ready(function () {
            getLocation();
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            }
        }

        function showPosition(position) {
            lat = position.coords.latitude;
            lon = position.coords.longitude;
//		alert (position.coords.latitude);
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }
        <?php
        }
        ?>

    </script>
    <?php
} else {
    echo '<p style="text-align:center; font-size:20px;">You have no <font color = red><strong>AUTHORITY</strong></font></p>';
}
?>
