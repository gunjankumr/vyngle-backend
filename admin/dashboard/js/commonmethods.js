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

function foo(salutation) {
    alert(salutation + " " + this.name + " test callback");
}