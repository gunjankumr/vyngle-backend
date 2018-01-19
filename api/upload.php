<?php
include_once 'uploadbase.php';
require_once __DIR__ . '/more/bottlepercase/bottlepercase_server.php';
require_once __DIR__ . '/more/city/city_server.php';
require_once __DIR__ . 'more/country/country_server.php';
require_once __DIR__ . 'more/contactus/contactus_server.php';
require_once __DIR__ . 'more/critics/critics_server.php';
require_once __DIR__ . 'more/criticsscore/criticsscore_server.php';
require_once __DIR__ . 'more/currency/currency_server.php';
require_once __DIR__ . '/more/geography/geography_server.php';
require_once __DIR__ . 'more/itemsize/itemsize_server.php';
require_once __DIR__ . 'more/legalinformation/legalinformation_server.php';
require_once __DIR__ . 'more/marketingtext/marketingtext_server.php';
require_once __DIR__ . 'more/status/status_server.php';
require_once __DIR__ . 'more/tax/tax_server.php';
require_once __DIR__ . 'more/varietal/varietal_server.php';
require_once __DIR__ . 'more/vintage/vintage_server.php';
require_once __DIR__ . 'more/winetype/winetype_server.php';


class Upload extends UploadBase {

	public function processUploadedFile($pageName, $fileName) {
		if (isset($pageName) && isset($fileName)) {
			switch ($pageName) {
				case "bottlepercase":
					$this->loadBottlesPerCaseData($fileName);
					break;
				case "city":
					$this->loadCityData($fileName);
					break;
				case "country":
					$this->loadCountryData($fileName);
					break;
				case "contactus":
					$this->loadContactUsData($fileName);
					break;
				case "critics":
					$this->loadCriticsData($fileName);
					break;
				case "criticsscore":
					$this->loadCriticsScoreData($fileName);
					break;
				case "currency":
					$this->loadCurrencyData($fileName);
					break;									
				case "geography":
					$this->loadGeographyData($fileName);
					break;
				case "itemsize":
					$this->loadItemSizeData($fileName);
					break;
				case "legalinformation":
					$this->loadLegalInformationData($fileName);
					break;
				case "marketingtext":
					$this->loadMarketingTextData($fileName);
					break;
				case "status":
					$this->loadStatusData($fileName);
					break;
				case "tax":
					$this->loadTaxData($fileName);
					break;
				case "varietal":
					$this->loadVarietalData($fileName);
					break;
				case "vintage":
					$this->loadVintageData($fileName);
					break;
				case "winetype":
					$this->loadWineTypeData($fileName);
					break;										
				default:
					break;
			}
		}
	}
	
	private function loadBottlesPerCaseData($fileName) {
		if (isset($fileName)) {
			$objBottlePerCase = new BottlePerCase();
			$objBottlePerCase->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadCityData($fileName) {
		if (isset($fileName)) {
			$objCity = new City();
			$objCity->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadCountryData($fileName) {
		if (isset($fileName)) {
			$objCountry = new Country();
			$objCountry->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadContactUsData($fileName) {
		if (isset($fileName)) {
			$objContactUs = new ContactUs();
			$objContactUs->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadCriticsData($fileName) {
		if (isset($fileName)) {
			$objCritics = new Critics();
			$objCritics->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadCriticsScoreData($fileName) {
		if (isset($fileName)) {
			$objCriticsScore = new CriticsScore();
			$objCriticsScore->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadCurrencyData($fileName) {
		if (isset($fileName)) {
			$objCurrency = new Currency();
			$objCurrency->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadGeographyData($fileName) {
		if (isset($fileName)) {
			$objGeography = new Geography();
			$objGeography->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadItemSizeData($fileName) {
		if (isset($fileName)) {
			$objItemSize = new ItemSize();
			$objItemSize->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadLegalInformationData($fileName) {
		if (isset($fileName)) {
			$objLegalInformation = new LegalInformation();
			$objLegalInformation->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadMarketingTextData($fileName) {
		if (isset($fileName)) {
			$objMarketingText = new MarketingText();
			$objMarketingText->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadStatusData($fileName) {
		if (isset($fileName)) {
			$objStatus = new Status();
			$objStatus->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadTaxData($fileName) {
		if (isset($fileName)) {
			$objTax = new Tax();
			$objTax->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadVarietalData($fileName) {
		if (isset($fileName)) {
			$objVarietal = new Varietal();
			$objVarietal->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadVintageData($fileName) {
		if (isset($fileName)) {
			$objVintage = new Vintage();
			$objVintage->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
	private function loadWineTypeData($fileName) {
		if (isset($fileName)) {
			$objWineType = new WineType();
			$objWineType->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
	
}

$pageName = "";
if ($_POST["pagename"]) {
	$pageName= $_POST["pagename"];
}

$allowedExts = array("csv");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if ((($_FILES["file"]["type"] == "application/csv") 
		|| ($_FILES["file"]["type"] == "application/x-csv") 
		|| ($_FILES["file"]["type"] == "text/csv") 
		|| ($_FILES["file"]["type"] == "text/comma-separated-values") 
		|| ($_FILES["file"]["type"] == "text/x-comma-separated-values") 
		|| ($_FILES["file"]["type"] == "text/tab-separated-values") 
		|| ($_FILES["file"]["type"] == "application/vnd.ms-excel"))
		&& in_array($extension, $allowedExts)) {
	
    if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    } else {
    	$fileName= $_FILES["file"]["name"];
//         echo "Upload: " . $_FILES["file"]["name"] . "<br>";
//         echo "Type: " . $_FILES["file"]["type"] . "<br>";
//         echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//         echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

        $objUpload = new Upload();
        
        if (file_exists(CSV_PATH . $fileName)) {
        	unlink(CSV_PATH . $fileName);
        	move_uploaded_file($_FILES["file"]["tmp_name"], CSV_PATH . $fileName);
        	$objUpload->processUploadedFile($pageName, $fileName);

        } else {
        	move_uploaded_file($_FILES["file"]["tmp_name"], CSV_PATH . $fileName);
        	$objUpload->processUploadedFile($pageName, $fileName);
        }
    }
} else {
    echo "Invalid file";
}
?>
