<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class CriticsScore extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteCriticsScoreMasterRecord":
					$response = $this->deleteCriticsScoreMasterRecord($params['criticsScore']);
					echo $response;
					break;
				case "addCriticsScoreMasterRecord":
					if ($this->addCriticsScore($params['criticsScore'])) {
						echo $this->getCriticsScoreList();
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

	private function isRecordExists($criticsScore) {
		$sql_query = "SELECT * FROM critics_score_master WHERE critics_score = '$criticsScore'";
		$result = $this->mysqli->query($sql_query);

		$arrCriticsScore = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addCriticsScore($criticsScore) {
		if (isset($criticsScore)) {
			if (!$this->isRecordExists($criticsScore)) {
				$sql_query = "INSERT INTO critics_score_master (critics_score) VALUES ('$criticsScore')";

				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteCriticsScoreMasterRecord($criticsScore) {
		if (isset($criticsScore)) {
			if ($this->isRecordExists($criticsScore)) {
				$sql_query = "DELETE FROM critics_score_master WHERE critics_score = '$criticsScore'";
				if ($this->mysqli->query($sql_query)) {
					return $this->getCriticsScoreList();
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
						$this->addCriticsScore($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getCriticsScoreList();
	}

	public function getCriticsScoreList() {
		$sql_query = "SELECT * FROM critics_score_master ORDER BY critics_score ASC";
		$result = $this->mysqli->query($sql_query);

		$arrCriticsScore = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrCriticsScore[] = $row['critics_score'];
			}
		}

		return $this->composeCriticsScoreHtml($arrCriticsScore);
	}

	private function composeCriticsScoreHtml($listOfCriticsScore) {
		$criticsScoreListHtml = "";
		if ($listOfCriticsScore != null && count($listOfCriticsScore) > 0) {
			$criticsScoreListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfCriticsScore); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$criticsScoreListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfCriticsScore[$i] </td>";
				$criticsScoreListHtml.= "<td style='white-space: nowrap;'>";
				$criticsScoreListHtml.= "<button id='delete' type='button' value='$listOfCriticsScore[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$criticsScoreListHtml.= "</td></tr>";
			}
			$criticsScoreListHtml.= "</table>";
		}
		return $criticsScoreListHtml;
	}
}