<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class Country extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteCountryMasterRecord":
					$response = $this->deleteCountryMasterRecord($params['countryName']);
					echo $response;
					break;
				case "addCountryMasterRecord":
					if ($this->addCountry($params['countryName'])) {
						echo $this->getCountryList();
						return;
					}
					echo "";
					break;
				case "uploadDataFromCSVFile":
					$response = $this->loadDataFromCSVFile($params);
					echo $response;
					break;
				default:
					break;
			}
		}
	}

	private function isRecordExists($countryName) {
		$sql_query = "SELECT * FROM country_master WHERE country = '$countryName'";
		$result = $this->mysqli->query($sql_query);

		$arrCountry = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addCountry($country) {
		if (isset($country)) {
			if (!$this->isRecordExists($country)) {
				$sql_query = "INSERT INTO country_master (country) VALUES ('$country')";

				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteCountryMasterRecord($country) {
		if (isset($country)) {
			if ($this->isRecordExists($country)) {
				$sql_query = "DELETE FROM country_master WHERE country = '$country'";
				if ($this->mysqli->query($sql_query)) {
					return $this->getCountryList();
				}
			}
		}
		return "";
	}

	private function loadDataFromCSVFile($fileName) {
		ini_set('auto_detect_line_endings', TRUE);
		$csv_file = CSV_PATH . $fileName;
		$row = 1;
		if (($handle = fopen($csv_file, "r")) !== FALSE) {
			$firstRow = "";
			while (($data = fgetcsv($handle, 1000, "\n")) !== FALSE) {
				$numOfColumn = count($data);
				if ($row == 1) {
					$firstRow = trim($data[0]);
				} else {
					//trim($firstRow) == "botpercase" &&
					if (!is_numeric($data[0])) {
						$this->addCountry($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getCountryList();
	}

	public function getCountryList() {
		$sql_query = "SELECT * FROM country_master ORDER BY country ASC";
		$result = $this->mysqli->query($sql_query);

		$arrCountry = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrCountry[] = $row['country'];
			}
		}

		return $this->composeCountryHtml($arrCountry);
	}

	private function composeCountryHtml($listOfCountry) {
		$countryListHtml = "";
		if ($listOfCountry != null && count($listOfCountry) > 0) {
			$countryListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfCountry); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$countryListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfCountry[$i] </td>";
				$countryListHtml.= "<td style='white-space: nowrap;'>";
				$countryListHtml.= "<button id='delete' type='button' value='$listOfCountry[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$countryListHtml.= "</td></tr>";
			}
			$countryListHtml.= "</table>";
		}
		return $countryListHtml;
	}
}