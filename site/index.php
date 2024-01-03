<!DOCTYPE html>
<html>
  <?php
    session_start();
    $_SESSION['error']=0;
    $_SESSION['log_error']=0;
    $_SESSION['level'] = '';
    unset($_SESSION['id']);
    unset($_SESSION['footorbike']);
  ?>

  <head>
    <meta charset="utf-8">
    <title>hikeplanner2022</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/style_2.css">
  </head>
  
  <h1>HIKE PLANNER</h1>
  </br></br></br></br></br></br></br>

  <body>
    <a class="button" href="inscription.php">SIGN-UP</a>
    <a class="button" href="login.php">SIGN-IN</a>
  </body>
</html>