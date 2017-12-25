<?php
$_SESSION["admin_name"] = "";
setcookie ("admin_name", "");
setcookie ("admin_password", "");
session_unset();
session_destroy();
header("Location: index.php");
?>