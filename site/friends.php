<!DOCTYPE html>
<?php
  include("fonctions.php");
  session_start();
  IsDefinedID();
?>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>friends</title>
    <link rel="stylesheet" href="css/users.css" />
    <?php AnimateCSS(); ?>
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


	<!-- Nav -->
	<?php
    PrintNav();
  ?>

  <body>
  <center><h1 class="wow animate__animated animate__fadeInRight">Add Friends</h1></center>
    <form method="post" action="friends.php">
      <input name="friend_name" type="text" placeholder="Nom" autocomplete="off" id="nom" />
      <input type="submit" value="Add" id="valider"/>
    </form>

    <center><h1 class="wow animate__animated animate__fadeInUp">My Friends</h1></center>
  </body>

  <?php
    InitBDD();
    $hostname=$_SESSION['hostname'];
    $username=$_SESSION['username']; 
    $password=$_SESSION['password']; 
    $dbname=$_SESSION['dbname'];
    $usertable=$_SESSION['usertable'];

    $dsn = "mysql:host=$hostname;dbname=$dbname";
  
	if(isset($_POST['friend_name'])){
    	$name = $_POST['friend_name'];
	}else{
	$name=NULL;
	}
	
    $pdo = new PDO($dsn, $username, $password);

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


    $req = $pdo->prepare("SELECT DISTINCT name_friend FROM friends WHERE id = ?");
    $req->execute([$_SESSION['id']]);
  ?>

  <table class="wow animate__animated animate__fadeInUp">
    <tr><center><img width="30" height="30" src="img/amis.png"></center></tr>
    <?php
      while($data = $req->fetch())
      {
    ?>
      <tr>
        <td><center><img width="30" height="30" src="img/amis.png"></td>
        <td><center><?php echo $data["name_friend"]; ?></center></td>
        <td><center><?php echo('<a href="redirect1.php?delusr='.$data["name_friend"].'">');?><center><img width="30" height="30" src="img/delete.png"></center></a></center></td>
        <td><center><?php echo('<a href="profileview.php?viewusr='.$data["name_friend"].'">');?><center><img width="30" height="30" src="img/see.png"></center></a></center></td>
      </tr>
    <?php
      }
    ?>
  </table>

  <center><h1 class="wow animate__animated animate__fadeInRight">Search User</h1></center>

  <form action = "friends.php" method = "get">
    <input type = "search" name = "terme">
    <input type = "submit" name = "s" value = "Search">
  </form>

  <?php

    if (isset($_GET["s"]))
    {
      $_GET["terme"] = htmlspecialchars($_GET["terme"]); //pour sécuriser le formulaire contre les failles html
      $terme = $_GET["terme"];
      //echo $terme;
      $terme = trim($terme); //pour supprimer les espaces dans la requête de l'internaute
      $terme = strip_tags($terme); //pour supprimer les balises html dans la requête
      if (isset($terme) and $terme != "")
      {
        $message = "";
        $terme = strtolower($terme);
        $req = $pdo->prepare("SELECT id,name FROM inscrits WHERE name LIKE ?;");
        $req->execute(["%".$terme."%"]);
      }
      else
      {
        header("Location: friends.php");
      }
    }

    
  ?>
  <h1 class="wow animate__animated animate__fadeInRight"><center>Results of search</center></h1>
  <table class="wow animate__animated animate__fadeInUp">
    <tr></tr>
    <?php
      while($data = $req->fetch())
      {
    ?>
      <tr>
        <td><center><?php echo $data["name"]; ?></center></td>
        <?php if($data['id']!=$_SESSION['id']){ ?>
        <td><center><?php echo('<a href="redirect1b.php?addusr='.$data["name"].'">');?><center><img width="30" height="30" src="img/plus.png"></center></a></center></td>
        <?php } ?>
      </tr>
    <?php
      }
    ?>
  </table>

</html>     

