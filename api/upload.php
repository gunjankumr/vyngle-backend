<?php

if ($_POST["pagename"]) {
	$pagename = $_POST["pagename"];
}

// $allowedExts = array("gif", "jpeg", "jpg", "png");
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

        if (file_exists("uploads/" . $filename)) {
            echo $filename . " already exists. ";
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $filename);
            echo "Stored in: " . "uploads/" . $filename;
        }
    }
} else {
    echo "Invalid file";
}
?>
