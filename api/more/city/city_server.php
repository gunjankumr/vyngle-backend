<?php

require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class City extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteCityMasterRecord":
					$response = $this->deleteCityMasterRecord($params['id']);
					echo $response;
					break;
				case "addCityMasterRecord":
					if ($this->addCity($params['id'])) {
						echo $this->getCityList();
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

	private function isRecordExists($id) {
		$sql_query = "SELECT * FROM city_master WHERE botpercase = $id";
		$result = $this->mysqli->query($sql_query);

		$arrCity = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addCity($city) {
		if ($city > 0) {
			if (!$this->isRecordExists($city)) {
				$sql_query = "INSERT INTO city_master (botpercase) VALUES ($city)";

				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteCityMasterRecord($id) {
		if ($id > 0) {
			if ($this->isRecordExists($id)) {
				$sql_query = "DELETE FROM city_master WHERE botpercase = $id";
				if ($this->mysqli->query($sql_query)) {
					return $this->getCityList();
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
					if (is_numeric($data[0])) {
						$this->addCity($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getCityList();
	}

	public function getCityList() {
		$sql_query = "SELECT * FROM city_master ORDER BY city ASC";
		$result = $this->mysqli->query($sql_query);

		$arrCity = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrCity[] = $row['city'];
			}
		}

		return $this->composeCityHtml($arrCity);
	}

	private function composeCityHtml($listOfCity) {
		$cityListHtml = "";
		if ($listOfCity != null && count($listOfCity) > 0) {
			$cityListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfCity); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$cityListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfCity[$i] </td>";
				$cityListHtml.= "<td style='white-space: nowrap;'>";
				$cityListHtml.= "<button id='delete' type='button' value='$listOfCity[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$cityListHtml.= "</td></tr>";
			}
			$cityListHtml.= "</table>";
		}
		return $cityListHtml;
	}
}