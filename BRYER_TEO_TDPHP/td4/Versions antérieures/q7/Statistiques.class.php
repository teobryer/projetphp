<?php
//setcookie('navigateur', $_SERVER['HTTP_USER_AGENT'],(time()+3600*24*12));
  class Statistiques{
      
  private $db;
protected $vues;
private $navigateur;
private $cookie;
private $date;
    public function __construct() {
     // $this->navigateur = $this->get_browser_name($_COOKIE['navigateur']);
      
       include('db.inc.php');
     $this->db = new PDO("$server:host=$host;dbname=$base", $user, $passwd);
      
///$sql= "SELECT CURRENT_DATE";

       // $this->date=date("Y-m-d");
     
   

        if (isset($_COOKIE['nav9'])){
         $this->navigateur = $_COOKIE['nav9'];

         }
      else{
        
        $this->navigateur= uniqid();
        $this->cookie =setcookie('nav9', $this->navigateur, (time()+3600*24*12) );
       // $sql= "INSERT INTO visite (user, dateJ , page, ip, nbVu, navigateur) VALUES ( '".$_COOKIE['user']."' ,CURRENT_DATE() ,'".$_SERVER['PHP_SELF']."','".$_SERVER['REMOTE_ADDR']."', 1,'".$this->navigateur."')";

       // $this->db->query($sql);
        
      }






      $sql = "SELECT page FROM visite WHERE user= '".$_COOKIE['user']."' AND dateJ= CURRENT_DATE() AND page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."' AND navigateur='".$this->navigateur."'";
      $val = $this->db->query($sql);
      $test = $this->db->query($sql);

    if($test->rowCount()!= null){

      $sql = "SELECT nbVu FROM visite WHERE user= '".$_COOKIE['user']."' AND dateJ= CURRENT_DATE() AND page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'AND navigateur='".$this->navigateur."'";
      $val = $this->db->query($sql);
      $ligne = $val->fetch();
      $this->vues = $ligne['nbVu'];
      $this->vues = ($this->vues+1);
      $sql = "UPDATE visite SET nbVu = ($this->vues)
      WHERE user='".$_COOKIE['user']."' AND dateJ= CURRENT_DATE() AND page='".$_SERVER['PHP_SELF']."'AND ip= '".$_SERVER['REMOTE_ADDR']."'AND navigateur='".$this->navigateur."'" ;
      $this->db->query($sql);
    //  echo"if";
     }

    else{
      echo'test';
      $this->vues = 1;
    $sql= "INSERT INTO visite (user, dateJ, page, ip, nbVu, navigateur) VALUES ( '".$_COOKIE['user']."' ,CURRENT_DATE(),'".$_SERVER['PHP_SELF']."','".$_SERVER['REMOTE_ADDR']."', 1,'".$this->navigateur."')";

    $this->db->query($sql);

      
    //echo"else";

    
    }
 
    } 

   // function get_browser_name($user_agent){
   ///   if(strpos($user_agent, 'Opera')|| strpos($user_agent,'OPR/')) return'Opera';
    //  elseif(strpos($user_agent, 'Edge')) return 'Edge';
    //  elseif(strpos($user_agent, 'Chrome')) return 'Chrome';
    //  elseif(strpos($user_agent, 'Safari')) return 'Safari';
   //   elseif(strpos($user_agent, 'Firefox')) return 'Firefox';
    //  elseif(strpos($user_agent, 'MSIE')||strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

//return 'Other';
   // }

         
      function showPageAccess() {
            return $this->vues;

        }

      function getStatsByPage() {
        echo'<table><tr><th>Nom</th><th>NbVues</th></tr>';
          /*  $sql = "SELECT * FROM visite ";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbVu'];*/
                 $arrayPage = array();

          
          //recupere les pages
          $sql = "SELECT DISTINCT page FROM visite ";
           // $sql = "SELECT SUM(nbVu) FROM visite ";
            $val = $this->db->query($sql);
            while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        //insert dans le tableau les page 
       array_push($arrayPage,$page);
       // echo $page.'<br />';
          }

          for ($i = 0; $i < count($arrayPage); $i++) {
     $sql = "SELECT  SUM(nbVu) as test FROM visite WHERE page ='".$arrayPage[$i]."'";
     $val = $this->db->query($sql);
 while ($ligne = $val->fetch()){
        
        $pagevu = $ligne['test'];
        //insert dans le tableau les page 
    

  
       // echo $page.'<br />';

         

        echo '<tr><td>'.$arrayPage[$i].'</td><td>'.$pagevu.'</td></tr>';
         }
      }
      echo'</table>';

        }




        function getStats() {
        echo'<table><tr><th>User</th><th>Navigateur</th><th>Nom</th><th>NbVues</th></tr>';
            $sql = "SELECT * FROM visite ";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbVu'];
        $user = $ligne['user'];
          $navigateur = $ligne ['navigateur'];
        echo '<tr><th>'.$user.'</th><th>'.$navigateur.'</th><td>'.$page.'</td><td>'.$nb.'</td></tr>';
      }
      echo'</table>';

        }


        function getDailyStats() {
        echo'<table><tr><th>Date</th><th>Navigateur</th><th>Nom</th><th>NbVues</th></tr>';
            $sql = "SELECT * FROM visite  WHERE dateJ=CURRENT_DATE()";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbVu'];
        $date= $ligne['dateJ'];
          $navigateur = $ligne ['navigateur'];
        echo '<tr><th>'.$date.'</th><th>'.$navigateur.'</th><td>'.$page.'</td><td>'.$nb.'</td></tr>';
      }
      echo'</table>';

        }


         function getStatsByIp() {
        echo'<table><tr><th>Ip</th><th>Nom</th><th>NbVues</th></tr>';
            $sql = "SELECT * FROM visite ";
      $val = $this->db->query($sql);
      while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        $nb = $ligne['nbVu'];
        $ip = $ligne ['ip'];
        echo '<tr><td>'.$ip.'</td><td>'.$page.'</td><td>'.$nb.'</td></tr>';
      }
      echo'</table>';

        }
        function getStatsPNG(){
          //tableau stock les pages du site 
          $arrayPage = array();

          $arrayPageVue = array();
          //recupere les pages
          $sql = "SELECT DISTINCT page FROM visite  ";
           // $sql = "SELECT SUM(nbVu) FROM visite ";
            $val = $this->db->query($sql);
            while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        //insert dans le tableau les page 
       array_push($arrayPage,$page);
       // echo $page.'<br />';
          }

          for ($i = 0; $i < count($arrayPage); $i++) {
     $sql = "SELECT  SUM(nbVu) as test FROM visite WHERE page ='".$arrayPage[$i]."'";
     $val = $this->db->query($sql);
 while ($ligne = $val->fetch()){
        
        $pagevu = $ligne['test'];
        //insert dans le tableau les page 
   

     array_push($arrayPageVue,$pagevu);
       // echo $page.'<br />';

          }

}
  //max valeur de la page la plus visité
  ;

for($i = 0; $i < count($arrayPage); $i++){
 $size =($arrayPageVue[$i]*500)/max($arrayPageVue);
    echo $arrayPage[$i].'  '.'<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Color-blue.JPG" width="'.$size.'" height="20" />'.'<br />';
}
       

        
        
        }


function getDailyStatsPNG(){
          //tableau stock les pages du site 
          $arrayPage = array($date);

          $arrayPageVue = array();
          //recupere les pages
          $sql = "SELECT DISTINCT page FROM visite  Where dateJ= ".$date."";
           // $sql = "SELECT SUM(nbVu) FROM visite ";
            $val = $this->db->query($sql);
            while ($ligne = $val->fetch()){
        
        $page = $ligne['page'];
        //insert dans le tableau les page 
       array_push($arrayPage,$page);
       // echo $page.'<br />';
          }

          for ($i = 0; $i < count($arrayPage); $i++) {
     $sql = "SELECT  SUM(nbVu) as test FROM visite WHERE page ='".$arrayPage[$i]."'";
     $val = $this->db->query($sql);
 while ($ligne = $val->fetch()){
        
        $pagevu = $ligne['test'];
        //insert dans le tableau les page 
   

     array_push($arrayPageVue,$pagevu);
       // echo $page.'<br />';

          }

}
  //max valeur de la page la plus visité
  ;

for($i = 0; $i < count($arrayPage); $i++){
 $size =($arrayPageVue[$i]*500)/max($arrayPageVue);
    echo $arrayPage[$i].'  '.'<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Color-blue.JPG" width="'.$size.'" height="20" />'.'<br />';
}
       

        
        
        }










      
	

  }

?>
