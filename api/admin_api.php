<?php
include_once 'dbbase.php';

class AdminApi extends DbBase {
	private $productId;
	
	function __construct() {
		parent::__construct();
	}
	
	public function addBottlesPerCase($postParams) {
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
	
	public function deleteBottlePerCaseMasterRecord($postParams) {
		$id = $postParams['id'];
		if ($id > 0) {
			$sql_query = "DELETE FROM bottle_per_case_master WHERE botpercase = $id";
			if ($this->mysqli->query($sql_query)) {
				return $this->getBottlesPerCaseList();
			}
		}
		return "";
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

if (isset($_GET['f']) && !empty($_POST)) {
	$adminApi = new AdminApi();
	$postParams = $_POST;
	switch ($_GET['f']) {
		case "deleteBottlesPerCaseMasterRecord":
			$response = $adminApi->deleteBottlePerCaseMasterRecord($postParams);
			echo $response;
			break;
		case "addBottlesPerCaseMasterRecord":
			$response = $adminApi->addBottlesPerCase($postParams);
			echo $response;
			break;
		default:
			break;
	}
}
