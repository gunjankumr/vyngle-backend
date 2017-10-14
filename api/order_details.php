<?php
include_once 'dbbase.php';

class OrderDetails extends DbBase {
	private $orderId;
	
	function __construct() {
		parent::__construct();
	}
	
	private function selectOrderDetails() {
		if (!empty($this->orderId)) {
			$sql_query = "SELECT * FROM order_details WHERE order_id=$this->orderId";
			$result = $this->mysqli->query($sql_query);
			return $result;
		}
	}
	
	private function composeOrderDetailsJson() {
		
	}
	
	public function getOrderDetails($orderId, $postParams) {
		$this->orderId = $orderId;
		$orderDetailsResult = $this->selectOrderDetails();
		
		
		
	}
}