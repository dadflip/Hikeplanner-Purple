<!DOCTYPE html>
<html>
<head>
	<title>verification</title>
</head>

<body>
	<?php
		include("fonctions.php");
		session_start();
		//Se connecter à la base de données
		InitBDD();
		$hostname=$_SESSION['hostname'];
		$username=$_SESSION['username']; 
		$password=$_SESSION['password']; 
		$dbname=$_SESSION['dbname'];
		$usertable=$_SESSION['usertable'];
		$bdd = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8","$username","$password");
	
		$req = $bdd->prepare("SELECT * FROM inscrits;");
		$req->execute();
		$usednameerror = 0;

		
		while($line = $req->fetch()){
			echo "line:".$line['name']."</br>";
			if($line['name'] == $_POST['name']){
				$usednameerror =1;
			}
		}
		echo $_POST['name']."</br>";
    	echo "unerr:".$usednameerror;
	?>




	<?php 
		session_start();
		$_SESSION['error']=0;

		if($usednameerror !=1){
			if (($_POST['passwd1'] == $_POST['passwd2'])){
				$req= $bdd->prepare("USE users");
				$req -> execute();
				$req= $bdd->prepare("INSERT INTO inscrits( name, email, passwd1, date, Genre, admin) VALUES(?,?,?,?,?,?)");
				$req -> execute([$_POST['name'],$_POST['email'],$_POST['passwd1'],$_POST['date'],$_POST['Genre'],'non']);
				header("Location: login.php");
				
			}else{
				$_SESSION['error']=1;
				header("Location: inscription.php");
			}
		}else{
			$_SESSION['error']=1;
			header("Location: inscription.php");
		}

		
	?>

</body>
</html>




