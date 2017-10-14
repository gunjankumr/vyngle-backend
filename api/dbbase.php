<?php
include_once 'utility.php';
include_once 'database.php';

class DbBase {
	protected $db;
	protected $mysqli;
	protected $helper;
	
	function __construct() {
		$this->db = Database::getInstance();
		$this->mysqli = $this->db->getConnection();
		$this->helper = new Util();
	}
}