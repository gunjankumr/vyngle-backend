<?php
if (!ini_get('date.timezone')) {
	date_default_timezone_set('GMT');
}
include_once 'dbbase.php';

define("CONTACT_US_EMAIL", "customerservice@vyngle.com");

class SendContactUsEmail extends DbBase {
	
	function __construct() {
		parent::__construct();
	}
	
	private function saveContactUsFormData($customerId, $from, $subject, $message, $status) {
		$isSuccess = $status == true ? 1 : 0;
		$dateTime = date('Y-m-d H:i:s');
		
		$queryStr = "
		INSERT INTO contact_us_log (customer_id, customer_email, email_subject, email_body, sent_date_time, status) VALUES ($customerId, '$from',
		'$subject', '$message', '$dateTime', $isSuccess);";
		
		$result = $this->mysqli->query($queryStr);
	}
	
	private function sendContactUsEmail($customerId, $from, $subject, $message) {
		$response = "";
		if (isset($from)) {
			$headers = 'From: '. $from . "\r\n" .
					'Reply-To: '. $from . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
			
			if (mail(CONTACT_US_EMAIL, $subject, $message, $headers)) {
				$response = "Email sent successfully!";
				$this->saveContactUsFormData($customerId, $from, $subject, $message, true);
			} else {
				$response = "Some error occured while sending the email!";
				$this->saveContactUsFormData($customerId, $from, $subject, $message, false);
			}
		} else {
			$response = "Email address is missing!";
		}
		return $response;
	}
	
	public function sendAndSaveContactUsFormContent($postParams) {
		$customerId = $_POST['customer_id'];
		$from = $_POST['from'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
		
		$response = $this->sendContactUsEmail($customerId, $from, $subject, $message);
		
		$jsonResponse = '{"response": "'. $response .'",';
		$jsonResponse .= $this->helper->getRequestContext($postParams);
		$jsonResponse .= '}';
		
		return $jsonResponse;
	}
}