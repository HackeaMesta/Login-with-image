<?php
class Connector {
	protected $host;
	protected $user;
	protected $pass;
	protected $name;

	function __construct() {
		// Tessting

		$this->host = "localhost";
		$this->user = "root";
		$this->pass = "toor";
		$this->name = "modelos";

	}

	// Database connection
	function DBConnection() {
	    $pdo = new PDO("mysql:host=". $this->host .";dbname=" . $this->name, $this->user, $this->pass);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    return $pdo;
	}
}
