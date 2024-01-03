<!DOCTYPE html>

<?php
	include("fonctions.php");
	session_start();
	IsDefinedID();
?>

<html>
	<head>
		<title>a propos</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" href="css/users.css">
		<?php AnimateCSS();?>
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
 
	<div>
        <p class="wow animate__animated animate__backInUp">Un projet réalisé par: Antonin Louvet, Eliot Cusin et Davide Chinedum</p>
        <a href="http://copyright.be" target="_blank">Copyright © 2022 Hikeplanner - Tous droits réservés</a>
    </div>

</html>