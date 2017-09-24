<?php
if (!ini_get('date.timezone')) {
	date_default_timezone_set('GMT');
}
class DateFormatter {
	function __construct() {
	}
	
	public function formatOrderDate($date) {
		if (isset($date)) {
			return date('D M j Y', strtotime($date));
		}
		return "";
	}
	
	public function formatDeliveryDate($date) {
		//8pm May 14th, 2017
		//date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
		if (isset($date)) {
			return date('g a M jS, Y', strtotime($date));
		}
		return "";
	}
}
?>