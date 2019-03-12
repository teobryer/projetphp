<?php
/* ***************************************************************
  Fichier    : Form.class.php (classe)
  Langage    : PHP 5.0+
  Auteur     : Vincent Barré
  Extensions : Aucune
  Paramètres : Aucun
*************************************************************** */

class Form {
	
	protected $leForm;
	
	public function __construct($action, $titre, $methode) {
		
		$this->leForm = "<form action = \"$action\" method=\"$methode\"><fieldset><legend>$titre</legend>";
		
	}
	
	protected function setter($type, $code, $label, $value) {

		$this->leForm .= "$label <input type=\"$type\" name=\"$code\" value=\"$value\"><br>"; 
				
	}
	
	public function setLabel($label) {
		
		$this->leForm .= "$label <br>";
		
	}

	public function setText($code, $label, $value) {
		
		$this->setter("text", $code, $label, $value);
		
	}
	
	public function setSubmit($code) {
		
		$this->setter("submit", null, null, $code);
		
	}

    public function getForm() {
		
		echo $this->leForm."</fieldset></form>";
		
	}

}

?>
