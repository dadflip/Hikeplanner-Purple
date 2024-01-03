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
    
    echo $_GET['delcirc'] ."<br>";
    $name = $_GET['delcirc'];

    $pdo = new PDO($dsn, $username, $password);

    $req = $pdo->prepare("DELETE FROM circuits WHERE id = ? AND id_circuits = ?;");
	$req->execute([$_SESSION['id'],$name]);

    echo "vous avez supprimmé un circuit!";
    header("Location: circuits.php")
    
?>