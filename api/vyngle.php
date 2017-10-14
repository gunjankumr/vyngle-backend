<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'product_list.php';
include_once 'product_detail.php';
include_once 'product_availability.php';
include_once 'legal_information.php';
include_once 'send_contact_us.php';
include_once 'order_list.php';
include_once 'order_details.php';

class Vyngle {
	var $productList;
	var $productDetail;
	var $productAvailability;
	var $legalInformation;
	var $sendContactUsContent;
	var $orderList;
	var $orderDetails;
	
	function __construct() {
		$this->productList = new ProductList();
		$this->productDetail = new ProductDetail();
		$this->productAvailability = new ProductAvailability();
		$this->legalInformation = new LegalInformation();
		$this->sendContactUsContent = new SendContactUsEmail();
		$this->orderList = new OrderList();
		$this->orderDetails = new OrderDetails();
	}
	
	public function getWineList($postParams) {
		return $this->productList->getWineList($postParams);
	}
	
	public function getProductDetail($productId, $postParams) {
		return $this->productDetail->getProductDetail($productId, $postParams);
	}
	
	public function getProductAvailability($productId, $orderQuantityInBottles, $postParams) {
		return $this->productAvailability->getProductAvailability($productId, $orderQuantityInBottles, $postParams);
	}
	
	public function getLegalInformation($postParams) {
		return $this->legalInformation->getLegalInformation($postParams);
	}
	
	public function sendAndSaveContactUsEmailContent($postParams) {
		return $this->sendContactUsContent->sendAndSaveContactUsFormContent($postParams);
	}
	
	public function getOrderList($customerId, $postParams) {
		return $this->orderList->getOrderList($customerId, $postParams);
	}
	
	public function getOrderDetails($orderId, $postParams) {
		return $this->orderDetails->getOrderDetails($orderId, $postParams);
	}
}

if (isset($_GET['f']) && !empty($_POST)) {
	$vyngle = new Vyngle();
	$postParams = $_POST;
	//header('Content-Type: application/json');
	header('Content-Type: application/json; charset=utf-8');
	switch ($_GET['f']) {
		case "getWineList":
			$response = $vyngle->getWineList($postParams);
			echo $response;
			break;
		case "getProductDetail":
			$productId = $_POST['product_id'];
			$response = $vyngle->getProductDetail($productId, $postParams);
			echo $response;
			break;
		case "getProductAvailability":
			$productId = $_POST['product_id'];
			$orderQuantityInBottles = $_POST['order_quantity_in_bottles'];
			$response = $vyngle->getProductAvailability($productId, $orderQuantityInBottles, $postParams);
			echo $response;
			break;
		case "getLegalInformation":
			$response = $vyngle->getLegalInformation($postParams);
			echo $response;
			break;
		case "getOrderList":
			$customerId = $_POST['customer_id'];
			$response = $vyngle->getOrderList($customerId, $postParams);
			echo $response;
			break;
		case "getOrderDetails":
			$orderId = $_POST['order_id'];
			$response = $vyngle->getOrderDetails($orderId, $postParams);
			echo $response;
			break;
		case "sendContactUs":
			$response = $vyngle->sendAndSaveContactUsEmailContent($postParams);
			echo $response;
			break;
		default:
			break;
	}
} else {
	echo "<h1>Access denied!!</h1>";
}
?>