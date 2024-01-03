<?php
  //Peemet de se désincrire de l'application
  include("fonctions.php");
  session_start();
  InitBDD();
	$hostname=$_SESSION['hostname'];
	$username=$_SESSION['username']; 
	$password=$_SESSION['password']; 
	$dbname=$_SESSION['dbname'];
	$usertable=$_SESSION['usertable'];

  $name=$_POST['name'];
  $email = $_POST['email'];
  $userpasswd = $_POST['passwd1'];
  $dsn = "mysql:host=$hostname;dbname=$dbname";
  $pdo = new PDO($dsn, $username, $password);

  $req = $pdo->prepare("DELETE FROM inscrits WHERE id=?;");
  $req->execute([$_SESSION['id']]);
  header("Location: index.php")
?>