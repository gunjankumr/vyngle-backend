<?php
require_once __DIR__ . '/../../uploadbase.php';
require_once __DIR__ . '/../../dbbase.php';

class ContactUs extends DbBase {
	function __construct() {
		parent::__construct();
	}


	public function getContactUsList() {
		$sql_query = "SELECT * FROM contact_us_log ORDER BY sent_date_time DESC";
		$result = $this->mysqli->query($sql_query);
	
		$arrContactUs = array();
		if (mysqli_num_rows($result) > 0) {
// 			while($row = $result->fetch_array())
// 			{
// 				$rows[] = $row;
// 			}
				
			while($row = mysqli_fetch_array($result)) {
				$arrContactUs[] = $row;
			}
		}
	
		return $this->composeContactUsHtml($arrContactUs);
	}
	
	private function composeContactUsHtml($listOfContactUs) {
		$contactUsListHtml = "";
		if ($listOfContactUs != null && count($listOfContactUs) > 0) {
			$contactUsListHtml= "<table width='100%'>";
			foreach($listOfContactUs as $row)
			{
// 				echo $row['CountryCode'];
// 			}
				
// 			for ($i = 0; $i < count($listOfContactUs); $i++) {
// 				$colorCode = "#c1cdd7";
// 				if ($i % 2 == 0) {
// 					$colorCode = "#839aaf";
// 				}
				$colorCode = "#839aaf";				
				$contactUsListHtml.= "<tr style='background: $colorCode; white-space: nowrap;'>";
				$contactUsListHtml.= "<td width='20%'>$row[customer_id] </td>";
				$contactUsListHtml.= "<td width='20%'>$row[customer_email] </td>";
				$contactUsListHtml.= "<td width='20%'>$row[email_subject] </td>";
				$contactUsListHtml.= "<td width='20%'>$row[email_body] </td>";
				$contactUsListHtml.= "<td width='10%'>$row[sent_date_time] </td>";
				$contactUsListHtml.= "<td width='10%'>$row[status] </td>";				
				$contactUsListHtml.= "</td></tr>";
			}
			$contactUsListHtml.= "</table>";
		}
		return $contactUsListHtml;
	}
	
// 	public function getContactUsList() {
// 		$sql_query = "SELECT * FROM contact_us_log ORDER BY sent_date_time DESC";
// 		$result = $this->mysqli->query($sql_query);

// 		$arrContactUs = array();
// 		if (mysqli_num_rows($result) > 0) {
// 			while($row = mysqli_fetch_array($result)) {
// 				$arrContactUs = $row;
// 			}
// 		}

// 		return $this->composeContactUsHtml($arrContactUs);
// 	}

// 	private function composeContactUsHtml($listContactUs) {
// 		$contactUsListHtml = "";
// 		if ($listContactUs != null && count($listContactUs) > 0) {
// 			$contactUsListHtml= "<table width='100%'>";
// 			for ($i = 0; $i < count($listContactUs); $i++) {
// 				$colorCode = "#c1cdd7";
// 				if ($i % 2 == 0) {
// 					$colorCode = "#839aaf";
// 				}
// 				$contactUsListHtml.= "<tr style='background: $colorCode;'><td width='99%'>$listContactUs[$i] </td>";
// 				$contactUsListHtml.= "<td style='white-space: nowrap;'>";
// 				$contactUsListHtml.= "</td></tr>";
// 			}
// 			$contactUsListHtml.= "</table>";
// 		}
// 		return $contactUsListHtml;
// 	}
}