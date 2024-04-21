<?php
function InitBDD(){
    //session_start();
    //Valeurs a changer selon base de données et serveurs...
    $_SESSION['hostname']="localhost";
	$_SESSION['username']="username";
    $_SESSION['password']="passwd";
    $_SESSION['dbname']="users";
    $_SESSION['usertable']="";
}

function IsDefinedID(){
    //session_start();
    if(!isset($_SESSION['id']) or $_SESSION['id']=='') {
        header('Location: login.php');
    }
}

function PrintNav(){
    echo '
    <!-- Nav -->
    <div id="sideNavigation" class="sidenav">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><img src="img/close.png" width="20" height="20" ></a>
          <a href="/site/accueil.php"><img width="30" height="30" src="img/home.png"> HOME</a>
          <a href="/site/profil.php"><img width="30" height="30" src="img/user.png"> PROFILE</a>
          <a href="/site/contacts.php"><img width="30" height="30" src="img/email.png"> CONTACT</a>
          <a href="/site/calendar.php"><img width="30" height="30" src="img/map.png"> PLANNED </a>
          <a href="/site/circuits.php"><img width="30" height="30" src="img/hiking.png"> COMPLETED </a>
          <a href="/site/friends.php"><img width="30" height="30" src="img/amis.png"> FRIENDS</a>
          <a href="/site/about.php"><img width="30" height="30" src="img/info-button.png"> ABOUT</a>
        </div>
    
        <nav class="topnav">
          <a class="wow animate__animated animate__heartBeat" href="#" onclick="openNav()">
            <svg width="30" height="30" id="icoOpen">
                <path d="M0,5 30,5" stroke="#000" stroke-width="5"/>
                <path d="M0,14 30,14" stroke="#000" stroke-width="5"/>
                <path d="M0,23 30,23" stroke="#000" stroke-width="5"/>
            </svg>
          </a>
          <a class="wow animate__animated animate__heartBeat" href="friends.php"><img width="30" height="30" src="img/amis.png"></a>
          <a class="wow animate__animated animate__heartBeat" href="index.php"><img width="30" height="30" src="img/on-off-button.png"></a>
        </nav>
    ';

}

function AnimateCSS(){
    echo '<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">';
}

function WowJS(){
    echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js" ></script>
    <script type="text/javascript">
	new WOW({
        boxClass: "wow", // par défaut
        animateClass: "animated", // par défaut
        offset: 0, // par défaut
        mobile: true, // par défaut
        live: true // par défaut
    }).init()
    </script>
    ';
}
?>
