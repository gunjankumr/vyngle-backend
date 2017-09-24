<?php
$to      = 'customerservice@vyngle.com';
$subject = 'testing email from php';
$message = 'hello';
$headers = 'From: ranjandeo@gmail.com' . "\r\n" .
		'Reply-To: ranjandeo@gmail.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);