<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class Currency extends DbBase {
	function __construct() {
		parent::__construct();
	}

	public function performAction($action, $params) {
		if (isset($action) && strlen($action) > 0) {
			switch ($action) {
				case "deleteCurrencyMasterRecord":
					$response = $this->deleteCurrencyMasterRecord($params['currencyName']);
					echo $response;
					break;
				case "addCurrencyMasterRecord":
					if ($this->addCurrency($params['currencyName'])) {
						echo $this->getCurrencyList();
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

	private function isRecordExists($currencyName) {
		$sql_query = "SELECT * FROM currency_master WHERE currency = '$currencyName'";
		$result = $this->mysqli->query($sql_query);

		$arrCurrency = array();
		if (mysqli_num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	private function addCurrency($currency) {
		if (isset($currency)) {
			if (!$this->isRecordExists($currency)) {
				$sql_query = "INSERT INTO currency_master (currency) VALUES ('$currency')";

				if ($this->mysqli->query($sql_query)) {
					return true;
				}
				return false;
			}
		}
		return false;
	}

	private function deleteCurrencyMasterRecord($currency) {
		if (isset($currency)) {
			if ($this->isRecordExists($currency)) {
				$sql_query = "DELETE FROM currency_master WHERE currency = '$currency'";
				if ($this->mysqli->query($sql_query)) {
					return $this->getCurrencyList();
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
						$this->addCurrency($data[0]);
					}
				}
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
		unlink(CSV_PATH . $fileName);
		return $this->getCurrencyList();
	}

	public function getCurrencyList() {
		$sql_query = "SELECT * FROM currency_master ORDER BY currency ASC";
		$result = $this->mysqli->query($sql_query);

		$arrCurrency = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$arrCurrency[] = $row['currency'];
			}
		}

		return $this->composeCurrencyHtml($arrCurrency);
	}

	private function composeCurrencyHtml($listOfCurrency) {
		$currencyListHtml = "";
		if ($listOfCurrency != null && count($listOfCurrency) > 0) {
			$currencyListHtml= "<table width='100%'>";
			for ($i = 0; $i < count($listOfCurrency); $i++) {
				$colorCode = "#c1cdd7";
				if ($i % 2 == 0) {
					$colorCode = "#839aaf";
				}
				$currencyListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listOfCurrency[$i] </td>";
				$currencyListHtml.= "<td style='white-space: nowrap;'>";
				$currencyListHtml.= "<button id='delete' type='button' value='$listOfCurrency[$i]' onclick='deleteRecord(this.value);' >Delete</button>";
				$currencyListHtml.= "</td></tr>";
			}
			$currencyListHtml.= "</table>";
		}
		return $currencyListHtml;
	}
}