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
?>
<?php

// si le bouton "Envoyer" est cliqué
if(isset($_POST['envoyer'])) {
    //on vérifie que le champ destinataire est correctement rempli
    if(empty($_POST['dest'])) {
        echo "Recipient field is empty!";
    } else {
        //Récupération de l'ID
        $req = $pdo->prepare("SELECT id FROM inscrits WHERE name = ?");
        $req->execute([$_POST["dest"]]); //On execute la requête
        $data = $req->fetch();  //On récupère les informations de la requête

        //on vérifie que le  destinataire existe
        if(!$data){
            echo "Incorrect recipient field";
        }else{
            //on vérifie que le champ sujet est correctement rempli
            if(empty($_POST['sujet'])) {
                echo "Empty subject field";
            }else{
                //on vérifie que le champ message n'est pas vide
                if(empty($_POST['message'])) {
                    echo "Empty message field";
                }else{
                    //tout est correctement renseigné, on envoi le message
                    //on renseigne les entêtes de la fonction mail de PHP

                    $req = $pdo->prepare("INSERT INTO messages(id,sujet,message,id_dest) VALUES (?,?,?,?);");
                    $req->execute([$_SESSION['id'],$_POST['sujet'],$_POST['message'],$data["id"]]); //On execute la requête            
                    echo "Message sent succesfully!";
                    echo "</br>"."<button><a href='accueil.php'>OK</a></button>";
                }
            }
        }
    }
}

?>
