<?php 
include_once 'dbbase.php';

class ProductAvailability extends DbBase {	
	private $productId;
	private $orderQuantityInBottles;
	
	function __construct() {
		parent::__construct();
	}
	
	private function selectProductAvailability() {
		if (!empty($this->productId)) {
			$sql_query = "SELECT
			physical_inventory, qty_available
			FROM product_transactional
			WHERE product_id = '$this->productId' 
			AND sold_out=0";
			
			$result = $this->mysqli->query($sql_query);
			return $result;
		}
	}
	
	private function composeProductAvailabilityJson($resultRows) {
		$jsonText = '"product_availability":{"is_available":';
		if (mysqli_num_rows($resultRows) == 0) {
			$jsonText .= '"false"}';
			return $jsonText;
		}
		while($row = mysqli_fetch_array($resultRows)) {
			$physicalQty = $row['physical_inventory'];
			$qtyAvailable = $row['qty_available'];
			$isAvailable = "false";
			if ($this->orderQuantityInBottles <= $qtyAvailable) {
				$isAvailable = "true";
			}
			$jsonText .= $isAvailable . "}";
		}
		return $jsonText;
	}
	
	public function getProductAvailability($productId, $orderQuantityInBottles, $postParams) {
		$this->productId = $productId;
		$this->orderQuantityInBottles = $orderQuantityInBottles;
		$productAvailabilityResult = $this->selectProductAvailability();
		
		$jsonResponse = '{';
		$jsonResponse .= $this->composeProductAvailabilityJson($productAvailabilityResult);
		
		$jsonResponse .= ',';
		$jsonResponse .= $this->helper->getRequestContext($postParams);
		$jsonResponse .= '}';
		
		return $jsonResponse;
	}
}