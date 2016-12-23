<?php
session_start();
include 'login.php';
/**
*
*/
class Validate {
	private $file;
	private $l;
	protected $master_key;
	protected $master_key_2;

	function __construct() {
		$this->l = new LoginApi();
		$this->master_key_2 = $this->l->returnMasterKey();
		$this->master_key = "a05ac87e074e841c4c3ce1631523e4c314d1806e6b32696b2a5466c47cb387e5";
	}

	// Valida la llave en el archivo
	function validate($file) {
		$orig_contents = file_get_contents($file);
		$key = explode("key:", $orig_contents);
        // Se encontro una llave
		if (!empty($key[1])) {
			if (($key[1] == $this->master_key) || ($key[1] == $this->master_key_2)) {
			  // La llave es correcta
        $_SESSION['token'] = $this->master_key;
        $_SESSION['ytrfvbnjjhgfgb'] = md5(rand(0, 9999) * rand(0, 9999));
        session_write_close();
				$this->l->unlockAccount(1);
				return "ok";
			} else {
				return "Llave incorrecta";
			}
		} else {
			return "Archivo invalido: llave no encontrada";
		}
	}
}

// Testing
$uploads_dir = 'uploads/';
if ( strstr($_FILES['fileKey']['type'], 'image/') ) {
	$tmp_name = $_FILES["fileKey"]["tmp_name"];
	$name = $_FILES["fileKey"]["name"];
	$fullFile = "$uploads_dir/$name";
	if ( !move_uploaded_file($tmp_name, $fullFile) ) {
		echo "Error: " . PHP_EOL;
	} else {
		$v = new Validate();
		echo $v->validate($fullFile);
	}
} else {
	echo "Sube un archivo valido (.png .jpg .jpeg)";
}
