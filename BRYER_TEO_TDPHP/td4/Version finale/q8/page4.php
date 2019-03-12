<?php


  require('Statistiques.class.php');

  $stats = new Statistiques();

;
echo "<strong>Nombre d'acc&eacute;s &agrave; la page :</strong> <br />";
echo $stats->showPageAccess();
echo "<strong> <br/>Nombre d'acc&eacute;s des machines differentes ayant acced&eacute; &agrave; la page courrante :</strong> <br />";
echo $stats->getStatsByIp();
echo "<strong>Nombre d'acc&eacute;s pour une page unique, un utilisateur unique et navigateur unique  :</strong> <br />";
echo $stats->getStats();
echo "<strong>Nombre d'acc&eacute;s global sous forme graphique pour chaque page :</strong> <br />";
echo $stats->getStatsPNG();
echo "<strong>--> En chiffres </strong> <br />";
echo $stats->getStatsbyPage();
echo "<strong>Nombre d'acc&eacute;s jour par jour & utilisateur unique :</strong> <br />";
echo $stats->getDailyStats();
echo "<strong>Nombre d'acc&eacute;s de chaque page pour une date donn&eacute;e (2018-10-05) :</strong> <br />";
echo $stats->getDailyStatsPNG('2018-10-05');

?>
