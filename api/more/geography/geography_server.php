<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class Geography extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteGeographyMasterRecord":
					$response = $this->deleteGeographyMasterRecord($params['record']);
					echo $response;
					break;
			    case "editGeographyMasterRecord":
					$response = $this->editGeographyMasterRecord($params['record']);						
					echo $response;
					break;
				case "addGeographyMasterRecord":
					if ($this->addGeography($params['record'])) {
						echo $this->getGeographyList();
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

	private function isRecordExists($record) {
		$cols = explode("#", $record);
		$sql_query = "SELECT * FROM geography_master WHERE country = '$cols[0]' AND region = '$cols[1]' AND sub_region = '$cols[2]' AND appellation = '$cols[3]' ";
		$result = $this->mysqli->query($sql_query);
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addGeography($record) {
		if (isset($record)) {
			$cols = explode("#", $record);
			if (!$this->isRecordExists($record)) {
				$sql_query = "INSERT INTO geography_master (country, region, sub_region, appellation) VALUES ('$cols[0]', '$cols[1]', '$cols[2]', '$cols[3]')";
				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteGeographyMasterRecord($record) {
		if (isset($record)) {
			if ($this->isRecordExists($record)) {
				$cols = explode("#", $record);
				$sql_query = "DELETE FROM geography_master WHERE country = '$cols[0]' AND region = '$cols[1]' AND sub_region = '$cols[2]' AND appellation = '$cols[3]' ";
				if ($this->mysqli->query($sql_query)) {
					return $this->getGeographyList();
				}
			}
		}
		return "";
	}
	
	private function editGeographyMasterRecord($record) {
		if (isset($record)) {
			$allRecord = explode("##", $record);
			$oldRecord = $allRecord[0];
			$newRecord = $allRecord[1];
			if ($this->isRecordExists($oldRecord)) {
				$cols = explode("#", $oldRecord);
				$colsNew = explode("#", $newRecord);
				$sql_query = "update geography_master set country = '$colsNew[0]' AND region = '$colsNew[1]' AND sub_region = '$colsNew[2]' AND appellation = '$colsNew[3]'  WHERE country = '$cols[0]' AND region = '$cols[1]' AND sub_region = '$cols[2]' AND appellation = '$cols[3]'";
				if ($this->mysqli->query($sql_query)) {
					return $this->getGeographyList();
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
				if ($row == 4) {
					$firstColumn = trim($data[0]);
					$secondColumn = trim($data[1]);
					$thirdColumn = trim($data[2]);
					$fourthColumn = trim($data[3]);
						
				} else {
					//trim($firstRow) == "botpercase" &&
					if (!is_numeric($data[0])) {
						$this->addGeography($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getGeographyList();
	}

	public function getGeographyList() {
		
		$sql_query = "SELECT * FROM geography_master ORDER BY country ASC";
		$result = $this->mysqli->query($sql_query);

		$arrGeography = array();
		if (mysqli_num_rows($result) > 0) {
			$index = 0;
			while($row = mysqli_fetch_array($result)){
				$arrGeography[$index]['country'] = isset($row['country']) ? $row['country'] : "";
				$arrGeography[$index]['region'] = isset($row['region']) ? $row['region'] : "";
				$arrGeography[$index]['sub_region'] = isset($row['sub_region']) ? $row['sub_region'] : "";
				$arrGeography[$index]['appellation'] = isset($row['appellation']) ? $row['appellation'] : "";
				$index++;
			}				
		}
		return $this->composeGeographyHtml($arrGeography);
	}

	private function composeGeographyHtml($listOfGeography) {
		$geographyListHtml = "";
		if ($listOfGeography != null && count($listOfGeography) > 0) {
// 			$geographyListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfGeography); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$currentRow = $listOfGeography[$i];
				$strRecordValue = $currentRow['country']."#".$currentRow['region']."#".$currentRow['sub_region']."#".$currentRow['appellation'];
				
				$geographyListHtml.= "<tr style='background: $colorCode;' onclick='display()';>";
				$geographyListHtml.="<td>". $currentRow['country'] ."</td>";
				$geographyListHtml.="<td>". $currentRow['region'] ."</td>";
				$geographyListHtml.="<td>". $currentRow['sub_region'] ."</td>";
				$geographyListHtml.="<td>". $currentRow['appellation'] ."</td>";
				$geographyListHtml.= "<td style='white-space: nowrap;'>";
				$geographyListHtml.= "<button id='edit' type='button' value='$strRecordValue' onclick='editRecord(this.value);' >Edit</button>";
				$geographyListHtml.= "<button id='delete' type='button' value='$strRecordValue' onclick='deleteRecord(this.value);' >Delete</button>";
				$geographyListHtml.= "</td></tr>";
			}
		}
		return $geographyListHtml;
	}
}