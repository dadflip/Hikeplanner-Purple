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
    
    //echo $_GET['delitin'] ."<br>";
    $name = $_GET['delitin'];

    $pdo = new PDO($dsn, $username, $password);

    $req = $pdo->prepare("DELETE FROM itineraires WHERE id = ? AND id_itineraires = ?;");
	$req->execute([$_SESSION['id'],$name]);

    //echo "vous avez supprimmé un itinéraire!";
    header("Location: calendar.php")
    
?>