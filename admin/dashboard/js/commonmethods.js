upload = function(callback, formData) {
	$.ajax({
		url : "../../../../../api/upload.php",
		type : "POST",
		data : formData,
		processData : false, // tell jQuery not to process the data
		contentType : false
	// tell jQuery not to set contentType
	}).done(function(data) {
		console.log("PHP Output:");
		console.log(data);
		callback(data);
	});
}
    
function validateInput(oInput) {
	var _validFileExtensions = [".csv"];
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
                //"Sorry, " + sFileName + " is invalid, allowed extensions is: " + _validFileExtensions.join(", ")
            	alert("File with extension \".csv\" is only allowed to upload!");
                oInput.value = "";
                return false;
            }
            return true;
        }
    }
	oInput.value = "";
    return false;
}