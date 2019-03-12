<?php


  require('Statistiques.class.php');

  $stats = new Statistiques();

;
echo "Nombre d'accès à la page : <br />";
echo $stats->showPageAccess();
echo $stats->getStatsByIp();
echo $stats->getStats();



?>
