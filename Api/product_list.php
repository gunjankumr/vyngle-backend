<?php
include_once 'dbbase.php';

class ProductList extends DbBase {
	private $isResponseAvailable;
	
	function __construct() {
		parent::__construct();
	}
	
	private function setResponseStatus($status) {
		if ($this->isResponseAvailable == false) {
			$this->isResponseAvailable = $status;
		}
	}
	
	private function getMarketingText() {
		$sql_query = "SELECT * FROM marketing_text";
		$result = $this->mysqli->query($sql_query);
		return $result;
	}
	
	private function getProductList($isFeatured) {
		$queryStr = '';
		if ($isFeatured) {
			$featuredParam = $isFeatured ? 1 : 0;
			$queryStr = "SELECT * FROM product_transactional WHERE featured=$featuredParam AND sold_out=0 ORDER BY product_name";
		} else {
			$queryStr = "SELECT * FROM product_transactional WHERE sold_out=0 ORDER BY product_name";
		}
		$result = $this->mysqli->query($queryStr);
		return $result;
	}
	
	private function getMarketingTextJson($marketingTextRows) {
		$jsonText = '';
		if (mysqli_num_rows($marketingTextRows) == 0) {
			$this->setResponseStatus(false);
			return $jsonText;
		}
		
		$this->setResponseStatus(true);
		
		while($row = mysqli_fetch_array($marketingTextRows)) {
			$jsonText .= '{"id":'.$row['id'].',';
			$jsonText .= '"text":"'.$row['marketing_text'].'",';
			$jsonText .= '"image":"'.$row['image'].'"},';
		}
		$jsonText = $this->helper->removeLastCharacterFromString($jsonText);
		return $jsonText;
	}
	
	private function getProductsListJson($productRows) {
		$jsonText = '';
		if (mysqli_num_rows($productRows) == 0) {
			$this->setResponseStatus(false);
			return $jsonText;
		}
		
		$this->setResponseStatus(true);
		
		$this->responseAvailable = true;
		while($row = mysqli_fetch_array($productRows)) {
			$jsonText .= '{"product_id":"' . $row['product_id'] . '",';
			$jsonText .= '"product_name":"' . $row['product_name']. '",';
			
			$isNewItem = $row['new_item'] == 1 ? "true" : "false";
			$isLastBottle = $row['last_bottles'] == 1 ? "true" : "false";
			
			$jsonText .= '"new_item":' . $isNewItem . ',';
			$jsonText .= '"last_bottles":' . $isLastBottle . ',';
			$jsonText .= '"currency":"' . $row['currency']. '",';
			$jsonText .= '"bottle_price":' . number_format($row['bottle_price'], 2) . ',';
			$jsonText .= '"discount_bottle_price":' . number_format($row['discount_bottle_price'], 2) . '},';
		}
		$jsonText = $this->helper->removeLastCharacterFromString($jsonText);
		return $jsonText;
	}
	
	public function getWineList($postParams) {
		$marketingTextRows = $this->getMarketingText();
		$featuredWineRows = $this->getProductList(true);
		$allWineRows = $this->getProductList(false);
		
		$jsonResponse = '{"marketing_text":[';
		$jsonResponse .= $this->getMarketingTextJson($marketingTextRows);
		$jsonResponse .= '],"feature_wines":[';
		$jsonResponse .= $this->getProductsListJson($featuredWineRows);
		$jsonResponse .= '],"all_wines":[';
		$jsonResponse .= $this->getProductsListJson($allWineRows);
		$jsonResponse .= '],';
		$jsonResponse .= $this->helper->getRequestContextValues($postParams, $this->isResponseAvailable);
		$jsonResponse .= '}';
		
		return $jsonResponse;
	}
}