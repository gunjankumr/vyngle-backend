<?php
include_once '../../../../../api/more/geography/geography_server.php';

$objGeography = new Geography();
 $geographyListStr = $objGeography->getGeographyList();
?>
<head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
</html>
<style>
table {
	border-collapse: collapse;
}

#main {
	border: 1px solid #32414e;
}

table {
    margin: 0 auto;
}

th, td {
	height: 40px;
}

table th {
	background: #394a59;
	color: white;
	vertical-align: middle;
}

  .customButton {
   		 background-color: #394a59; /* Grey */
    	 border: none;
   		 color: white;
    	 padding: 15px 32px;
    	 text-align: center;
    	 text-decoration: none;
    	 display: inline-block;
         font-size: 16px;
	}
#f1_upload_process{
   z-index:100;
   position:absolute;
   visibility:hidden;
   text-align:center;
   width:400px;
   margin:0px;
   padding:0px;
   background-color:#fff;
   border:1px solid #ccc;
}
 
form{
   text-align:center;
   width:390px;
   margin:0px;
   padding:5px;
   background-color:#fff;
   border:1px solid #ccc;
 
}

</style>
<script src="../../../js/jquery-1.8.3.min.js"></script>
<script src="../../../js/commonmethods.js"></script>
<script>


function display() {
	$("#dialog").dialog("open");
	return false;
}

function editRecord(record, newrecord) {
	 var r = confirm("Are you sure you want to edit the record?");
	 if (r == true) {
		 var request = $.ajax({
		   url: "../../../../../api/admin_api.php?function=geography&action=editGeographyMasterRecord",
		   type: "POST",
		   data: {record : record, newrecord : newrecord},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#geography-list").html(msg);
				
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });    
	 }
}

 function deleteRecord(record) {
	 var r = confirm("Are you sure you want to delete the record?");
	 if (r == true) {
		 var request = $.ajax({
		   url: "../../../../../api/admin_api.php?function=geography&action=deleteGeographyMasterRecord",
		   type: "POST",
		   data: {record : record},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#geography-list").html(msg);
				
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });    
	 }
 }

 function addRecord() {
	 var city = $('#text_input_new_entry').val();
	 if (city.length > 0) {
		$('#text_input_new_entry').val(""); 
	 	var request = $.ajax({
		   url: "../../../../../api/admin_api.php?function=city&action=addCityMasterRecord",
		   type: "POST",
		   data: {cityName : city},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#city-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });
	 } else {
		alert("Please input city!");
	 }
 }

 function uploadResponse(responseStatus) {
	 if (responseStatus.length > 0) {
		$("#city-list").html(responseStatus);
		$("#fileToUpload").val('');
	 }
 }

 function submitForm() {
	 if (validateInput(document.getElementById("fileToUpload")) == true) {
	     var formData = new FormData(document.getElementById("fileinfo"));
	     formData.append("pagename", "city");
	     upload(uploadResponse, formData);
	     return false;
	 }
}
</script>
 
<body>
    <div id="dialog" title="Record" style="display:none">
    	<table>
			<tr>
				<th>Country</th>
				<th>Region</th>
				<th>Sub Region</th>
				<th>Appellation</th>
				<th>Action</th>
			</tr>
		</table>
    </div>

	<table id="main">
		<tr>
			<th colspan="5"><p>Geography</p></th>
		</tr>
		<tr style="margin-left: 10px; border: 1px solid #32414e;">
			<td colspan="2" width="50%" style="padding: 10px 10px 10px 10px;">
				<button id="btn_add" class="customButton" onClick="addRecord()">Add New Record</button>
			</td>
			<td colspan="3" width="%" style="padding: 10px 10px 10px 10px;">											
				Upload CSV File <br/>
				<form method="post" id="fileinfo" name="fileinfo" onsubmit="return submitForm();">
        		<input type="file" name="file" id="fileToUpload" required onChange="validateInput(this)" />
       			<input type="submit" value="Upload" />
   				</form>
			</td>
		</tr>
		<tr>
				<th>Country</th>
				<th>Region</th>
				<th>Sub Region</th>
				<th>Appellation</th>
				<th>Action</th>
		</tr>
		<tbody id="geography-list">
			<?php echo $geographyListStr?>
		</tbody>
	</table>	
</body>
</html>