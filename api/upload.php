<?php
include_once 'uploadbase.php';
require_once __DIR__ . '/more/bottlepercase/bottlepercase_server.php';

class Upload extends UploadBase {

	public function processUploadedFile($pageName, $fileName) {
		if (isset($pageName) && isset($fileName)) {
			switch ($pageName) {
				case "bottlepercase":
					$this->loadBottlesPerCaseData($fileName);
					break;
				default:
					break;
			}
		}
	}
	
	private function loadBottlesPerCaseData($fileName) {
		if (isset($fileName)) {
			$objBottlePerCase = new BottlePerCase();
			$objBottlePerCase->performAction("uploadDataFromCSVFile", $fileName);
		}
	}
}

$pageName = "";
if ($_POST["pagename"]) {
	$pageName= $_POST["pagename"];
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
    	$fileName= $_FILES["file"]["name"];
//         echo "Upload: " . $_FILES["file"]["name"] . "<br>";
//         echo "Type: " . $_FILES["file"]["type"] . "<br>";
//         echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//         echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

        $objUpload = new Upload();
        
        if (file_exists(CSV_PATH . $fileName)) {
        	unlink(CSV_PATH . $fileName);
        	move_uploaded_file($_FILES["file"]["tmp_name"], CSV_PATH . $fileName);
        	$objUpload->processUploadedFile($pageName, $fileName);

        } else {
        	move_uploaded_file($_FILES["file"]["tmp_name"], CSV_PATH . $fileName);
        	$objUpload->processUploadedFile($pageName, $fileName);
        }
    }
} else {
    echo "Invalid file";
}
?>
