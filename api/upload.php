<?php
include_once 'uploadbase.php';

class Upload extends UploadBase {
	
	function __construct() {
		parent::__construct();
	}
	
	public function uploadBottlePerCaseCsvFile($fileName) {
		
	}
}
if ($_POST["pagename"]) {
	$pagename = $_POST["pagename"];
}

$allowedExts = array("csv");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if ((($_FILES["file"]["type"] == "application/csv") 
		|| ($_FILES["file"]["type"] == "application/x-csv") 
		|| ($_FILES["file"]["type"] == "text/csv") 
		|| ($_FILES["file"]["type"] == "text/comma-separated-values") 
		|| ($_FILES["file"]["type"] == "text/x-comma-separated-values") 
		|| ($_FILES["file"]["type"] == "text/tab-separated-values") 
		|| ($_FILES["file"]["type"] == "application/vnd.ms-excel"))
		&& in_array($extension, $allowedExts)) {
	
    if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    } else {
        $filename = $_FILES["file"]["name"];
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

        if (file_exists(CSV_PATH . $filename)) {
            echo $filename . " already exists. ";
        } else {
        	move_uploaded_file($_FILES["file"]["tmp_name"], CSV_PATH. $filename);
            echo "Stored in: " . CSV_PATH. $filename;
        }
    }
} else {
    echo "Invalid file";
}
?>
