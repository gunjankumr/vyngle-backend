<?php
include_once 'more/bottlepercase/bottlepercase_server.php';
include_once 'more/city/city_server.php';
include_once 'more/country/country_server.php';
include_once 'more/contactus/contactus_server.php';
include_once 'more/critics/critics_server.php';
include_once 'more/criticsscore/criticsscore_server.php';
include_once 'more/currency/currency_server.php';
include_once 'more/itemsize/itemsize_server.php';


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
				case "country":
					$objCountry = new Country();
					$objCountry->performAction($action, $params);
					break;
				case "contactus":
					$objContactus = new ContactUs();
					$objContactus->performAction($action, $params);
					break;
				case "critics":
					$objCritics = new Critics();
					$objCritics->performAction($action, $params);
					break;
				case "criticsscore":
					$objCriticsScore = new CriticsScore();
					$objCriticsScore->performAction($action, $params);
					break;
				case "currency":
					$objCurrency = new Currency();
					$objCurrency->performAction($action, $params);
					break;
				case "geography":
					$objGeography = new Geography();
					$objGeography->performAction($action, $params);
					break;
				case "itemsize":
					$objItemSize = new ItemSize();
					$objItemSize->performAction($action, $params);
					break;
				case "legalinformation":
					$objLegalInformation = new LegalInformation();
					$objLegalInformation->performAction($action, $params);
					break;
				case "marketingtext":
					$objMarketingText = new MarketingText();
					$objMarketingText->performAction($action, $params);
					break;
				case "status":
					$objStatus = new Status();
					$objStatus->performAction($action, $params);
					break;
				case "tax":
					$objTax = new Tax();
					$objTax->performAction($action, $params);
					break;
				case "varietal":
					$objVarietal = new Varietal();
					$objVarietal->performAction($action, $params);
					break;
				case "vintage":
					$objVintage = new Vintage();
					$objVintage->performAction($action, $params);
					break;
				case "winetype":
					$objWineType = new WineType();
					$objWineType->performAction($action, $params);
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
