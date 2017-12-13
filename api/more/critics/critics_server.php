<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class Critics extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteCriticsMasterRecord":
					$response = $this->deleteCriticsMasterRecord($params['criticsName']);
					echo $response;
					break;
				case "addCriticsMasterRecord":
					if ($this->addCritics($params['criticsName'])) {
						echo $this->getCriticsList();
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

	private function isRecordExists($criticsName) {
		$sql_query = "SELECT * FROM critics_master WHERE critics = '$criticsName'";
		$result = $this->mysqli->query($sql_query);

		$arrCritics = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addCritics($critics) {
		if (isset($critics)) {
			if (!$this->isRecordExists($critics)) {
				$sql_query = "INSERT INTO critics_master (critics) VALUES ('$critics')";

				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteCriticsMasterRecord($critics) {
		if (isset($critics)) {
			if ($this->isRecordExists($critics)) {
				$sql_query = "DELETE FROM critics_master WHERE critics = '$critics'";
				if ($this->mysqli->query($sql_query)) {
					return $this->getCriticsList();
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
						$this->addCritics($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getCriticsList();
	}

	public function getCriticsList() {
		$sql_query = "SELECT * FROM critics_master ORDER BY critics ASC";
		$result = $this->mysqli->query($sql_query);

		$arrCritics = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrCritics[] = $row['critics'];
			}
		}

		return $this->composeCriticsHtml($arrCritics);
	}

	private function composeCriticsHtml($listOfCritics) {
		$criticsListHtml = "";
		if ($listOfCritics != null && count($listOfCritics) > 0) {
			$criticsListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfCritics); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$criticsListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfCritics[$i] </td>";
				$criticsListHtml.= "<td style='white-space: nowrap;'>";
				$criticsListHtml.= "<button id='delete' type='button' value='$listOfCritics[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$criticsListHtml.= "</td></tr>";
			}
			$criticsListHtml.= "</table>";
		}
		return $criticsListHtml;
	}
}