<!DOCTYPE html>
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
    $bdd = new PDO($dsn, $username, $password);
?>
<html>

	<head>
		<title>conctacts</title>
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
 
	<div>
		<header>
		<hgroup>
			<h1 class="wow animate__animated animate__fadeInRight"><center>MESSAGE</center></h1>
			<h2 class="wow animate__animated animate__fadeInLeft"><center>Send a message to another user!</center></h2>
		</hgroup>
		</header>


		<body>
			
            <form class="wow animate__animated animate__fadeInUp" action="sendmessage.php" method="post">
                Recipient: <input type="text" name="dest" value="" />
                <br />
                Subject: <input type="text" name="sujet" value="" />
                <br />
                Message: <textarea name="message" cols="40" rows="20"></textarea>
                <br />
                <input type="submit" name="envoyer" value="Send" />
            </form>

            
		</body>

	</div>

	<h2 class="wow animate__animated animate__fadeInUp"><center>Received Messages</center></h2>

	<div>
		<?php
			
			$req = $bdd->prepare("SELECT * FROM inscrits INNER JOIN messages ON inscrits.id = messages.id where id_dest = ? LIMIT 10;");
			$req->execute([$_SESSION['id']]);
			
		?>

		<table class="wow animate__animated animate__fadeInUp">
			<tr>
				<th>sender</th>
				<th>subject</th>
				<th>message</th>
			</tr>
			<?php
			while($data = $req->fetch())
			{ 
				?>
				<tr>
					<td><?php echo $data["name"]; ?></td>
					<td><?php echo $data["sujet"]; ?></td>
					<td><?php echo $data["message"]; ?></td>
				</tr>
				<?php

			}
		
			?>
		</table>
	</div>

</html>