<?php
include_once 'dbbase.php';
include_once 'custom_date_formatter.php';

class OrderList extends DbBase {
	
	function __construct() {
		parent::__construct();
	}
	
	private function selectOrdersList($customerId) {
		$sql_query = "SELECT * FROM order_master ORDER BY order_id DESC";
		$result = $this->mysqli->query($sql_query);
		return $result;
	}
	
	private function composeOrderListJson($orderListRows) {
		$jsonText = '';
		if (mysqli_num_rows($orderListRows) == 0) {
			return $jsonText;
		}
		$dateFormatter = new DateFormatter();
		while($row = mysqli_fetch_array($orderListRows)) {
			$jsonText .= '{"order_id":' . $row['order_id'] . ',';
			$jsonText .= '"vendor_id":' . $row['vendor_id']. ',';
			$jsonText .= '"customer_id":' . $row['customer_id']. ',';
			$jsonText .= '"status":"' . $row['status']. '",';
			$jsonText .= '"total":' . $row['total']. ',';
			$jsonText .= '"legal_age":' . $row['legal_age']. ',';
			
			$formattedOrderDate = $dateFormatter->formatOrderDate($row['order_date']);
			$formattedDeliveryDate = $dateFormatter->formatDeliveryDate($row['delivery_date']);
			
			$jsonText .= '"order_date":"' . $formattedOrderDate . '",';
			$jsonText .= '"delivery_date":"' . $formattedDeliveryDate . '"},';
		}
		$jsonText = $this->helper->removeLastCharacterFromString($jsonText);
		return $jsonText;
	}
	
	public function getOrderList($customerId, $postParams) {
		$orderListRows = $this->selectOrdersList($customerId);
		
		$jsonResponse = '{"order_list":[';
		$jsonResponse .= $this->composeOrderListJson($orderListRows);
		$jsonResponse .= '],';
		$jsonResponse .= $this->helper->getRequestContext($postParams);
		$jsonResponse .= '}';
		
		return $jsonResponse;
	}
}
?>