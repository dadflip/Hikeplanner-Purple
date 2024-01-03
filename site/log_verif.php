<?php
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

  // récupérer l'utilisateur dont le nom est ... et le password...
  $sql = 'SELECT * FROM inscrits WHERE name="'.$name.'" AND passwd1="'.$userpasswd.'"';
  $req = $pdo->prepare($sql);
  $req->execute();
  $user = $req->fetch(); //On récupère les informations de la requête

  //Récupération de l'ID
  $req = $pdo->prepare("SELECT id FROM inscrits WHERE name = ?");
  $req->execute([$_POST["name"]]); //On execute la requête
  $data = $req->fetch();  //On récupère les informations de la requête

  $_SESSION['id']= $data["id"]; //On récupère l'ID de l'utilisateur à la connexion
  $_SESSION['log_error']=0;

  //Vérification des informations de connexion
  if ($user) {
    //echo "OK";
    header("Location: accueil.php");
    $_SESSION['log_error']=0;
   
  } else {
    //echo "PAS OK";
    header("Location: login.php");
    $_SESSION['log_error']=1;
  }  
?>