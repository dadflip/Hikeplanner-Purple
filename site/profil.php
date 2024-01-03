<!DOCTYPE html>
<?php
    session_start();
    include("fonctions.php");
    IsDefinedID();
    $_SESSION['vitesse']=0;//Par défaut est mis à 0
?>

<html>
	<head>
		<title>my profile</title>
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


    <!-- Nav -->
	<?php
        PrintNav();
    ?>

    <body>
        <div>
            <header>
                <hgroup>
                    <h1 class="wow animate__animated animate__fadeInRight" ><center>MY PROFILE</center></h1>
                    <h2 class="wow animate__animated animate__fadeInLeft"><center>Personal data</center></h2>
                    <center><img width="200" height="200" src="img/user.png"></center>
                </hgroup>
            </header>
            
            <form class="wow animate__animated animate__bounceIn" title="Enter informations about you in this form" method="post" action="profil.php">

                <p><center>
                    <label for="Level">Level :</label></br>
                    <select style="width: 55%" id="Level" name="level">

                        <option value="bikehard" required>hard (bike) max= 30 km/h)</option>
                        <option value="bikemed">medium (bike) max= 20 km/h)</option>
                        <option value="bikeeasy">easy (bike) max= 15 km/h)</option>
                        <option value="hard">hard (vit. marche max= 5 km/h)</option>
                        <option value="medium">medium (vit. marche max= 4 km/h)</option>
                        <option value="easy">easy (vit. marche max= 3 km/h)</option>
                    </select></br>
                    <label>
                        Weight : </br><input style="width: 50%" type="int" name="weight" placeholder="kg" />
                    </label></br>
                    <label>
                        Height : </br><input style="width: 50%" type="int" name="height" placeholder="cm"/> 
                    </label>
                    </br>
                    <label>
                        Max heart rate : </br><input style="width: 50%" type="int" name="max_heart_rate" placeholder="bpm" />
                    </label></br>
                    <label>
                        <center><input type="submit"  name='ajouter' value="modifier" /></center>
                    </label>
                </center></p>
            </form>

            <h2 class="wow animate__animated animate__fadeInLeft"><center>My Stats</center></h2>

            <?php
                InitBDD();
                $hostname=$_SESSION['hostname'];
                $username=$_SESSION['username']; 
                $password=$_SESSION['password']; 
                $dbname=$_SESSION['dbname'];
                $usertable=$_SESSION['usertable'];
            
                $dsn = "mysql:host=$hostname;dbname=$dbname";
                $bdd = new PDO($dsn, $username, $password);

                $req = $bdd->prepare("SELECT IF(id IS NULL or id = '', 'NULL', id) as id FROM personal_data WHERE  id = ?");
                $req->execute([$_SESSION['id']]);
                $id1 = $req->fetch(); 
            
                
                if ($id1 != null && $id1['id'] == $_SESSION['id']) {
                    
                    if (isset($_POST["level"])) {
                        $level = $_POST["level"];
                        $_SESSION['level']=$level;
                        $weight = $_POST["weight"];
                        $height = $_POST["height"];
                        $max_heart_rate = $_POST["max_heart_rate"];

                        //execute the request
                        $req = $bdd->prepare("UPDATE personal_data SET level = ?,weight = ?,height = ?,Max_heart_rate = ? where id = ?");
                        $req->execute([$level,$weight,$height,$max_heart_rate,$_SESSION['id']]);
                    }

                }else{
                    if (isset($_POST["level"])) {
                        $level = $_POST["level"];
                        $_SESSION['level']=$level;
                        $weight = $_POST["weight"];
                        $height = $_POST["height"];
                        $max_heart_rate = $_POST["max_heart_rate"];
                        
                        $req = $bdd->prepare("INSERT INTO personal_data (id,level,weight,height,Max_heart_rate) VALUES (?,?,?,?,?);");
                        $req->execute([$_SESSION['id'],$level,$weight,$height,$max_heart_rate]);
                    }
                }
                
            ?>
            

            <?php
                $req = $bdd->prepare("SELECT level,weight,height,Max_heart_rate FROM personal_data where id = ? ;");
                $req->execute([$_SESSION['id']]);
            ?>

            <table>
                <tr>
                    <th><img width="30" height="30" src="img/volume.png"></th>
                    <th><img width="30" height="30" src="img/weight.png"></th>
                    <th><img width="30" height="30" src="img/height.png"></th>
                    <th><img width="30" height="30" src="img/heart-attack.png"></th>
                </tr>
                <?php
                while($data = $req->fetch()){
                ?>
                    <tr>
                        <td><?php echo $data["level"]; ?></td>
                        <td><?php echo $data["weight"]; ?></td>
                        <td><?php echo $data["height"]; ?></td>
                        <td><?php echo $data["Max_heart_rate"]; ?></td>
                    </tr>

                    <?php
			
                        if($data['level']!="" or $data['level']!=NULL){
                            if($data['level']=="hard"){
                                $_SESSION['vitesse']=5;
                            }elseif($data['level']=="medium"){
                                $_SESSION['vitesse']=4;
                            }elseif($data['level']=="medium"){
                                $_SESSION['vitesse']=3;

                            }elseif($data['level']=="bikehard"){
                                $_SESSION['vitesse']=30;
                            }elseif($data['level']=="bikemed"){
                                $_SESSION['vitesse']=20;
                            }elseif($data['level']=="bikeeasy"){
                                $_SESSION['vitesse']=15;
                            }
                        }else{
			                $_SESSION['vitesse']=0;
                        }
                }
                    ?>
            </table>
            </br>
            <table>
                <?php
                    if($_SESSION['vitesse']<15){
                        $_SESSION['bikeorfoot']='foot';
                ?>
                    <tr>
                    <th>walking speed</th>
                    <th>average over 1km</th>
                    <th>average over 10km</th>
                    </tr>
                <?php
                    }else{
                        $_SESSION['bikeorfoot']='bike';
                ?>
                    <tr>
                    <th>cycling speed</th>
                    <th>average over 1km</th>
                    <th>average over 10km</th>
                    </tr>
                <?php
                    }
		        ?>
		<?php
		if(isset($_SESSION['vitesse'])){
		?>
                <tr>
                    <td><?php echo $_SESSION['vitesse']; ?></td>
                    <td><?php echo $_SESSION['vitesse']*0.99; ?></td>
                    <td><?php echo $_SESSION['vitesse']*0.98; ?></td>
                </tr>
		<?php
		}
		?>
                    
            </table>
        </div>
    

        <h2 class="wow animate__animated animate__fadeInLeft"><center>My Performances</center></h2>
	<?php
		$req = $bdd->prepare("SELECT id_circuits FROM circuits where id = ? ;");
                $req->execute([$_SESSION['id']]);
		$data=$req->fetch();
		if($data!=false){
	?>
        <img src='graph.php' style="border:solid" width="49%">
        <img src='graph2.php' style="border:solid" width="49%">
	<?php } ?>

        </br></br>
    
        <div>
            <?php
		
                $req = $bdd->prepare("SELECT id_circuits,date,time,feeling,weather FROM circuits where id = ? ;");
                $req->execute([$_SESSION['id']]);
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
                        <td><?php echo $data["date"]; ?></td>
                        <td><?php echo $data["time"]; ?></td>
                        <td><?php echo $data["feeling"]; ?></td>
                        <td><?php echo $data["weather"]; ?></td>
                    </tr>
                    <?php

                }
            
                ?>
            </table>
        </div>
            </br> </br> </br> </br> </br>
        <a class="wow animate__animated animate__bounceIn" href="quitapp.php"><center>Quit app</center></a>
    </body>
</html>