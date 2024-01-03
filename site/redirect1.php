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
    
    //echo $_GET['delusr'] ."<br>";
    $name = $_GET['delusr'];

    $pdo = new PDO($dsn, $username, $password);

    $req = $pdo->prepare("DELETE FROM friends WHERE id = ? AND name_friend = ?;");
	$req->execute([$_SESSION['id'],$name]);
    $req->execute([$name,$_SESSION['id']]);

    //echo "vous avez supprimmé un ami";
    header("Location: friends.php")
    
?>