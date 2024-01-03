<!DOCTYPE html>
<html>

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
    $_SESSION['id']="";
  ?>

  <!-- Header: mise en place des éléments js et css nécessaires -->
  <head>
    <title>login</title>

    <script type="text/javascript">
      document.createElement('header');
      document.createElement('footer');
      document.createElement('form');
    </script>

    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/insc_style.css">

  </head>


  <body>
    <h1>SIGN IN</h1>
    <form method="post" action="log_verif.php">

      <pre>
            <label>
              <center>
      Name:         <input type="text" name="name"> </br>
      Password:     <input type="password" name="passwd1"></br></br>
                    <input class="button "type="submit" value="Login">
              </center>
            </label>

            <?php 
              if(isset($_SESSION['log_error']) and $_SESSION['log_error']==1){
                echo "Incorrect username or password !"."</br></br>";
              }else{
                echo ""."</br></br>";
              }
            ?>
      </pre>
      

    </form>
    <a href="/site/index.php">Back</a>

  </body>

</html>


