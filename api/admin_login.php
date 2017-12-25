<?php
include_once 'dbbase.php';

class AdminLogin extends DbBase {
	
	function __construct() {
		parent::__construct();
	}
	
	public function login($userName, $password) {
		$userValues = array();
		$userValues['status'] = "Failure";
		
		if (isset($userName) && isset($password)) {
			$sql_query= "SELECT * FROM admin WHERE user_id = '" . $userName . "' AND password = '" . md5($password) . "'";
			$result = $this->mysqli->query($sql_query);

			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				$userValues['status'] = "Success";
				$userValues['user_id'] = $row['user_id'];
				$userValues['password'] = $password;
			} else {
				$userValues['status'] = "Failure";
			}
		}
		return $userValues;
	}
}