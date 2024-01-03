<?php
	include("fonctions.php");
	session_start();
	IsDefinedID();
    InitBDD();
	$hostname=$_SESSION['hostname'];
	$username=$_SESSION['username']; 
	$password=$_SESSION['password']; 
	$dbname=$_SESSION['dbname'];
	$usertable=$_SESSION['usertable'];

	$dsn = "mysql:host=$hostname;dbname=$dbname";
    $name = $_GET['viewusr'];

    $pdo = new PDO($dsn, $username, $password);
    $req = $pdo->prepare("SELECT id FROM inscrits WHERE name = ?;");
	$req->execute([$name]);
    $id_f = $req->fetch();//l'id de l'ami dont on veut voir le profil
?>

<!DOCTYPE html>
<html>

	<head>
		<title>profiles</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" href="css/users.css">

	</head>

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

	<script>
		function macopie1() {
		var copyText = document.getElementById("macopie1");
		copyText.select();
		document.execCommand("Copy");
		alert("Le code est copié dans le presse papier");   
		} 
	</script>

    <!-- Nav -->
	<?php
		PrintNav();	
	?>
 
	<div>
		<header>
			<hgroup>
				<?php echo('<h1><center>Profile of '.$name.'');?></center></h1>
			</hgroup>
		</header>


	    <body>
            <?php
                $bdd = new PDO($dsn, $username, $password);
                $req = $bdd->prepare("SELECT level,weight,height,Max_heart_rate FROM personal_data where id = ? ;");
                $req->execute([$id_f["id"]]);
            ?>
            <table>
                <tr>
                    <th><img width="30" height="30" src="img/volume.png"></th>
                    <th><img width="30" height="30" src="img/weight.png"></th>
                    <th><img width="30" height="30" src="img/height.png"></th>
                    <th><img width="30" height="30" src="img/heart-attack.png"></th>
                </tr>
                <?php
                while($data = $req->fetch())
                {
                ?>
                    <tr>
                        <td><?php echo $data["level"]; ?></td>
                        <td><?php echo $data["weight"]; ?></td>
                        <td><?php echo $data["height"]; ?></td>
                        <td><?php echo $data["Max_heart_rate"]; ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        
        
        </body>

	</div>
    
    <div>
        <header>
        <hgroup>
            <?php echo('<h1><center>Performance of '.$name.'');?></center></h1>
		</hgroup>
		</header>

		
			<?php
				$bdd = new PDO($dsn, $username, $password);
				$req = $bdd->prepare("SELECT id_circuits,date,time,feeling,weather FROM circuits where id = ? ;");
				$req->execute([$id_f["id"]]);
			?>

			<table>
				<tr>
					<th><img width="30" height="30" src="img/calendar.png"></th>
					<th><img width="30" height="30" src="img/clock.png"></th>
					<th><img width="30" height="30" src="img/happy-face.png"></th>
					<th><img width="30" height="30" src="img/cloudy-day.png"></th>
				</tr>
				<?php
				while($data = $req->fetch())
				{
					?>
					<tr>
						<td>
						<?php echo $data["date"]; ?>
						</td>

						<td>
						<?php echo $data["time"]; ?>
						</td>

						<td>
						<?php echo $data["feeling"]; ?>
						</td>

						<td>
						<?php echo $data["weather"]; ?>
						</td>
					</tr>
					<?php

				}
			
				?>
			</table>
    </div>

    <div>
        <header>
        <hgroup>
            <?php echo('<h1><center>Routes of : '.$name.'');?></center></h1>
		</hgroup>
		</header>
        <?php
            $req= $bdd->prepare("SELECT * FROM itineraires WHERE id = ?;");
            $req -> execute([$id_f["id"]]);
	        $data = $req ->fetch();
        ?>
        <table>
            
            <tr>
                <th>Start</th>
                <th>End</th>
                <th>Date</th>
            </tr>
            <?php
                while($data = $req->fetch())
                {
                    $dep = $data["latitude_start"].",".$data["longitude_start"];
                    $arv = $data["latitude_arrived"].",".$data["longitude_arrived"];
            ?>
            <tr>
				<td><textarea id="macopie1" style="resize : none; cursor:default; width:70%;" rows="2" readonly>
				<?php echo $dep; ?>
				</textarea>
				<button type="btn" style="font-size:12px; border:solid;" onclick="macopie1()">Copier</button>
				</td>

				<td><textarea id="macopie1" style="resize : none; cursor:default; width:70%;" rows="2" readonly>
				<?php echo $arv; ?>
				</textarea>
				<button type="btn" style="font-size:12px; border:solid;" onclick="macopie1()">Copier</button>
				</td>
                <td><?php echo $data["date"]; ?></td>
            </tr>
            <?php
            }
            ?>
        </table>

    </div>


</html>