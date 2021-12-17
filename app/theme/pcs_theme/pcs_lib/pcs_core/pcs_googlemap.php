<!DOCTYPE html>
<html>
<body>

<p>Spool Location</p>

<div id="mapholder" style="width:100%; height:100vh;"></div>

<script src="https://maps.google.com/maps/api/js?key=<?php include_once ('./pcs_config.php');	echo PCS_GGL_API; ?>"></script>

<script>
    var oblat = <?php echo $_GET['lat']; ?>*1+0.001;
    var oblon = <?php echo $_GET['lon']; ?>*1+0.001;



window.onload = function () {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function showPosition(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
			
			var splpos = new google.maps.LatLng(<?php echo $_GET['lat']; ?>*1, <?php echo $_GET['lon']; ?>*1);
			var centerpos = new google.maps.LatLng((pos.lat+<?php echo $_GET['lat']; ?>)/2, (pos.lng+<?php echo $_GET['lon']; ?>)/2);
			
			var mapholder = document.getElementById('mapholder');

			var myOptions = {
				center:centerpos,zoom: <?php if(!G5_IS_MOBILE) {echo '17';} else {echo '19';} ?>,
				mapTypeId:google.maps.MapTypeId.ROADMAP,
				mapTypeControl:false,
				navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
			};
		
			var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
		
			var map = new google.maps.Map(document.getElementById("mapholder"), myOptions);
		
			var marker = new google.maps.Marker({
				position: splpos,
				label: 'S',
				map: map
			});
			
			var contentString = '<span style ="font-size:50px;">'+calcDistance(pos.lat,pos.lng,<?php echo $_GET['lat']; ?>*1,<?php echo $_GET['lon']; ?>*1)+'</span>'
			
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			infowindow.open(map, marker);
	
		
			var marker = new google.maps.Marker({
				position: pos,
				icon: iconBase + 'man.png',
				map: map
			});
	
			var flightPath = new google.maps.Polyline({
				path:[splpos,pos],
				strokeColor:"#0000FF",
				strokeOpacity:0.8,
				strokeWeight:2
			});
			flightPath.setMap(map);
		}, showError);
	}

}

function showError(error) {
	switch(error.code) {
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

function calcDistance(lat1, lon1, lat2, lon2){

	var EARTH_R, Rad, radLat1, radLat2, radDist; 
	var distance, ret;
			
	EARTH_R = 6371000.0;
	Rad 	= Math.PI/180;
	radLat1 = Rad * lat1;
	radLat2 = Rad * lat2;
	radDist = Rad * (lon1 - lon2);

	distance = Math.sin(radLat1) * Math.sin(radLat2);
	distance = distance + Math.cos(radLat1) * Math.cos(radLat2) * Math.cos(radDist);
	ret 	 = EARTH_R * Math.acos(distance);

	var rtn = Math.round(Math.round(ret) / 1000);

	if(rtn <= 0){rtn = Math.round(ret) + " m";}
	else{rtn = rtn + " km";}

	return  rtn;

}

</script>

</body>
</html>
