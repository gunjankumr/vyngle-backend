<?php
include_once '../../../../../api/more/legalinformation/legalinformation_server.php';

$objLegalInformation = new LegalInformation();
$legalInformationListStr = $objLegalInformation->getLegalInformationList();
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
	$('#id').val("");
	$('#text').val("");
	$('#last_updated').val("");
	
	var theDialog = $("#dialog").dialog(opt);
	theDialog.dialog("open");
	if(currentRecord.length > 0) {
		var res = currentRecord.split("#");		
		$('#id').val(res[0]);
		$('#text').val(res[1]);
		$('#last_updated').val(res[2]);
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
	var id = $('#id').val();
	var text = $('#text').val();
	var last_updated = $('#last_updated').val();
	var newRecord = id + "#" + text + "#" + last_updated;

	if (text == null || country == "") {
		alert("Please enter text");
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
			url: "../../../../../api/admin_api.php?function=legalinformation&action=editLegalInformationMasterRecord",
		   	type: "POST",
		   	data: {record : allRecord},
		   	dataType: "html"
		});

		request.done(function(msg) {
			closeModalDialog();
			if (msg.length > 0) {
				$("#legalinformation-list").html(msg);
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
		   url: "../../../../../api/admin_api.php?function=legalinformation&action=deleteLegalInformationMasterRecord",
		   type: "POST",
		   data: {record : record},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#legalinformation-list").html(msg);
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
		   url: "../../../../../api/admin_api.php?function=legalinformation&action=addLegalInformationMasterRecord",
		   type: "POST",
		   data: {record : newRecord},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			closeModalDialog();
			if (msg.length > 0) {
				$("#legalinformation-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		 	alert( "Request failed: " + textStatus );
		 });    
	 }
 }
 
 function uploadResponse(responseStatus) {
	 if (responseStatus.length > 0) {
		$("#legalinformation-list").html(responseStatus);
		$("#fileToUpload").val('');
	 }
 }

 function submitForm() {
	 if (validateInput(document.getElementById("fileToUpload")) == true) {
	     var formData = new FormData(document.getElementById("fileinfo"));
	     formData.append("pagename", "legalinformation");
	     upload(uploadResponse, formData);
	     return false;
	 }
}
</script>
 
<body>
    <div id="dialog" title="Record" style="display:none">
  		<div class="form-group">
    		<label>Id:</label>&nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="text" class="form-control" id="id">
  		</div>
  		<div class="form-group">
   			<label>Text:</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="text" class="form-control" id="text">
  		</div>
  		<div class="form-group">
    		<label>Last Updated:</label>
    		<input type="text" class="form-control" id="last_updated">
  		</div>
  		<br>
  		<button class="customButton" id="dialogSaveButton" onClick="submitClicked();">Submit</button>
  		<button class="customButton" id="dialogCancelButton" onClick="closeModalDialog();">Cancel</button>   
    </div>

	<table id="main">
		<tr>
			<th colspan="5"><p>Legal Information</p></th>
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
				<th>Id</th>
				<th>Text</th>
				<th>Last Updated</th>
		</tr>
		<tbody id="legalinformation-list">
			<?php echo $legalInformationListStr;?>
		</tbody>
	</table>	
</body>
</html>