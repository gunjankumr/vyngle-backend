<?php
include_once '../../../../../api/more/country/country_server.php';

$objCountry = new Country();
$countryListStr = $objCountry->getCountryList();
?>
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

 function deleteRecord(country) {
	 var r = confirm("Are you sure you want to delete the record?");
	 if (r == true) {
		 var request = $.ajax({
		   url: "../../../../../api/admin_api.php?function=country&action=deleteCountryMasterRecord",
		   type: "POST",
		   data: {countryName : country},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#country-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });    
	 }
 }

 function addRecord() {
	 var country = $('#text_input_new_entry').val();
	 if (country.length > 0) {
		$('#text_input_new_entry').val(""); 
	 	var request = $.ajax({
		   url: "../../../../../api/admin_api.php?function=country&action=addCountryMasterRecord",
		   type: "POST",
		   data: {countryName : country},
		   dataType: "html"
		 });

		 request.done(function(msg) {
			if (msg.length > 0) {
				$("#country-list").html(msg);
			}
		 });

		 request.fail(function(jqXHR, textStatus) {
		   alert( "Request failed: " + textStatus );
		 });
	 } else {
		alert("Please input country!");
	 }
 }

 function uploadResponse(responseStatus) {
	 if (responseStatus.length > 0) {
		$("#country-list").html(responseStatus);
		$("#fileToUpload").val('');
	 }
 }

 function submitForm() {
	 if (validateInput(document.getElementById("fileToUpload")) == true) {
	     var formData = new FormData(document.getElementById("fileinfo"));
	     formData.append("pagename", "country");
	     upload(uploadResponse, formData);
	     return false;
	 }
}
</script>
 
<body>
	<table id="main">
		<tr>
			<th colspan="2"><p>Country</p></th>
		</tr>
		<tr>
			<td width="60%" id="country-list">
				<!-- Displays the existing data -->
				<?php echo $countryListStr;?>
			</td>
			<td width="%" valign="top">
				<!-- Add new data and also shows option to upload csv -->
				<table>
					<tr style="margin-left: 10px;">
						<td width="100%" style="padding: 10px 10px 10px 10px;">
							Enter country name:<br/>
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
        						 <input type="file" name="file" id="fileToUpload" required onChange="validateInput(this)" />
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