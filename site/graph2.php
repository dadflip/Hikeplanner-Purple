<?php
  
  //Inclusion de la librairie JpGraph
  include ("jpgraph-4.4.1/src/jpgraph.php");
  include ("jpgraph-4.4.1/src/jpgraph_pie.php");
  include ("jpgraph-4.4.1/src/jpgraph_line.php");
  
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
  $bdd = new PDO($dsn, $username, $password);

  $tableaudist = array();
  $tableautime = array();

  /*
  ***********************************************************************************************************
      Récupération de valeurs et création du tableau

  ***********************************************************************************************************
  */

  //requête vers la base de données pour récupérer les valeurs
  $req = $bdd->prepare("SELECT id_circuits,distance,time,feeling,weather FROM circuits where id = ? ORDER BY distance ASC;");
  $req->execute([$_SESSION['id']]);

  //Mise dans un tableau des valeurs acquises précédemment
  while ($tableau = $req->fetch())
  {
    $tableaudist[] = $tableau['distance'];
    $tab=explode(':',$tableau['time']);
    $hours=$tab[0];
    $min=$tab[1];
    $secs=$tab[2];
    $tableautime[]=$hours*60+$min+$secs/60;
  }

  /*
  ***********************************************************************************************************
      Création du graphique

  ***********************************************************************************************************
  */

  // ***********************
  // Creation du graphique
  // ***********************

  // Creation du conteneur
  $graph = new Graph(500,300);

  // Fixer les marges
  $graph->img->SetMargin(60,30,50,40);    

  // Mettre une image en fond
  //$graph->SetBackgroundImage("",BGIMG_FILLFRAME);

  // Lissage sur fond blanc (evite la pixellisation)
  $graph->img->SetAntiAliasing("white");

  // A detailler
  $graph->SetScale("textlin");

  // Ajouter une ombre
  $graph->SetShadow();

  // Ajouter le titre du graphique
  $graph->title->Set("VOS PERFORMANCES");

  // Afficher la grille de l'axe des ordonnees
  $graph->ygrid->Show();
  // Fixer la couleur de l'axe (bleu avec transparence : @0.7)
  $graph->ygrid->SetColor('blue@0.7');
  // Des tirets pour les lignes
  $graph->ygrid->SetLineStyle('dashed');

  // Afficher la grille de l'axe des abscisses
  $graph->xgrid->Show();
  // Fixer la couleur de l'axe (rouge avec transparence : @0.7)
  $graph->xgrid->SetColor('red@0.7');
  // Des tirets pour les lignes
  $graph->xgrid->SetLineStyle('dashed');

  // Apparence de la police
  //$graph->title->SetFont(FF_ARIAL,FS_BOLD,11);

  // Creer une courbes
  $courbe = new LinePlot($tableautime); //

  // Afficher les valeurs pour chaque point
  $courbe->value->Show();

  // Valeurs: Apparence de la police
  //$courbe->value->SetFont(FF_ARIAL,FS_NORMAL,9);
  //$courbe->value->SetFormat('%d');
  //$courbe->value->SetColor("red");

  // Chaque point de la courbe ****
  // Type de point
  $courbe->mark->SetType(MARK_FILLEDCIRCLE);
  // Couleur de remplissage
  $courbe->mark->SetFillColor("red");
  // Taille
  $courbe->mark->SetWidth(5);

  // Couleur de la courbe
  $courbe->SetColor("blue");
  $courbe->SetCenter();

  // Param�trage des axes
  $graph->xaxis->title->Set("Distance parcourue (en km)");
  $graph->yaxis->title->Set("Temps en minutes");
  $graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
  $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
  $graph->xaxis->SetTickLabels($tableaudist);

  // Ajouter la courbe au conteneur
  $graph->Add($courbe);

  $graph->Stroke();

?>
