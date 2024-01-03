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
    $pdo = new PDO($dsn, $username, $password);

    //echo $_GET['addusr'] ."<br>";
    $name = $_GET['addusr'];

   
    //on récupère l'id de la personne a ajouter
    $req = $pdo->prepare("SELECT id FROM inscrits WHERE name = ?");
    $req->execute([$name]);
    $friend = $req->fetch();

    //on récupère le nom de l'utilisateur actuel
    $req = $pdo->prepare("SELECT name FROM inscrits WHERE id = ?");
    $req->execute([$_SESSION['id']]);
    $usrnam = $req->fetch();

    //si l'id (correspondant au nom de l'ami) est trouvé dans la bdd
    if ($friend) {
        $req = $pdo->prepare("INSERT INTO friends(id, id_friends, name_friend) VALUES (?,?,?);");
        $req->execute([$_SESSION['id'],$friend['id'], $name]);//on ajoute l'ami
        $req->execute([$friend['id'],$_SESSION['id'], $usrnam['name']]);//on est ajouté dans la liste d'amis de l'autre utilisateur
    }  

    //echo "vous avez ajouté un ami";
    header("Location: friends.php")
    
?>