<!DOCTYPE html>
<html>
	
	<head>
		<script type="text/javascript">
			document.createElement('header');
			document.createElement('footer');
			document.createElement('form');
		</script>

		<meta charset="utf-8">
		<title>sign-up</title>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/insc_style.css">
	</head>

	<body>
		<header>
			<h1>SIGN UP</h1>
		</header>

		<div>
		<form method="post" action="verif.php">

			
			<label>
					<p>
					<pre>
					Name:            <input type="text" name="name" required> </br>
					Email:           <input type="email" name="email" required> </br>
					Password:        <input type="password" name="passwd1" required="4"> </br>
					Retype password: <input type="password" name="passwd2" required="4"> </br>
					Birthday:        <input type="date" name="date" required> </br>  <!--type="date"-->
						
						<input type="radio" name="Genre" value="homme"> Man <input type="radio" name="Genre" value="femme" required> Woman <input type="radio" name="Genre" value="autre"> Other </br></br>
						
					
							<input class="button" type="submit" value="SUBMIT">
					</p>
			</label>

					<p>
						<input type="checkbox" name="Accept" checked="true" required>I accept <a href= "/site/cgu.php">the terms of use</a> </center>
					</p>
					<p style="color:white"><?php 
						session_start();
						if($_SESSION['error']==1){
							echo "The passwords are different or the name is already in use"."</br></br>";
						}else{
							echo ""."</br></br>";
						}
					?></p>
			</pre>

		</form>
		</div>
		<a href="/site/index.php">Back</a>
		<footer>
			<center><small> © Tous droits réservés - 2022 </small></center>
		</footer>
	</body>


</html>