<!DOCTYPE html>
<html>
<body>

<p>Spool Location</p>

<div id="mapholder" style="width:100%; height:100vh;"></div>

<script src="https://maps.google.com/maps/api/js?key=<?php
    include_once(__DIR__ . '/pcs_config.php');
    echo PCS_GGL_API; ?>"></script>
<script>
    const no = [];
    const lat = [];
    const lon = [];
    let i = 0;
    <?php

    $no = $_POST['no'];
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];
    $sumlat = 0;
    $sumlon = 0;
    $length = count($_POST['no']);
    $qty = $length + 1;
    echo 'var length = '.$length.';';

    for ($i = 0; $i < $length; $i++) {
        $j = $i + 1;
        if ( ! $lat[$j] || ! $lon[$j]) {
            $no[$j]  = 0;
            $lat[$j] = 0;
            $lon[$j] = 0;
            $qty--;
        } else {
            $sumlat += $sumlat + $lat[$j];
            $sumlon += $sumlon + $lon[$j];
        }
        echo '
		no['.$i.'] = '.$no[$j].';
		lat['.$i.'] = '.$lat[$j].';
		lon['.$i.'] = '.$lon[$j].';
	';
    }
    ?>

window.onload = function () {

			var splpos = new google.maps.LatLng(<?php echo $sumlat/$qty; ?>, <?php echo $sumlon/$qty; ?>);
			
			var mapholder = document.getElementById('mapholder');

			var myOptions = {
				center:splpos,zoom:17,
				mapTypeId:google.maps.MapTypeId.ROADMAP,
				mapTypeControl:false,
				navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
			};
		
			var map = new google.maps.Map(document.getElementById("mapholder"), myOptions);

	for(i=0;i<length;i++){
		if(no[i]===0){}
		else {
			var eachspl = new google.maps.LatLng(lat[i], lon[i]);
			var marker = new google.maps.Marker({
				position: eachspl,
				map: map
			});
			var contentString = String(no[i]);
			
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			infowindow.open(map, marker);
		}
	}
}


</script>

</body>
</html>
