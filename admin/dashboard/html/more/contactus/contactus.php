<?php
include_once '../../../../../api/more/contactus/contactus_server.php';

$objContactUs = new ContactUs();
$contactUsListStr = $objContactUs->getContactUsList();
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
 
<body>
	<table id="main">
		<tr>
			<th ><p>Contact Us</p></th>
		</tr>
		<tr>
			<td id="contactus-list">
				<!-- Displays the existing data -->
				<?php echo $contactUsListStr;?>
			</td>
		</tr>
	</table>
</body>
</html>