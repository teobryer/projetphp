<?php

  class Statistiques{
      
  private $db;
protected $vues;

  
    public function __construct() {
      include('db.inc.php');
      $this->db = new PDO("$server:host=$host;dbname=$base", $user, $passwd);
      $sql = "SELECT page FROM access WHERE page='".$_SERVER['PHP_SELF']."'";
      $test = $this->db->query($sql);

    if($test->rowCount()!= null){

      $sql = "SELECT nbAcces FROM access WHERE page='".$_SERVER['PHP_SELF']."'";
      $val = $this->db->query($sql);
       $ligne = $val->fetch();
      $this->vues = $ligne['nbAcces'];
      $this->vues = ($this->vues+1);
     $sql = "UPDATE access SET nbAcces = ($this->vues)
     WHERE page='".$_SERVER['PHP_SELF']."'" ;
      $this->db->query($sql);
    //  echo"if";
     }

    else{
      $this->vues = 1;
    $sql= "INSERT INTO access (page, nbAcces) VALUES ('".$_SERVER['PHP_SELF']."', 1)";
    $this->db->query($sql);
    //echo"else";
    
    }
 
    } 

         
      function showPageAccess() {
            return $this->vues;
        }

      function getStats() {
        echo'<table><tr><th>Nom</th><th>NbVues</th></tr>';
            $sql = "SELECT * FROM access ";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbAcces'];
        echo '<tr><td>'.$page.'</td><td>'.$nb.'</td></tr>';
      }
      echo'</table>';

        }
      
	

  }

?>
