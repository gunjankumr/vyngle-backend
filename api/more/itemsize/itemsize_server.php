<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class ItemSize extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteItemSizeMasterRecord":
					$response = $this->deleteItemSizeMasterRecord($params['itemSize']);
					echo $response;
					break;
				case "addItemSizeMasterRecord":
					if ($this->addItemSize($params['itemSize'])) {
						echo $this->getItemSizeList();
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

	private function isRecordExists($itemSize) {
		$sql_query = "SELECT * FROM item_size_master WHERE item_size = '$itemSize'";
		$result = $this->mysqli->query($sql_query);

		$arrItemSize = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addItemSize($itemSize) {
		if (isset($itemSize)) {
			if (!$this->isRecordExists($itemSize)) {
				$sql_query = "INSERT INTO item_size_master (item_size) VALUES ('$itemSize')";

				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteItemSizeMasterRecord($itemSize) {
		if (isset($itemSize)) {
			if ($this->isRecordExists($itemSize)) {
				$sql_query = "DELETE FROM item_size_master WHERE item_size = '$itemSize'";
				if ($this->mysqli->query($sql_query)) {
					return $this->getItemSizeList();
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
						$this->addItemSize($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getItemSizeList();
	}

	public function getItemSizeList() {
		$sql_query = "SELECT * FROM item_size_master ORDER BY item_size ASC";
		$result = $this->mysqli->query($sql_query);

		$arrItemSize = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrItemSize[] = $row['item_size'];
			}
		}

		return $this->composeItemSizeHtml($arrItemSize);
	}

	private function composeItemSizeHtml($listOfItemSize) {
		$itemSizeListHtml = "";
		if ($listOfItemSize != null && count($listOfItemSize) > 0) {
			$itemSizeListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfItemSize); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$itemSizeListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfItemSize[$i] </td>";
				$itemSizeListHtml.= "<td style='white-space: nowrap;'>";
				$itemSizeListHtml.= "<button id='delete' type='button' value='$listOfItemSize[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$itemSizeListHtml.= "</td></tr>";
			}
			$itemSizeListHtml.= "</table>";
		}
		return $itemSizeListHtml;
	}
}