<?php
	include("fonctions.php");
	session_start();
	IsDefinedID();
?>

<html>
	<head>
		<title>routes</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" href="css/users.css">
		<?php AnimateCSS(); ?>

	</head>

	<?php WowJS(); ?>

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

	<?php
		PrintNav();
	?>


	<body>
		<header>
			<hgroup>
				<h1 class="wow animate__animated animate__fadeInRight"><center>ROUTES</center></h1>
				<h2 class="wow animate__animated animate__fadeInLeft"><center>Add a completed route</center></h2>
				<center><img width="200" height="200" src="img/hiking.png"></center>
			</hgroup>
		</header>


		<br/>

		<?php
			InitBDD();
			$hostname=$_SESSION['hostname'];
			$username=$_SESSION['username']; 
			$password=$_SESSION['password']; 
			$dbname=$_SESSION['dbname'];
			$usertable=$_SESSION['usertable'];

			$dsn = "mysql:host=$hostname;dbname=$dbname";
			$bdd = new PDO($dsn, $username, $password);

			if(isset($_GET["lat"])){
				$lat = $_GET["lat"];
				$lng = $_GET["lng"];
				$dist = $_GET["dist"];//Récupération de la distance dansl'URL
				$dep = $lat[0].",".$lng[0];
					
				if(count($lng) >2){ 
					//Si la taille de la table contenant les longitudes
					//est supérieure à 2, alors il y a des points de passage
					$intmd=NULL;
					for ($i = 1; $i < count($lng)-1; $i++) {
						$intermed[$i]=$lat[$i].",".$lng[$i];
						$intmd=$intmd.",".$intermed[$i];
						//On concatène longitudes et latitude des point de passage 
						//sous forme de liste à ',' pour les manipuler plus facilement...
					}
					$intermediaire = $intmd;
				}
				$arv = $lat[count($lat)-1].",".$lng[count($lng)-1];
			}
		?>

		<!--Formulaire pour entrer un trajet effectué -->
		<form class="wow animate__animated animate__fadeInUp" method="post" action="circuits.php">
				<label>
						<p>
						<center>
							
							date</br>       
							<input style="width: 50%" name="date" type="date" ><br/><br/>
							time</br>        
							<input style="width: 50%" name="time" type="int" placeholder="hh:mm:ss"><br/><br/>
							Depart(coordonnées GPS)</br>						
							<input style="width: 50%" name="coorddep" type="float" value="<?php if(isset($_GET["lat"])){ echo $dep; } ?>" >  <br/><br/>
							Etapes intermediaire(coordonnées GPS)</br>
							<input style="width: 50%" placeholder="entrez les coordonnees sous forme de liste (ex: lat1,lon1,lat2,lon2...)" name="coordint" type="float" value="<?php if(isset($_GET["lat"]) and count($lng) >2){ echo $intmd; } ?>">  <br/><br/>
							Arrivee(coordonnées GPS)</br>
							<input style="width: 50%" name="coordarr" type="float" value="<?php if(isset($_GET["lat"])){ echo $arv; } ?>">  <br/><br/>
							Distance (en km)</br>
							<input style="width: 50%" name="dist" type="float" value="<?php if(isset($_GET["lat"])){ echo $dist; } ?>">  <br/><br/>
							feeling</br>     
							<label for="feeling"></label>
							<select style="width: 50%" id="feeling" name="feeling">
								<option value="hard">hard</option>
								<option value="ok">ok</option>
								<option value="great">great</option>
							</select><br/><br/>

							weather</br>    
							<label for="weather"></label>
							<select style="width: 50%" id="weather" name="weather">
								<option value="sunny">sunny</option>
								<option value="cloudy">cloudy</option>
								<option value="foggy">foggy</option>
								<option value="rainy">rainy</option>
								<option value="snowy">snowy</option>
							</select><br/> <br/><br/>
							<input class="button" type="submit" value="AJOUTER">
						</center>
						</p>
				</label>
		</form>

		<?php
			//Récupération des données du formulaire ci-dessus
			if (isset($_POST["date"])) {
				$weather = $_POST["weather"];
				$feeling = $_POST["feeling"];
				$date = $_POST["date"];
				$time = $_POST["time"];
				$dep = explode(',',$_POST['coorddep']);
				$arv = explode(',',$_POST['coordarr']);
				$interm = $_POST['coordint'];
				$dist = $_POST["dist"];
				if ($interm==',' or $interm==''){
					$interm = "NULL";
				}
				//Requête vers la base de données pour insérer les informations entrées dans le formulaire
				$req = $bdd->prepare("INSERT INTO circuits(id, date, time, feeling, weather,latitude_start, latitude_arrived, longitude_start, longitude_arrived,via,distance) VALUES (?,?,?,?,?,?,?,?,?,?,?);");
				$req->execute([$_SESSION['id'],$date,$time,$feeling,$weather,$dep[0],$arv[0],$dep[1],$arv[1],$interm,$dist]);
			} 
		?>

		<!-- Affichage des informations-->
		<div>
			<header>
			<hgroup>
				<h1 class="wow animate__animated animate__fadeinUp"><center>SELECTED ROUTE...</center></h1>
			</hgroup>
			</header>

			<?php
				//Récupération des coordonnées GPS dans l'URL
				if(!isset($_GET['lat']) or  !isset($_GET['lng'])){
					$lat = "";
					$lng = "";
					$dep = "";
					$arv = "";
				}else{
					$lat = $_GET["lat"];
					$lng = $_GET["lng"];

					$dep = $lat[0].",".$lng[0];
					$intermed=[];
					for ($i = 1; $i < count($lng)-1; $i++) {
						$intermed[$i]=$lat[$i].",".$lng[$i]."</br>";
					}
					$arv = $lat[count($lat)-1].",".$lng[count($lng)-1];
				}
			?>
			<table class="wow animate__animated animate__fadeinUp">
				<thead>
					<tr>
						<th>Start</th>
						<th>Via..</th>
						<th>End</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td><?php echo $dep; ?></td>
						<td><?php 
						if(!isset($lng)){
						for ($i = 1; $i < count($lng)-1; $i++) {
							echo $intermed[$i];
						}};?></td>
						<td><?php echo $arv; ?></td>
					</tr>
				</tbody>
			</table>

			<header>
			<hgroup>
				<h1 class="wow animate__animated animate__fadeinUp"><center>COMPLETED ROUTES...</center></h1>
			</hgroup>
			</header>

			</br>

			<?php
				$bdd = new PDO($dsn, $username, $password);
				$req = $bdd->prepare("SELECT * FROM circuits where id = ?;");
				$req->execute([$_SESSION['id']]);
			?>

			<table class="wow animate__animated animate__fadeinUp">
				<tr>
					<th><img width="30" height="30" src="img/calendar.png"></th>
					<th><img width="30" height="30" src="img/clock.png"></th>
					<th><img width="30" height="30" src="img/happy-face.png"></th>
					<th><img width="30" height="30" src="img/cloudy-day.png"></th>
					<th>DISTANCE</th>
				</tr>
				
				<?php
					while($data = $req->fetch())
					{
						$dep = $data["latitude_start"].",".$data["longitude_start"];
						$arv = $data["latitude_arrived"].",".$data["longitude_arrived"];
						?>
						<tr>
							<td><?php echo $data["date"]; ?></td>
							<td><?php echo $data["time"]; ?></td>
							<td><?php echo $data["feeling"]; ?></td>
							<td><?php echo $data["weather"]; ?></td>
							
								<?php
									$urlint="";
									$departure = explode(',',$dep);
									$arrival = explode(',',$arv);
									$interm = explode(',',$data["via"]);

									if($interm[0]=="NULL"){
										$urlint="";
									}else{
										//$interm[0]=0;
										for ($i = 1; $i < count($interm); $i++){
											if($i%2 == 0){
												$urlint=$urlint."&lng[]=".$interm[$i]; 
											}else{
												$urlint=$urlint."&lat[]=".$interm[$i];
											}
										}
									}				
								?>
							<td><?php echo $data["distance"]; ?></td>
							<td ><?php echo('<a href="map.php?lat[]='.$departure[0].'&lng[]='.$departure[1].$urlint.'&lat[]='.$arrival[0].'&lng[]='.$arrival[1].'">');?><center><img width="30" height="30" src="img/see.png"></center></a></td>
							<td><?php echo('<a href="redirect3.php?delcirc='.$data["id_circuits"].'">');?><center><img width="30" height="30" src="img/delete.png"></center></a></td>
						</tr>
					<?php
					}
					?>
			</table>

			</br>
			
			<header>
			<hgroup>
				<h1 class="wow animate__animated animate__fadeinUp"><center>Routes you have ridden the most</center></h1>
			</hgroup>
			</header>

			</br>

			<?php
				$bdd = new PDO($dsn, $username, $password);
				$req = $bdd->prepare("SELECT * ,COUNT(*) as count FROM circuits where id = ? GROUP BY latitude_start,longitude_start,latitude_arrived,longitude_arrived,via  ORDER BY time ASC;");
				$req->execute([$_SESSION['id']]);
			?>

			<table class="wow animate__animated animate__fadeinUp">
				<tr>
					<th>COMPLETED X</th>
					<th><img width="30" height="30" src="img/calendar.png"></th>
					<th><img width="30" height="30" src="img/clock.png"></th>
					<th>DISTANCE</th>
				</tr>
				
				<?php
					while($data = $req->fetch())
					{
					?>
						<tr>
							<td><?php echo $data["count"]; ?></td>
							<td><?php echo $data["date"]; ?></td>
							<td><?php echo $data["time"]; ?></td>
							<td><?php echo $data["distance"]; ?></td>
						</tr>
					<?php
					}
					?>
			</table>



			<header>
			<hgroup>
				<h1 class="wow animate__animated animate__fadeinUp"><center>YOUR BEST TIME</center></h1>
			</hgroup>
			</header>

			</br>
			
			<?php
				$bdd = new PDO($dsn, $username, $password);
				$req = $bdd->prepare("SELECT id_circuits,weather,time,latitude_start,latitude_arrived,longitude_start,longitude_arrived,via, COUNT(*) as count FROM circuits WHERE id = ? GROUP BY latitude_start,longitude_start,latitude_arrived,longitude_arrived,via ORDER BY time ASC;");
				$req->execute([$_SESSION['id']]);
				$data2 = $req->fetch();
				$req->closeCursor();
			?>

			<table class="wow animate__animated animate__fadeinUp">
				<tr>
					<th>COMPLETED X</th>
					<th>BEST TIME</th>
					<th>START</th>
					<th>END</th>
				</tr>
				
				<tr>
					<?php if($data2 != false){ ?>

					<td><?php echo $data2["count"];  ?></td>
					<td><?php echo $data2["time"]; ?></td>
					<td><?php echo $data2["latitude_start"].",".$data2["longitude_start"];  ?></td>
					<td><?php echo $data2["latitude_arrived"].",".$data2["longitude_arrived"]; ?></td> <?php } ?>
				</tr>
			</table>

			<header>
			<hgroup>
				<h1 class="wow animate__animated animate__fadeinUp"><center>DISCOVER OTHER ROUTES...</center></h1>
				<h2 class="wow animate__animated animate__fadeinUp"><center>Let's Go !</center></h2>
			</hgroup>
			</header>
			
			
			<iframe class="wow animate__animated animate__fadeinUp" border-color="black" width="97%" height="700px" frameborder="1" allowfullscreen src="//umap.openstreetmap.fr/fr/map/chemins-de-grande-randonnee_51389?scaleControl=false&miniMap=false&scrollWheelZoom=false&zoomControl=true&allowEdit=false&moreControl=true&searchControl=null&tilelayersControl=null&embedControl=null&datalayersControl=true&onLoadPanel=undefined&captionBar=false"></iframe><p><a href="//umap.openstreetmap.fr/fr/map/chemins-de-grande-randonnee_51389">Voir en plein écran</a></p>

		</div>

	</body>

</html>


