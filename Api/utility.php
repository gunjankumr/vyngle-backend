<?php
if (!ini_get('date.timezone')) {
	date_default_timezone_set('GMT');
}
include_once 'constants.php';

class Util {
	function __construct() {
	}
	
	/**
	 * Remove last character from supplied string
	 * @param unknown $srcString
	 * @return string
	 */
	public function removeLastCharacterFromString($srcString) {
		if (strlen($srcString) > 1) {
			$srcString = substr($srcString, 0, -1);
		}
		return $srcString;
	}
	
	public function getRequestContextValues($postParams, $responseStatus) {
		$jsonText = $this->composeContextJson($postParams, $responseStatus);
		return $jsonText;
	}
	
	public function getRequestContext($postParams) {
		$jsonText = $this->getRequestContextValues($postParams, true);
		return $jsonText;
	}
	
	private function composeContextJson($postParams, $responseStatus) {
		$serverStatus = ($responseStatus == true ? SERVER_STATUS_SUCCESS_CODE : SERVER_STATUS_FAILURE_CODE);
		$jsonText = '"request_context":{';
		$jsonText .= '"server_status":'. $serverStatus .',';
		$jsonText .= '"request_time":"'. date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) .'",';
		if (sizeof($postParams) > 0) {
			$keys = array_keys($postParams);
			
			for($i = 0; $i < count($keys); ++$i) {
				$jsonText .= '"' .$keys[$i] .'":"' . $postParams[$keys[$i]] .'",';
			}
		}
		$jsonText = $this->removeLastCharacterFromString($jsonText);
		$jsonText .= "}";
		return $jsonText;
	}
	
}