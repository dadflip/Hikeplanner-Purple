<!DOCTYPE html>
<html>
	<?php
		include ("fonctions.php");
		session_start();

		//Initialise les infos de connexion à la base de données
		InitBDD();
		
		$hostname=$_SESSION['hostname'];
		$username=$_SESSION['username']; 
		$password=$_SESSION['password']; 
		$dbname=$_SESSION['dbname'];
		$usertable=$_SESSION['usertable'];
		$dsn = "mysql:host=$hostname;dbname=$dbname";
		$bdd = new PDO($dsn, $username, $password);
			
		
		//Moyen de déplacement et Niveau de l'utilisateur... nécessaire pour les calculs de distance et de temps
	
		$req = $bdd->prepare("SELECT Level FROM personal_data WHERE id=?;");
		$req->execute([$_SESSION['id']]);
		$data = $req->fetch(); 
		if($data == false){ 
			$data['Level'] = 0;
		}else{
			$_SESSION['Level']=$data['Level'];
			
		}

		if($data['Level']=="bikehard" or $data['Level']=='bikemed' or $data['Level']=='bikeeasy'){
			$_SESSION['footorbike'] = 'By Cycling';
		}else{
			$_SESSION['footorbike'] = 'By Walking';
		}

		$req = $bdd->prepare("SELECT name FROM inscrits WHERE id=?;");
		$req->execute([$_SESSION['id']]);
		$namusr = $req->fetch(); 
		$_SESSION['namusr']=$namusr['name'];

		//Redirige vers la page de connexion si l'id de session n'est pas défini
		IsDefinedID();
	?>
	<head>
		<title>home</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css"> 
		<!-- Ces deux balises link sont à insérer entre les deux balises existantes -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
        <?php AnimateCSS();?>
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
	<?php WowJS(); ?>

	<!--Scripts pour le menu de navigation-->
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

	<!--Carte-->

    <script type="text/javascript">

		// General parameters
		const saveBaseUrl = 'circuits.php';
		const saveBaseUrl2 = 'calendar.php';
		const mapboxApiKey = 'pk.eyJ1IjoiZml0ejQ1IiwiYSI6ImNqbGM4aTllaTJoeGMza3FraHRkYml4MHoifQ.VCdlDhvEJrxsOBlG1QqEBQ';
		
		//Centrage de la carte...
		const init_lat = 47.58825539047638;
		const init_lon =  6.86631158092425;
		const init_end_lat = 47.58825539047638;
		const init_end_lon = 6.86631158092425;

		//level
		var level = <?php echo json_encode($_SESSION['level']); ?>; 
		var bikeorfoot = <?php echo json_encode($_SESSION['footorbike']); ?>;
		

		// Find waypoints in URL
		let urlParams = new URLSearchParams(window.location.search);
		if(urlParams.has('lat') && urlParams.has('lng')) {
			let lat = urlParams.getAll('lat');
			let lng = urlParams.getAll('lng');
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
				}else{ // if level undifined...
                    var velocity=4; // we take an average speed of 4km/h
                }
                
                //alert: time and distance in km and minutes
                if(Math.round((distance/velocity)*60) <= 60){
                    document.getElementById('input').innerHTML = bikeorfoot+'</br>'+'Level: '+level+': '+'</br>'+'Total distance is ' + dist + ' km and total time is ' + Math.round((distance/velocity)*60) + ' minutes';
                }else{
                    document.getElementById('input').innerHTML = bikeorfoot+'</br>'+'Level: '+level+': '+'</br>'+'Total distance is ' + dist + ' km and total time is ' + Math.trunc(distance/velocity) + ' hours ' + Math.round(((distance/velocity)-(Math.trunc(distance/velocity)))*60) + ' minutes ';    
                }
            });
                
			routingControl.addTo(my_map);

			// Create additional buttons
			let container = document.getElementsByClassName('leaflet-routing-container')[0]; //champ
			
			let confirmButton = L.DomUtil.create('button', 'btn-routing-save', container); //création d'un bouton
			document.getElementsByClassName('btn-routing-save')[0].textContent = 'Save a Completed Road';
			
			let confirmButton2 = L.DomUtil.create('button', 'btn-routing-save', container); //création d'un bouton
			document.getElementsByClassName('btn-routing-save')[1].textContent = 'Plannify Your Road';

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

			L.DomEvent.on(confirmButton2, 'click', function() {
				let customWaypoints = routingControl.getWaypoints();
				let customWaypointsLatLng = customWaypoints.map(function(it) { return it.latLng; });
				let saveUrl = saveBaseUrl2 + '?';
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
	
	<!-- Menu de Navigation -->
	<?php
		PrintNav();
	?>
 
	<!-- Body -->
	<body>
		<div id="main">
			<header>
				<hgroup>
					<h2 style="color:red"><center>
						<?php
							if($_SESSION['level']==""){
								echo "PLEASE COMPLETE YOUR PROFILE !";
							}
						?>
					</center></h2>
					<h1 class="wow animate__animated animate__fadeInRight" title="hikeplanner 2022"><center>HIKE PLANNER HOME</center></h1>
					<h2 class="wow animate__animated animate__fadeInLeft"><center>hello <?php echo $_SESSION['namusr']; ?> !</center></h2>
				</hgroup>
			</header>

			<!-- Affiche les données de temps de parcours et la distance -->
			<center><h3><span id="input"></span>...</h3></center>
			
			<div class="wow animate__animated animate__fadeInRight" id="map">
				<!-- Affiche la carte n°1 -->
			</div>

			
			<h2 class="wow animate__animated animate__fadeInRight"><center>Hiking Routes</center></h2>

			<!--Carte n°2 avec les grandes routes de randonnée -->
			<iframe class="wow animate__animated animate__fadeInLeft" border-color="black" width="97%" height="700px" frameborder="1" allowfullscreen src="//umap.openstreetmap.fr/fr/map/chemins-de-grande-randonnee_51389?scaleControl=false&miniMap=false&scrollWheelZoom=false&zoomControl=true&allowEdit=false&moreControl=true&searchControl=null&tilelayersControl=null&embedControl=null&datalayersControl=true&onLoadPanel=undefined&captionBar=false"></iframe><p><a href="//umap.openstreetmap.fr/fr/map/chemins-de-grande-randonnee_51389">Voir en plein écran</a></p>
		</div>
	</body>
</html>