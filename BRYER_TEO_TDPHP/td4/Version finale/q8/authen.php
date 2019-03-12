<?php

try{
 include('db.inc.php');
     $db = new PDO("$server:host=$host;dbname=$base", $user, $passwd);

    
    

      $id = $_POST['nom'];
      $mdp = $_POST['code'];
     $sql = "SELECT * FROM users WHERE id='".$id."' AND mdp='".$mdp."'" ;
     $test = $db->query($sql);
    if($test->rowCount()!= null){
       $cookie =setcookie('user', $id, (time()+3600*24*12) );
        header("location:page1.php");


        
      }
        else{
         
          header("location:connexion.php");
        }

}

catch(Exception $e){
  echo 'error';
}

?>
