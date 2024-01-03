<!DOCTYPE html>
<?php

	include ("fonctions.php");
	session_start();
	
	//Redirige vers la page de connexion si l'id de session n'est pas défini
	IsDefinedID();

	//Récupération de coordonnées dans l'URL
	$lat = $_GET["lat"];
	$lng = $_GET["lng"];
?>

<html>

	<head>
		<title>map</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css"> 
		<!-- Ces deux balises link sont à insérer entre les deux balises existantes -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
        
        <style type="text/css">
            #map{
                height:800px;
				width:97%;
				border:solid;
            }
			.btn-routing-save {
				text-align: center;
				width: 100%;
			}
        </style>
	    <link rel="stylesheet" href="css/users.css">

	</head>

	<!-- Javascript files -->
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
	<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
	<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
	
	<!-- Carte -->
    <script type="text/javascript">
		// General parameters
		const saveBaseUrl = 'circuits.php';
		//const saveBaseUrl2 = 'calendar.php';
		const mapboxApiKey = 'pk.eyJ1IjoiZml0ejQ1IiwiYSI6ImNqbGM4aTllaTJoeGMza3FraHRkYml4MHoifQ.VCdlDhvEJrxsOBlG1QqEBQ';
		const init_lat = 47.58825539047638;
		const init_lon =  6.86631158092425;

		//const init_end_lat = 47.58825539047638;
		//const init_end_lon = 6.86631158092425;

		//level
		var level = <?php echo json_encode($_SESSION['level']); ?>; 
		var bikeorfoot = <?php echo json_encode($_SESSION['footorbike']); ?>;

			
		// Find waypoints in URL
		let urlParams = new URLSearchParams(window.location.search);
		if(urlParams.has('lat[]') && urlParams.has('lng[]')) {
			let lat = urlParams.getAll('lat[]');
			let lng = urlParams.getAll('lng[]');
			start_waypoint_lat = lat[0];
			start_waypoint_lon = lng[0];
			waypoints = lat.map(function(it, index) { return L.latLng(it, lng[index]); });
		} else {
			// Initializing with latitude and longitude city choosen (center of the map)
			start_waypoint_lat = init_lat;
			start_waypoint_lon = init_lon;
	        waypoints = [L.latLng(init_lat, init_lon), L.latLng(init_end_lat, init_end_lon)];
		}
            
        var my_map = null;
        // Initialize the map
        function initMap() {
            // Create "my_map" and insert it in the HTML element with ID "map"
            my_map = L.map('map').setView([start_waypoint_lat, start_waypoint_lon], 11); //le centre de la carte et le zoom

            // Set up Leaflet to use OpenStreetMap with Mapbox for routing
            L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            }).addTo(my_map);
				
			const router = L.Routing.mapbox(mapboxApiKey, {
				profile : 'mapbox/walking',
				language: 'fr'
			});
				
			let routingControl = L.Routing.control({
				waypoints: waypoints,
				routeWhileDragging: true,
				router: router,
				geocoder: L.Control.Geocoder.nominatim()
                    
			});

			routingControl.on('routesfound',function(e){
                var routes=e.routes;
                var summary = routes[0].summary;
                distance = summary.totalDistance/1000; //in km
				window.dist = distance;
				//foot
                if (level == 'hard'){
                    var velocity=5; // we take an average speed of 5km/h
                }else if(level == 'medium'){
                    var velocity=4; // we take an average speed of 4km/h
                }else if(level == 'easy'){
                    var velocity=3; // we take an average speed of 3km/h

				//bike
				}else if(level == 'bikehard'){
                    var velocity=30; // we take an average speed of 30km/h
				}else if(level == 'bikemed'){
                    var velocity=20; // we take an average speed of 20km/h
				}else if(level == 'bikeeasy'){
                    var velocity=15; // we take an average speed of 15km/h
				
				//undifined
				}else if (level == ''){ // if level undifined...
                    var velocity=4; // we take an average speed of 4km/h
                }
                
                //alert: time and distance in km and minutes
                if(Math.round((distance/velocity)*60) <= 60){
                    document.getElementById('input').innerHTML = bikeorfoot+'-> '+'Level: '+level+': '+'</br>'+'Total distance is ' + dist + ' km and total time is ' + Math.round((distance/velocity)*60) + ' minutes';
                }else{
                    document.getElementById('input').innerHTML = bikeorfoot+'-> '+'Level: '+level+': '+'</br>'+'Total distance is ' + dist + ' km and total time is ' + Math.trunc(distance/velocity) + ' hours ' + Math.round(((distance/velocity)-(Math.trunc(distance/velocity)))*60) + ' minutes ';    
                }
            });
                
			routingControl.addTo(my_map);
            let customWaypoints = routingControl.getWaypoints();
			let customWaypointsLatLng = customWaypoints.map(function(it) { return it.latLng; });
			

            // Create additional buttons
			let container = document.getElementsByClassName('leaflet-routing-container')[0]; //champ
			let confirmButton = L.DomUtil.create('button', 'btn-routing-save', container); //création d'un bouton
			document.getElementsByClassName('btn-routing-save')[0].textContent = 'Circuit effectué!';

			L.DomEvent.on(confirmButton, 'click', function() {
				let customWaypoints = routingControl.getWaypoints();
				let customWaypointsLatLng = customWaypoints.map(function(it) { return it.latLng; });
				let saveUrl = saveBaseUrl + '?';
				// Concat latitudes
				saveUrl += ('lat[]=' + customWaypointsLatLng[0].lat);
				for (let i = 1; i < customWaypointsLatLng.length; i++) {
					saveUrl += ('&lat[]=' + customWaypointsLatLng[i].lat);
				}
				// Concat longitudes
				for (let i = 0; i < customWaypointsLatLng.length; i++) {
					saveUrl += ('&lng[]=' + customWaypointsLatLng[i].lng);
				}
				saveUrl += ('&dist=' + dist);	//ajout de la distance à l'url
				document.location.href = saveUrl;
			});

				
        }
        window.onload = function(){
			// Initialize the map once the DOM is loaded
			initMap(); 
        };
	</script>

	<script>
		function openNav() {
		    document.getElementById("sideNavigation").style.width = "250px";
		    document.getElementById("main").style.marginLeft = "250px";
		}
 
		function closeNav() {
		    document.getElementById("sideNavigation").style.width = "0";
		    document.getElementById("main").style.marginLeft = "0";
		}
		
	</script>


	<!-- Nav -->
	<?php
		PrintNav();
	?>
 
	<!-- Body -->
	<body>
		<div id="main">
			<!-- Add all your websites page content here  -->
			<header>
				<hgroup>
					<h1><center>MAP</center></h1>
				</hgroup>
			</header>
			
			<div id="map">
				<!-- Here we will have the map -->
			</div>
			<center><h3><span id="input"></span>...</h3></center>
		</div>
	</body>
</html>