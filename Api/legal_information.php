<?php
include_once 'dbbase.php';

class LegalInformation extends DbBase {
	
	function __construct() {
		parent::__construct();
	}
	
	private function selectLegalInformation() {
		$sql_query = "SELECT * FROM legal_information";
		$result = $this->mysqli->query($sql_query);
		return $result;
	}
	
	private function composeLegalInformationJson($legalInformationResult) {
		$jsonText = '"legal_information":[';
		if (mysqli_num_rows($legalInformationResult) == 0) {
			$jsonText .= ']';
			return $jsonText;
		}
		while($row = mysqli_fetch_array($legalInformationResult)) {
			$jsonText .= '{"id":' . $row['id'] . ',';
			$jsonText .= '"text":"' . addslashes($row['text']) . '"},';
		}
		$jsonText = $this->helper->removeLastCharacterFromString($jsonText);
		$jsonText .= ']';
		return $jsonText;
	}
	
	public function getLegalInformation($postParams) {
		$legalInformationResult= $this->selectLegalInformation();
		
		$jsonResponse = '{';
		$jsonResponse .= $this->composeLegalInformationJson($legalInformationResult);
		
		$jsonResponse .= ',';
		$jsonResponse .= $this->helper->getRequestContext($postParams);
		$jsonResponse .= '}';
		
		return $jsonResponse;
	}
}