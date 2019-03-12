<?php

try{
 include('db.inc.php');
     $db = new PDO("$server:host=$host;dbname=$base", $user, $passwd);

    
    

      $id = $_POST['nom'];
      $mdp = $_POST['code'];
     $sql = "SELECT * FROM users WHERE id='".$id."' AND mdp='".$mdp."'" ;
     $test = $db->query($sql);
    if($test->rowCount()!= null){
        unset($_COOKIE['id2']);
        unset($_COOKIE['mdp2']);
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
