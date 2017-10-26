<?php 
include_once '../../../../../api/admin_api.php';

$obj = new AdminApi();
$bottlesListStr = $obj->getBottlesPerCaseList();
?>
</html>
<style>
table {
	border-collapse: collapse;
}

#main {
	border: 1px solid #32414e;
}

/*   html, body {   */
/*       width: 95% !important;   */
/*       height: 95% !important;  */
/*       padding:0;  */
/*       margin:0;  */
/*   }   */

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
<script>

 function deleteRecord(recId) {
	 var r = confirm("Are you sure you want to delete the record?");
	 if (r == true) {
		 var request = $.ajax({
		   url: "../../../../../api/admin_api.php?f=deleteBottlesPerCaseMasterRecord",
		   type: "POST",
		   data: {id : recId},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#bottles-per-case-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });    
	 }
 }

 function addRecord() {
	 var bottlesPerCase = $('#text_input_new_entry').val();
	 if (!isNaN(bottlesPerCase) && bottlesPerCase > 0) {
		$('#text_input_new_entry').val(""); 
	 	var request = $.ajax({
		   url: "../../../../../api/admin_api.php?f=addBottlesPerCaseMasterRecord",
		   type: "POST",
		   data: {id : bottlesPerCase},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#bottles-per-case-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });
	 } else {
		alert("Please input valid number of bottles!");
	 }
 }

 var _validFileExtensions = [".csv"];    
 function validateInput(oInput) {
     if (oInput.type == "file") {
         var sFileName = oInput.value;
          if (sFileName.length > 0) {
             var blnValid = false;
             for (var j = 0; j < _validFileExtensions.length; j++) {
                 var sCurExtension = _validFileExtensions[j];
                 if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                     blnValid = true;
                     break;
                 }
             }
              
             if (!blnValid) {
                 alert("Sorry, " + sFileName + " is invalid, allowed extensions is: " + _validFileExtensions.join(", "));
                 oInput.value = "";
                 return false;
             }
         }
     }
     return true;
 }


 function uploadResponse(responseStatus) {
     alert(responseStatus);
 }

     function submitForm() {
         console.log("submit event");
         var formData = new FormData(document.getElementById("fileinfo"));
         formData.append("pagename", "bottlepercase");
          upload(uploadResponse,formData);
          return false;
     }        


 
</script>
<head>
    <script src="../../../js/commonmethods.js"></script>
</head>
 
<body>
	<table id="main">
		<tr>
			<th colspan="2"><p>Bottles per case</p></th>
		</tr>
		<tr>
			<td width="60%" id="bottles-per-case-list">
				<!-- Displays the existing data -->
				<?php echo $bottlesListStr;?>
			</td>
			<td width="%" valign="top">
				<!-- Add new data and also shows option to upload csv -->
				<table>
					<tr style="margin-left: 10px;">
						<td width="100%" style="padding: 10px 10px 10px 10px;">
							Enter number of bottles per case:<br/>
							<input type="text" id="text_input_new_entry"/>
							<button id="btn_add" onClick="addRecord()">Add</button>
						</td>
					</tr>
					<tr>
					<th style="height: 30px;">Or</th>
					</tr>
					<tr style="margin-left: 10px;">
						<td width="100%" style="padding: 10px 10px 10px 10px;">											
							Upload CSV File <br/>
							<form method="post" id="fileinfo" name="fileinfo" onsubmit="return submitForm();">
        						 <input type="file" name="file" required />
       							 <input type="submit" value="Upload" />
   							</form>
							<br/>							
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	

</body>
</html>