<?php

  class Statistiques{
      
  private $db;
protected $vues;

  
    public function __construct() {
      $a= $_SERVER['REMOTE_ADDR'];
      $b= $_SERVER['PHP_SELF'];
      include('db.inc.php');
      $this->db = new PDO("$server:host=$host;dbname=$base", $user, $passwd);
       $sql = "SELECT page FROM acess1 WHERE page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'";
      $val = $this->db->query($sql);
      $test = $this->db->query($sql);

    if($test->rowCount()!= null){

      $sql = "SELECT nbAcces FROM acess1 WHERE page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'";
      $val = $this->db->query($sql);
       $ligne = $val->fetch();
      $this->vues = $ligne['nbAcces'];
      $this->vues = ($this->vues+1);
     $sql = "UPDATE acess1 SET nbAcces = ($this->vues)
     WHERE page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'" ;
      $this->db->query($sql);
    //  echo"if";
     }

    else{
      $this->vues = 1;
    $sql= "INSERT INTO acess1 (page, ip, nbAcces) VALUES ('".$_SERVER['PHP_SELF']."','".$_SERVER['REMOTE_ADDR']."', 1)";

    $this->db->query($sql);
    //echo"else";
    
    }
 
    } 

         
      function showPageAccess() {
            return $this->vues;
        }

      function getStats() {
        echo'<table><tr><th>Nom</th><th>NbVues</th></tr>';
            $sql = "SELECT * FROM acess1 ";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbAcces'];
        echo '<tr><td>'.$page.'</td><td>'.$nb.'</td></tr>';
      }
      echo'</table>';

        }


         function getStatsByIp() {
        echo'<table><tr><th>Id</th><th>Nom</th><th>NbVues</th></tr>';
            $sql = "SELECT * FROM acess1 ";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbAcces'];
        $ip = $ligne ['ip'];
        echo '<tr><td>'.$ip.'</td><td>'.$page.'</td><td>'.$nb.'</td></tr>';
      }
      echo'</table>';

        }
      
	

  }

?>
