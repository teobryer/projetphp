<?php
/* ***************************************************************
  Fichier    : exemple-test_v2.php
  Langage    : PHP 5.0+
  Auteur     : Vincent Barré
  Extensions : Aucune
  Paramètres : Aucun
*************************************************************** */
include_once('Form.class.php');

  $myform = new Form("authen.php", "Connectez vous ", "post");
  
  // Traitement du fichier
/*  if (! $fic = fopen("items_v2.txt", "r")) die("Impossible d'ouvrir le fichier !");
  $myform->beginOption();
  while (! feof($fic)) {
	$opt = trim(fgets($fic)); // trim() permet de supprimer les espaces et retours à la ligne en début / fin de chaine
	if (! empty($opt)) {	// élimine les éventuelles lignes vides
		$opts = explode("\t", $opt);
		$myform->setOption($opts[0], $opts[1]);  
	}
  }*/
  header('connexion.php');
    
  $myform->setText("nom", "Votre nom : ", null);
  $myform->setText("code", "Votre code : ", null);
 
  $myform->setSubmit("envoi");
  
  $myform->getForm();
  
?>
