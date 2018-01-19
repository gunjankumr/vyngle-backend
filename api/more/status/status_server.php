<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class Geography extends DbBase {
	private $country;
	private $region;
	private $subRegion;
	private $appellation;

	private $newCountry;
	private $newRegion;
	private $newSubRegion;
	private $newAppellation;

	function __construct() {
		parent::__construct();
	}

	public function setValues($country, $region, $subRegion, $appellation) {
		$this->country = $country;
		$this->region = $region;
		$this->subRegion = $subRegion;
		$this->appellation = $appellation;
	}

	private function cleanSpecialCharacter($str) {
		return addslashes($str);
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteGeographyMasterRecord":
					$this->initFields($params['record']);
					if (isset($this->country)) {
						$response = $this->deleteGeographyMasterRecord();
						echo $response;
					}
					break;
				case "editGeographyMasterRecord":
					$this->initFieldsForEdit($params['record']);
					if (isset($this->country) && isset($this->newCountry)) {
						$response = $this->editGeographyMasterRecord();
						echo $response;
					}
					break;
				case "addGeographyMasterRecord":
					$this->initFields($params['record']);
					if (isset($this->country)) {
						if ($this->addGeography()) {
							echo $this->getGeographyList();
							return;
						}
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

	private function initFields($record) {
		if (isset($record) && strpos($record, '#') !== false) {
			$values = explode("#", $record);
			$this->country = isset($values[0]) ? $values[0] : "";
			$this->region = isset($values[1]) ? $values[1] : "";
			$this->subRegion = isset($values[2]) ? $values[2] : "";
			$this->appellation = isset($values[3]) ? $values[3] : "";
		}
	}

	private function initFieldsForEdit($record) {
		if (isset($record) && strpos($record, '#') !== false && strpos($record, '@~@') !== false) {
			$records = explode("@~@", $record);
				
			$this->initFields($records[0]);
				
			$values = explode("#", $records[1]);
			$this->newCountry = isset($values[0]) ? $values[0] : "";
			$this->newRegion = isset($values[1]) ? $values[1] : "";
			$this->newSubRegion = isset($values[2]) ? $values[2] : "";
			$this->newAppellation = isset($values[3]) ? $values[3] : "";
		}
	}

	private function isRecordExists() {
		if (isset($this->country) && strlen($this->country) > 0) {
			$countryValue = $this->mysqli->real_escape_string($this->country);
			$regionValue = $this->mysqli->real_escape_string($this->region);
			$subRegionValue = $this->mysqli->real_escape_string($this->subRegion);
			$appellationValue = $this->mysqli->real_escape_string($this->appellation);
				
			$sql_query = "SELECT * FROM geography_master WHERE country = '$countryValue' AND region = '$regionValue' AND sub_region = '$subRegionValue' AND appellation = '$appellationValue'";
			if ($result = $this->mysqli->query($sql_query)) {
				if (mysqli_num_rows($result) > 0) {
					return true;
				}
			} else {
				echo $sql_query . "</br>";
			}
		}
		return false;
	}

	private function addGeography() {
		if (!$this->isRecordExists()) {
			$countryValue = $this->mysqli->real_escape_string($this->country);
			$regionValue = $this->mysqli->real_escape_string($this->region);
			$subRegionValue = $this->mysqli->real_escape_string($this->subRegion);
			$appellationValue = $this->mysqli->real_escape_string($this->appellation);
				
			$sql_query = "INSERT INTO geography_master (country, region, sub_region, appellation) VALUES ('$countryValue', '$regionValue', '$subRegionValue', '$appellationValue')";
			if ($this->mysqli->query($sql_query)) {
				return true;
			}
			return false;
		}
		return false;
	}

	private function deleteGeographyMasterRecord() {
		if ($this->isRecordExists()) {
			$countryValue = $this->mysqli->real_escape_string($this->country);
			$regionValue = $this->mysqli->real_escape_string($this->region);
			$subRegionValue = $this->mysqli->real_escape_string($this->subRegion);
			$appellationValue = $this->mysqli->real_escape_string($this->appellation);
				
			$sql_query = "DELETE FROM geography_master WHERE country = '$countryValue' AND region = '$regionValue' AND sub_region = '$subRegionValue' AND appellation = '$appellationValue'";
			if ($this->mysqli->query($sql_query)) {
				return $this->getGeographyList();
			}
		}
		return "";
	}

	private function editGeographyMasterRecord() {
		if ($this->isRecordExists()) {
			$countryValue = $this->mysqli->real_escape_string($this->country);
			$regionValue = $this->mysqli->real_escape_string($this->region);
			$subRegionValue = $this->mysqli->real_escape_string($this->subRegion);
			$appellationValue = $this->mysqli->real_escape_string($this->appellation);
				
			$newCountryValue = $this->mysqli->real_escape_string($this->newCountry);
			$newRegionValue = $this->mysqli->real_escape_string($this->newRegion);
			$newSubRegionValue = $this->mysqli->real_escape_string($this->newSubRegion);
			$newAppellationValue = $this->mysqli->real_escape_string($this->newAppellation);
				
			$sql_query = "UPDATE geography_master SET country = '$newCountryValue', region = '$newRegionValue', sub_region = '$newSubRegionValue', appellation = '$newAppellationValue'  WHERE country = '$countryValue' AND region = '$regionValue' AND sub_region = '$subRegionValue' AND appellation = '$appellationValue'";
			if ($this->mysqli->query($sql_query)) {
				return $this->getGeographyList();
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
				if ($row == 1) {
					$values = explode(",", $data[0]);
					$firstColumn = trim($values[0]);
					$secondColumn = trim($values[1]);
					$thirdColumn = trim($values[2]);
					$fourthColumn = trim($values[3]);
				} else {
					if (strpos($data[0], ',') !== false) {
						$values = explode(",", $data[0]);
						if (count($values) == 1) {
							$countryValue = isset($values[0]) ? trim($values[0]) : "";
							$this->setValues($countryValue, "", "", "");
								
						} else if (count($values) == 2) {
							$countryValue = isset($values[0]) ? trim($values[0]) : "";
							$regionValue = isset($values[1]) ? trim($values[1]) : "";
							$this->setValues($countryValue, $regionValue, "", "");
								
						} else if (count($values) == 3) {
							$countryValue = isset($values[0]) ? trim($values[0]) : "";
							$regionValue = isset($values[1]) ? trim($values[1]) : "";
							$subRegionValue = isset($values[2]) ? trim($values[2]) : "";
							$this->setValues($countryValue, $regionValue, $subRegionValue, "");
								
						} else if (count($values) == 4) {
							$countryValue = isset($values[0]) ? trim($values[0]) : "";
							$regionValue = isset($values[1]) ? trim($values[1]) : "";
							$subRegionValue = isset($values[2]) ? trim($values[2]) : "";
							$appellationValue = isset($values[3]) ? trim($values[3]) : "";
							$this->setValues($countryValue, $regionValue, $subRegionValue, $appellationValue);
						}

					} else {
						$countryValue = isset($values[0]) ? trim($values[0]) : "";
						$this->setValues($countryValue, "", "", "");
					}
						
					if (isset($this->country) && strlen($this->country) > 0) {
						$this->addGeography();
						$this->country = "";
						$this->region = "";
						$this->subRegion = "";
						$this->appellation = "";
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
			for ($i = 0; $i < count($listOfGeography); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$currentRow = $listOfGeography[$i];
				$strRecordValue = $currentRow['country']."#".$currentRow['region']."#".$currentRow['sub_region']."#".$currentRow['appellation'];

				$geographyListHtml.= "<tr style='background: $colorCode;'>";
				$geographyListHtml.="<td>". $currentRow['country'] ."</td>";
				$geographyListHtml.="<td>". $currentRow['region'] ."</td>";
				$geographyListHtml.="<td>". $currentRow['sub_region'] ."</td>";
				$geographyListHtml.="<td>". $currentRow['appellation'] ."</td>";
				$geographyListHtml.= "<td style='white-space: nowrap;'>";
				$geographyListHtml.= "<button id='edit' type='button' value=\"$strRecordValue\" onclick='editRecord(this.value);' >Edit</button>";
				$geographyListHtml.= "<button id='delete' type='button' value=\"$strRecordValue\" onclick='deleteRecord(this.value);' >Delete</button>";
				$geographyListHtml.= "</td></tr>";
			}
		}
		return $geographyListHtml;
	}
}