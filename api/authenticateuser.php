<?php
header('Access-Control-Allow-Origin: *');
$password = "sunil@#@#$%";
if (!empty($_POST) && isset($_POST['password']) && $_POST['password'] == $password) {
	echo "Success";
} else {
	echo "Failure";
}
?>