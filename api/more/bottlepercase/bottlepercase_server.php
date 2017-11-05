<?php
require_once __DIR__."/../../dbbase.php";

class BottlePerCase extends DbBase {
	function __construct() {
		parent::__construct();
	}
	
	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteBottlesPerCaseMasterRecord":
					$response = $this->deleteBottlePerCaseMasterRecord($params);
					echo $response;
					break;
				case "addBottlesPerCaseMasterRecord":
					$response = $this->addBottlesPerCase($params);
					echo $response;
					break;
				default:
					break;
			}
		}
	}
	
	private function addBottlesPerCase($postParams) {
		$bottlesPerCase = $postParams['id'];
		if ($bottlesPerCase > 0) {
			$sql_query = "INSERT INTO bottle_per_case_master (botpercase) VALUES ($bottlesPerCase)";
			
			if ($this->mysqli->query($sql_query)) {
				return $this->getBottlesPerCaseList();
			}
			return "";
		}
		return "";
	}
	
	private function deleteBottlePerCaseMasterRecord($postParams) {
		$id = $postParams['id'];
		if ($id > 0) {
			$sql_query = "DELETE FROM bottle_per_case_master WHERE botpercase = $id";
			if ($this->mysqli->query($sql_query)) {
				return $this->getBottlesPerCaseList();
			}
		}
		return "";
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