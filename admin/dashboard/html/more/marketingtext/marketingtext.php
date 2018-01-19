<?php
include_once '../../../../../api/more/marketingtext/marketingtext_server.php';

$objGeography = new Geography();
$geographyListStr = $objGeography->getGeographyList();
?>
<head>
<link href="../../../css/jquery-ui-1.10.4.min.css" rel="stylesheet">    
<script src="../../../js/jquery-1.8.3.min.js"></script>
<script src="../../../js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="../../../js/commonmethods.js"></script>
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
<script>
var currentRecord = "";
var opt = {
        autoOpen: false,
        modal: true,
        width: 400,
        height:300,
        position: 'top'
};

function display() {
	$('#country').val("");
	$('#region').val("");
	$('#subregion').val("");
	$('#appellation').val("");
	
	var theDialog = $("#dialog").dialog(opt);
	theDialog.dialog("open");
	if(currentRecord.length > 0) {
		var res = currentRecord.split("#");		
		$('#country').val(res[0]);
		$('#region').val(res[1]);
		$('#subregion').val(res[2]);
		$('#appellation').val(res[3]);
	}
	return false;
}

function closeModalDialog() {
	currentRecord = "";
	var theDialog = $("#dialog").dialog(opt);
	theDialog.dialog("close");
	return true;
}

function submitClicked() {
	var country = $('#country').val();
	var region = $('#region').val();
	var subregion = $('#subregion').val();
	var appellation = $('#appellation').val();
	var newRecord = country + "#" + region + "#" + subregion + "#" + appellation;

	if (country == null || country == "") {
		alert("Please enter country");
		return false;
    } else if(currentRecord.length > 0) { // Update data using Edit button
		editRecordSubmit(currentRecord, newRecord);
	} else { // Add new record using Add Record
		addRecordSubmit(newRecord);
	}
}

function editRecord(record) {
	currentRecord = record;
	display();
	return;
}

function editRecordSubmit(currentRecord, newRecord) {
	var allRecord = currentRecord + "@~@" + newRecord;
	
	var r = confirm("Are you sure you want to save the record?");
	if (r == true) {
		var request = $.ajax({
			url: "../../../../../api/admin_api.php?function=geography&action=editGeographyMasterRecord",
		   	type: "POST",
		   	data: {record : allRecord},
		   	dataType: "html"
		});

		request.done(function(msg) {
			closeModalDialog();
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
		 	alert("Request failed: " + textStatus);
		 });    
	 }
 }

 function addRecord() {
	 display();
 }

 function addRecordSubmit(newRecord) {
	 var r = confirm("Are you sure you want to add the record?");
	 if (r == true) {
		 var request = $.ajax({
		   url: "../../../../../api/admin_api.php?function=geography&action=addGeographyMasterRecord",
		   type: "POST",
		   data: {record : newRecord},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			closeModalDialog();
			if (msg.length > 0) {
				$("#geography-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		 	alert( "Request failed: " + textStatus );
		 });    
	 }
 }
 
 function uploadResponse(responseStatus) {
	 if (responseStatus.length > 0) {
		$("#geography-list").html(responseStatus);
		$("#fileToUpload").val('');
	 }
 }

 function submitForm() {
	 if (validateInput(document.getElementById("fileToUpload")) == true) {
	     var formData = new FormData(document.getElementById("fileinfo"));
	     formData.append("pagename", "geography");
	     upload(uploadResponse, formData);
	     return false;
	 }
}
</script>
 
<body>
    <div id="dialog" title="Record" style="display:none">
  		<div class="form-group">
    		<label>Country:</label>&nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="text" class="form-control" id="country">
  		</div>
  		<div class="form-group">
   			<label>Region:</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="text" class="form-control" id="region">
  		</div>
  		<div class="form-group">
    		<label>Sub Region:</label>
    		<input type="text" class="form-control" id="subregion">
  		</div>
  		<div class="form-group">
   			<label>Appellation:</label>
    		<input type="text" class="form-control" id="appellation">
  		</div>
  		<br>
  		<button class="customButton" id="dialogSaveButton" onClick="submitClicked();">Submit</button>
  		<button class="customButton" id="dialogCancelButton" onClick="closeModalDialog();">Cancel</button>   
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
			<?php echo $geographyListStr;?>
		</tbody>
	</table>	
</body>
</html>