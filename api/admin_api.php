<?php
include_once 'more/bottlepercase/bottlepercase_server.php';
include_once 'more/city/city_server.php';

class AdminApi {

	public function executeFunction($function, $action, $params) {
		if (isset($function) && isset($action)) {
			switch ($_GET['function']) {
				case "bottlepercase":
					$objBottlePerCase = new BottlePerCase();
					$objBottlePerCase->performAction($action, $params);
					break;
				case "city":
					$objCity = new City();
					$objCity->performAction($action, $params);
					break;
				default:
					break;
			}
		}
	}
}

if (isset($_GET['function']) && isset($_GET['action']) && !empty($_POST)) {
	$function  = $_GET['function'];
	$action = $_GET['action'];
	$postParams = $_POST;
	
	$adminApi = new AdminApi();
	$adminApi->executeFunction($function, $action, $postParams);
}
