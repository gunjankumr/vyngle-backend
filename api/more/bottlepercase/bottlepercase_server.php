<?php

require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class BottlePerCase extends DbBase {
	function __construct() {
		parent::__construct();
	}
	
	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteBottlesPerCaseMasterRecord":
					$response = $this->deleteBottlePerCaseMasterRecord($params['id']);
					echo $response;
					break;
				case "addBottlesPerCaseMasterRecord":
					if ($this->addBottlesPerCase($params['id'])) {
						echo $this->getBottlesPerCaseList();
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
		$sql_query = "SELECT * FROM bottle_per_case_master WHERE botpercase = $id";
		$result = $this->mysqli->query($sql_query);
		
		$arrBottlesPerCase = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}
	
	private function addBottlesPerCase($bottlesPerCase) {
		if ($bottlesPerCase > 0) {
			if (!$this->isRecordExists($bottlesPerCase)) {
				$sql_query = "INSERT INTO bottle_per_case_master (botpercase) VALUES ($bottlesPerCase)";
				
				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}
	
	private function deleteBottlePerCaseMasterRecord($id) {
		if ($id > 0) {
			if ($this->isRecordExists($id)) {
				$sql_query = "DELETE FROM bottle_per_case_master WHERE botpercase = $id";
				if ($this->mysqli->query($sql_query)) {
					return $this->getBottlesPerCaseList();
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
						$this->addBottlesPerCase($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getBottlesPerCaseList();
	}
	
	public function getBottlesPerCaseList() {
		$sql_query = "SELECT * FROM bottle_per_case_master ORDER BY botpercase ASC";
		$result = $this->mysqli->query($sql_query);
		
		$arrBottlesPerCase = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrBottlesPerCase[] = $row['botpercase'];
			}
		}
		
		return $this->composeBottlesPerCaseHtml($arrBottlesPerCase);
	}
	
	private function composeBottlesPerCaseHtml($listOfBottles) {
		$bottlesListHtml = "";
		if ($listOfBottles != null && count($listOfBottles) > 0) {
			$bottlesListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfBottles); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$bottlesListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfBottles[$i] bottles</td>";
				$bottlesListHtml.= "<td style='white-space: nowrap;'>";
				$bottlesListHtml.= "<button id='delete' type='button' value='$listOfBottles[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$bottlesListHtml.= "</td></tr>";
			}
			$bottlesListHtml.= "</table>";
		}
		return $bottlesListHtml;
	}
}