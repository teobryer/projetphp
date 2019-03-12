<?php
//setcookie('navigateur', $_SERVER['HTTP_USER_AGENT'],(time()+3600*24*12));
class Statistiques{

  private $db;
  protected $vues;
  private $navigateur;
  private $cookie;
  private $date;


  public function __construct() {

    include('db.inc.php'); // on inclut ce fichier pour récupérer les paramètres de la base de données qui ont été indiqué 
    $this->db = new PDO("$server:host=$host;dbname=$base", $user, $passwd); // on instancie un objet PDO afin de pouvoir faire des requêtes SQL que l'on place dans l'attribut 


//////VERIFICATION : on regarde si le navigateur a déjà été utilsé avec un cookie

    if (isset($_COOKIE['nav9'])){
      $this->navigateur = $_COOKIE['nav9']; /// dans le cas ou le cookie existe déjà on récupére sa valeur et on la donne à l'attribut navigateur
    }

    else{
      $this->navigateur= uniqid(); // dans le cas ou il est inexistant on génère un id unique 
      $this->cookie =setcookie('nav9', $this->navigateur, (time()+3600*24*12) ); // on créer alors le cookie pour affecter le navigateur avec cet id unique
    }



    $sql = "SELECT page FROM visite WHERE user= '".$_COOKIE['user']."' AND dateJ= CURRENT_DATE() AND page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."' AND navigateur='".$this->navigateur."'"; // on effectue on requête SELECT avec toutes les informations sur la connexion ( page, utilisateur, navigateur,  date, Ip) qui pourraient être potentiellement sur la base de donnée 
   // $val = $this->db->query($sql); //
    $test = $this->db->query($sql); // on execute la précédente requête sql dans la base de donnée concernée

    if($test->rowCount()!= null){ // on vérifie si la requête renvoie quelque chose
// DANS LE CAS OU ELLE RENVOIE QUELQUE CHOSE : cela veut dire que la page courrante a déjà un enregistrement qui lui correpond 

      $sql = "SELECT nbVu FROM visite WHERE user= '".$_COOKIE['user']."' AND dateJ= CURRENT_DATE() AND page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'AND navigateur='".$this->navigateur."'"; // requête SQL permertant de récupérer la nombre de vu de cette dite page courrante
      $val = $this->db->query($sql); // éxécution de la précédente requête SQL
      $ligne = $val->fetch(); // place dans éléments dans une variable
      $this->vues = $ligne['nbVu']; // récupère le nombre de vues 
      $this->vues = ($this->vues+1); // incrémente de 1 vue
      $sql = "UPDATE visite SET nbVu = ($this->vues) 
      WHERE user='".$_COOKIE['user']."' AND dateJ= CURRENT_DATE() AND page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'AND navigateur='".$this->navigateur."'" ; // requête SQL permettant de mettre à jour les données de la dite page courante 
      $this->db->query($sql); // éxécution de la précédente requête SQL
    }

    else{
      // DANS LE CAS OU LA REQUETE NE RENVOIE RIEN
      $this->vues = 1; // affecte la valeur de vue à 1 
      $sql= "INSERT INTO visite (user, dateJ, page, ip, nbVu, navigateur) VALUES ( '".$_COOKIE['user']."' ,CURRENT_DATE(),'".$_SERVER['PHP_SELF']."','".$_SERVER['REMOTE_ADDR']."', 1,'".$this->navigateur."')"; // requête SQL permettant d'insérer dans la table un nouvel enregistrement avec les inormations de la connexion et nombre de vue = 1 

      $this->db->query($sql); // éxécution de la précédente requête SQL

      



    }

  } 

  


  function showPageAccess() { // Permet d’afficher le nombre fois que la page courante a été vu
    return $this->vues;

  }

  function getStatsByPage() { // permet d’afficher le nombre d’accès global de chaque page 
    echo'<table border=1><tr><th>Nom</th><th>NbVues</th></tr>';

    $arrayPage = array();

    //Récupération du nom des pages
    $sql = "SELECT DISTINCT page FROM visite ";
    $val = $this->db->query($sql);
    


    while ($ligne = $val->fetch()){ // parcours les lignes des enregistrement de la précente requête SQL et place le nom des pages dans une liste
      $page = $ligne['page'];
      array_push($arrayPage,$page);
   }

    for ($i = 0; $i < count($arrayPage); $i++) {
     $sql = "SELECT  SUM(nbVu) as test FROM visite WHERE page ='".$arrayPage[$i]."'"; /// Pour chaque page on calcule le nombre de vue total 
     $val = $this->db->query($sql);
     while ($ligne = $val->fetch()){

    $pagevu = $ligne['test'];
        
// INSERTION DANS UN TABLEAU LE NOMBRE DE VU SELON LE NOM DE LA PAGE

      echo '<tr><td>'.$arrayPage[$i].'</td><td>'.$pagevu.'</td></tr>';
    }
  }
  echo'</table>';

}


function getStats() { // permet d’afficher le nombre d'accès pour une page unique, un utilisateur unique et navigateur unique 
  echo'<table border=1><tr><th>User</th><th>Navigateur</th><th>Nom</th><th>NbVues</th></tr>';
  $sql = "SELECT * FROM visite "; // On sélectionne tous les enregistrements 
  $val = $this->db->query($sql);
  while ($ligne = $val->fetch()){

    $page = $ligne['page'];
    $nb = $ligne['nbVu'];    // on récupère leurs données dans des variables
    $user = $ligne['user'];
    $navigateur = $ligne ['navigateur'];
    echo '<tr><th>'.$user.'</th><th>'.$navigateur.'</th><td>'.$page.'</td><td>'.$nb.'</td></tr>'; // INSERTION DANS UN TABLEAU DES DONNEES
  }
  echo'</table>';

}


function getDailyStats() { // permet d’afficher le nombre d'accès jour par jour & utilisateur unique 
  echo'<table border=1><tr><th>Date</th><th>User</th><th>Nom</th><th>NbVues</th></tr>';
  $sql = "SELECT dateJ, user, page, SUM(nbVu) AS nbVu FROM visite  GROUP BY user,page ORDER BY dateJ DESC"; // On sélectionne les enregistrements par utilisateurs et par date 
  $val = $this->db->query($sql);
  while ($ligne = $val->fetch()){

    $page = $ligne['page'];
    $nb = $ligne['nbVu'];  /// on récupère leurs données dans des variables 
    $date= $ligne['dateJ'];
    $user = $ligne ['user'];
    echo '<tr><th>'.$date.'</th><th>'.$user.'</th><td>'.$page.'</td><td>'.$nb.'</td></tr>'; // INSERTION DANS UN TABLEAU DES DONNEES
  }
  echo'</table>';

}


function getStatsByIp() { // permet d’afficher le nombre d'accès des machines différentes ayant accédé à la page courante
  echo'<table border=1><tr><th>Ip</th><th>Nom</th><th>NbVues</th></tr>';
  $sql = "SELECT ip, page, SUM(nbVu) AS nbVu FROM visite WHERE page='".$_SERVER['PHP_SELF']."'GROUP BY ip"; // On sélectionne les enregistrements par IP 
  $val = $this->db->query($sql);
  while ($ligne = $val->fetch()){

    $page = $ligne['page'];                /// on récupère leurs données dans des variables 
    $nb = $ligne['nbVu'];
    $ip = $ligne ['ip'];
    echo '<tr><td>'.$ip.'</td><td>'.$page.'</td><td>'.$nb.'</td></tr>';  // INSERTION DANS UN TABLEAU DES DONNEES
  }
  echo'</table>';

}
function getStatsPNG(){
          //listes
  $arrayPage = array();
  $arrayPageVue = array();
         // On sélectionne le nom de page une seule fois 
  $sql = "SELECT DISTINCT page FROM visite  ";
  $val = $this->db->query($sql);


  while ($ligne = $val->fetch()){   // On place dans un tableau le nom de toutes les pages différentes 
    $page = $ligne['page'];
    array_push($arrayPage,$page);

  }

  for ($i = 0; $i < count($arrayPage); $i++) {
   $sql = "SELECT  SUM(nbVu) as test FROM visite WHERE page ='".$arrayPage[$i]."'"; // On récupère le nombre de vues des pages 1 à 1 et on le met dans u
   $val = $this->db->query($sql);                                                   // et on les mets dans une liste
   while ($ligne = $val->fetch()){  
   $pagevu = $ligne['test'];
  array_push($arrayPageVue,$pagevu);


  }

}
  // On récupère le nombre maximum de vues en parcourant la liste 
;

for($i = 0; $i < count($arrayPage); $i++){
 $size =($arrayPageVue[$i]*500)/max($arrayPageVue); 
 echo $arrayPage[$i].'  '.' &nbsp; &nbsp;<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Color-blue.JPG" width="'.$size.'" height="20" />'.'<br />'; // On affiche l'image proportionnelle au nombre max de vu 
}




}


function getDailyStatsPNG($date){
          //listes
  $arrayPage = array();
  $arrayPageVue = array();

   // On sélectionne le nom de page une seule fois les pages qui ont une date égale à celle placée en paramètre      
  $sql = "SELECT DISTINCT page FROM visite  Where dateJ= '".$date."'";
  $val = $this->db->query($sql);

  while ($ligne = $val->fetch()){
    $page = $ligne['page'];               // On place dans un tableau le nom de toutes les pages différentes 
    array_push($arrayPage,$page);

  }

  for ($i = 0; $i < count($arrayPage); $i++) {                                      // On récupère le nombre de vues des pages 1 à 1 et on le met dans u
   $sql = "SELECT  SUM(nbVu) as test FROM visite WHERE page ='".$arrayPage[$i]."'"; // et on les mets dans une liste
   $val = $this->db->query($sql);
   while ($ligne = $val->fetch()){
   $pagevu = $ligne['test'];
   array_push($arrayPageVue,$pagevu);
 }

}
   // On récupère le nombre maximum de vues en parcourant la liste 
;

for($i = 0; $i < count($arrayPage); $i++){
 $size =($arrayPageVue[$i]*500)/max($arrayPageVue);
 echo $arrayPage[$i].'  &nbsp; &nbsp;'.'<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Color-blue.JPG" width="'.$size.'" height="20" />'.'<br />'; // On affiche l'image proportionnelle au nombre max de vu 
}




}













}

?>
