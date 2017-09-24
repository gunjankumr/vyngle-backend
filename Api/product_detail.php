<?php
include_once 'dbbase.php';

class ProductDetail extends DbBase {
	private $productId;
	
	function __construct() {
		parent::__construct();
	}
	
	private function addEscapeCharacterIntoString($src) {
		$dest = mysqli_real_escape_string($this->mysqli, $src);
		return str_replace("'", "\'", $dest);
	}
	
	private function getImageList() {
		if (!empty($this->productId)) {
			$sql_query = "";
			if ($this->productId == "V-GP-1") {
				$sql_query = "SELECT * FROM product_image WHERE product_id='$this->productId' ORDER BY priority ASC";
			} else {
				$sql_query = "SELECT * FROM product_image WHERE product_id='V-CS-3' ORDER BY priority ASC";
			}
			$result = $this->mysqli->query($sql_query);
			return $result;
		}
	}
	
	private function selectProductDetail() {
		if (!empty($this->productId)) {
			$sql_query = "SELECT
				product_master.*,
				product_transactional.physical_inventory,
				product_transactional.qty_available,
				product_transactional.currency,
				product_transactional.actual_cost_per_bottle,
				product_transactional.import_cost_per_bottle,
				product_transactional.duty_cost_per_bottle,
				product_transactional.storage_cost_per_bottle,
				product_transactional.bottle_price,
				product_transactional.discount_bottle_price,
				product_transactional.case_price,
				product_transactional.discount_case_price,
				product_transactional.case_price,
				product_transactional.discount_case_price,
				product_transactional.free_shipping,
				product_transactional.discount_case_price,
				product_transactional.free_shipping,
				product_transactional.sold_out,
				product_transactional.new_item,
				product_transactional.last_bottles,
				product_transactional.publish_to_live,
				product_transactional.additional_fees,
				product_transactional.featured
			FROM product_master
			LEFT JOIN product_transactional ON
				product_master.product_id = product_transactional.product_id
			WHERE product_transactional.product_id = '$this->productId'";
						
			$result = $this->mysqli->query($sql_query);
			return $result;
		}
	}
	
	private function composeImageListJson($images) {
		$jsonText = '"image_list":[';
		if (mysqli_num_rows($images) == 0) {
			$jsonText .= ']';
			return $jsonText;
		}
		while($row = mysqli_fetch_array($images)) {
			$jsonText .= '{"image_id":' . $row['image_id'] . ',';
			$jsonText .= '"thumbnail_image_path":"' . $row['thumbnail_image_path'] . '",';
			$jsonText .= '"image_path":"' . $row['image_path'] . '",';
			$jsonText .= '"priority":' . $row['priority']. '},';
		}
		$jsonText = $this->helper->removeLastCharacterFromString($jsonText);
		$jsonText .= ']';
		return $jsonText;
	}
	
	private function composeProductDetailJson($productDetail) {
		$jsonText = '"product_detail":{';
		if (mysqli_num_rows($productDetail) == 0) {
			$jsonText .= '}';
			return $jsonText;
		} else {
			while($row = mysqli_fetch_array($productDetail)) {
				// from product master
				$jsonText .= '"product_id":"' . $row['product_id']. '",';
				$jsonText .= '"product_name":"' .$this->addEscapeCharacterIntoString($row['product_name']). '",';
				$jsonText .= '"category_name":"' .$this->addEscapeCharacterIntoString($row['category_name']). '",';
				$jsonText .= '"item_size":"' .$row['item_size']. '",';
				$jsonText .= '"botpercase":' .$row['botpercase']. ',';
				$jsonText .= '"vintage":' .$row['vintage']. ',';
				$jsonText .= '"country":"' .$row['country']. '",';
				$jsonText .= '"region":"' .$row['region']. '",';
				$jsonText .= '"sub_region":"' .$row['sub_region']. '",';
				$jsonText .= '"appellation":"' .$row['appellation']. '",';
				$jsonText .= '"estate":"' .$row['estate']. '",';
				$jsonText .= '"varietal":"' .$row['varietal']. '",';
				$jsonText .= '"type":"' .$row['type']. '",';
				$jsonText .= '"critic1_name":"' .$this->addEscapeCharacterIntoString($row['critic1_name']). '",';
				$jsonText .= '"critic2_name":"' .$this->addEscapeCharacterIntoString($row['critic2_name']). '",';
				$jsonText .= '"critic3_name":"' .$this->addEscapeCharacterIntoString($row['critic3_name']). '",';
				$jsonText .= '"critic1_initial":"' .$row['critic1_initial']. '",';
				$jsonText .= '"critic2_initial":"' .$row['critic2_initial']. '",';
				$jsonText .= '"critic3_initial":"' .$row['critic3_initial']. '",';
				$jsonText .= '"critic1_score":' .number_format($row['critic1_score'], 2). ',';
				$jsonText .= '"critic2_score":' .number_format($row['critic2_score'], 2). ',';
				$jsonText .= '"critic3_score":' .number_format($row['critic3_score'], 2). ',';
				$jsonText .= '"critic1_text":"' .$this->addEscapeCharacterIntoString($row['critic1_text']). '",';
				$jsonText .= '"critic2_text":"' .$this->addEscapeCharacterIntoString($row['critic2_text']). '",';
				$jsonText .= '"critic3_text":"' .$this->addEscapeCharacterIntoString($row['critic3_text']). '",';
				$jsonText .= '"food_pairing":"' .$this->addEscapeCharacterIntoString($row['food_pairing']). '",';
				$jsonText .= '"tasting_notes":"' .$this->addEscapeCharacterIntoString($row['tasting_notes']). '",';
				$jsonText .= '"additional_information":"' .$this->addEscapeCharacterIntoString($row['additional_information']). '",';
				$jsonText .= '"producer_notes":"' .$this->addEscapeCharacterIntoString($row['producer_notes']). '",';
				$jsonText .= '"wine_maker_notes":"' .$this->addEscapeCharacterIntoString($row['wine_maker_notes']). '",';
				
				$isDiscountEligible = $row['discount_eligible'] == 1 ? "true" : "false";
				$isPromotionEligible = $row['promotion_eligible'] == 1 ? "true" : "false";
				$isFeatured = $row['featured'] == 1 ? "true" : "false";
				$isPreOrder = $row['preorder'] == 1 ? "true" : "false";
				$jsonText .= '"featured":' .$isFeatured. ',';
				$jsonText .= '"preorder":' .$isPreOrder. ',';
				$jsonText .= '"discount_eligible":' .$isDiscountEligible. ',';
				$jsonText .= '"promotion_eligible":' .$isPromotionEligible. ',';
				
				$jsonText .= '"vendor_id":"' .$row['vendor_id']. '",';
		
				// from product transactional
				$jsonText .= '"physical_inventory":' .$row['physical_inventory']. ',';
				$jsonText .= '"qty_available":' .$row['qty_available']. ',';
				$jsonText .= '"currency":"' .$row['currency']. '",';
				$jsonText .= '"actual_cost_per_bottle":' .number_format($row['actual_cost_per_bottle'], 2). ',';
				$jsonText .= '"import_cost_per_bottle":' .number_format($row['import_cost_per_bottle'], 2). ',';
				$jsonText .= '"duty_cost_per_bottle":' .number_format($row['duty_cost_per_bottle'], 2). ',';
				$jsonText .= '"storage_cost_per_bottle":' .number_format($row['storage_cost_per_bottle'], 2). ',';
				$jsonText .= '"bottle_price":' .number_format($row['bottle_price'], 2). ',';
				$jsonText .= '"discount_bottle_price":' .number_format($row['discount_bottle_price'], 2). ',';
				$jsonText .= '"case_price":' .number_format($row['case_price'], 2). ',';
				$jsonText .= '"discount_case_price":' .number_format($row['discount_case_price'], 2). ',';
				
				$isFreeShipping = $row['free_shipping'] == 1 ? "true" : "false";
				$isSoldOut = $row['sold_out'] == 1 ? "true" : "false";
				$isNewItem = $row['new_item'] == 1 ? "true" : "false";
				$isLastBottle = $row['last_bottles'] == 1 ? "true" : "false";
				$isPublishToLive = $row['publish_to_live'] == 1 ? "true" : "false";
				$jsonText .= '"free_shipping":' .$isFreeShipping. ',';
				$jsonText .= '"sold_out":' .$isSoldOut. ',';
				$jsonText .= '"new_item":' .$isNewItem. ',';
				$jsonText .= '"last_bottles":' .$isLastBottle. ',';
				$jsonText .= '"publish_to_live":' .$isPublishToLive. ',';
				
				$jsonText .= '"additional_fees":' .number_format($row['additional_fees'], 2). '}';
			}
			return $jsonText;
		}
	}
	
	public function getProductDetail($productId, $postParams) {
		$this->productId = $productId;
		$images = $this->getImageList();
		$productDetail = $this->selectProductDetail();
		
		$jsonResponse = '{';
		$jsonResponse .= $this->composeImageListJson($images);
		$jsonResponse .= ',';
		$jsonResponse .= $this->composeProductDetailJson($productDetail);
		
		$jsonResponse .= ',';
		$jsonResponse .= $this->helper->getRequestContext($postParams);
		$jsonResponse .= '}';
		
		return $jsonResponse;
	}
}