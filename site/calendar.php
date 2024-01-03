<!DOCTYPE html>
<?php
	include("fonctions.php");
	session_start();
	IsDefinedID();
?>
<html>	
	<head>
		<title>plannify</title>
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
 
	<div id="main">
		<header>
			
			<hgroup>
				<h1 class="wow animate__animated animate__fadeInRight"><center>HIKE PLANNER</center></h1>
				<h2 class="wow animate__animated animate__fadeInLeft"><center>Planned routes</center></h2> 
				<center><img width="200" height="200" src="img/map.png"></center>
			</hgroup>
		</header>
	</div>


	<?php
		InitBDD();
		$hostname=$_SESSION['hostname'];
		$username=$_SESSION['username']; 
		$password=$_SESSION['password']; 
		$dbname=$_SESSION['dbname'];
		$usertable=$_SESSION['usertable'];

		$bdd = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8","$username","$password");
		$date = date('y-m-d');
		
		if(isset($_GET["lat"])){
			$lat = $_GET["lat"];
			$lng = $_GET["lng"];
		

			$dep = $lat[0].",".$lng[0]."</br></br></br>";
			if(count($lng) >2){
				$intmd=NULL;
			for ($i = 1; $i < count($lng)-1; $i++) {
				$intermed[$i]=$lat[$i].",".$lng[$i];
				$intmd=$intmd.",".$intermed[$i]; 
				//on crée une chaîne de coordonnées GPS pour les points de passage 
				//pour pouvoir les envoyer et les stocker plus facilement
			}
			}
		
			$arv = $lat[count($lng)-1].",".$lng[count($lng)-1]."</br></br></br>";
			if(count($lng) >2){
			//si un ou plusieurs de points de passage...
			$req= $bdd->prepare("INSERT INTO itineraires( id, latitude_start, latitude_arrived, longitude_start, longitude_arrived, date,via) VALUES(?,?,?,?,?,?,?)");
			$req -> execute([$_SESSION['id'],$lat[0],$lat[count($lng)-1],$lng[0],$lng[count($lng)-1],$date,$intmd]);
			}else{
			//sinon...
			$req= $bdd->prepare("INSERT INTO itineraires( id, latitude_start, latitude_arrived, longitude_start, longitude_arrived, date,via) VALUES(?,?,?,?,?,?,?)");
			$req -> execute([$_SESSION['id'],$lat[0],$lat[1],$lng[0],$lng[1],$date,"NULL"]);
			}
			//header("Location: calendar.php");
		}
		
		$req= $bdd->prepare("SELECT * FROM itineraires WHERE id = ?;");
		$req -> execute([$_SESSION['id']]);
		
		$data = $req ->fetch();
	?>

	<table>
		<tr>
			<th>Start</th>
			<th>End</th>
			<th>Date</th>
			<th>GPS</th>
		</tr>
		<?php
			while($data = $req->fetch())
			{
				$dep = $data["latitude_start"].",".$data["longitude_start"];
				$arv = $data["latitude_arrived"].",".$data["longitude_arrived"];
		?>
		<tr>
			<td><?php echo $dep; ?></td>
			<td><?php echo $arv; ?></td>
			<td><?php echo $data["date"]; ?></td>
			<td>
				<?php
					$urlint="";
					//on récupère chaque coordonnées individuelement 
					//à l'aide du séparateur ','
					$departure = explode(',',$dep);
					$arrival = explode(',',$arv);
					$interm = explode(',',$data["via"]);
					//On affiche les informations dans le tableau
					echo "latdep:".$departure[0]."</br>";
					echo "lngdep:".$departure[1]."</br>";
					echo "latarv:".$arrival[0]."</br>";
					echo "lngarv:".$arrival[1]."</br>";

					//Coordonnées des points de passage et préparation de la concaténation 
					//pour l'envoi dans l'url (lorsqu'on clique sur le lien afficher l'itinéraire)	
					
					if(!isset($lng)){ $lng=[]; }
					if(!isset($lng)){ if(count($lng) >2 or $data['via']!="" or $data['via']!="NULL"){
						for ($i = 1; $i < count($interm); $i++){
							if($i%2 == 0){
								echo "lnginterm(".$i.")".$interm[$i]."</br>";
								$urlint=$urlint."&lng[]=".$interm[$i]; 
							}else{
								echo "latinterm(".$i.")".$interm[$i]."</br>";
								$urlint=$urlint."&lat[]=".$interm[$i];
							}
						}
					}else{
						$urlint="";
					}	}			
				?>
			</td>
			<!--Lien d'affichage et de suppression des trajets -->
			<td><?php echo('<a href="map.php?lat[]='.$departure[0].'&lng[]='.$departure[1].$urlint.'&lat[]='.$arrival[0].'&lng[]='.$arrival[1].'">');?><center>Afficher</center></a></td>
			<td><?php echo('<a href="redirect2.php?delitin='.$data["id_itineraires"].'">');?><center><img width="50" height="50" src="img/delete.png"></center></a></td>
		</tr>
		<?php
		}
		?>
	</table>

</html>
