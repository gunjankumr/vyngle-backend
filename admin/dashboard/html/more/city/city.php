<html>
<head>
    <title>Image Upload Form</title>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="../../../js/commonmethods.js"></script>

    <script type="text/javascript">
//     doSomething = function(callback, salutation) {
//         // Call our callback, but using our own instance as the context
//         callback.call(this, salutation);
//     }

    function uploadResponse(responseStatus) {
        alert(responseStatus);
    }

        function submitForm() {
            console.log("submit event");
            var formData = new FormData(document.getElementById("fileinfo"));
            formData.append("label", "city");
             upload(uploadResponse,formData);
             return false;
        }        
    </script>
</head>

<body>
    <form method="post" id="fileinfo" name="fileinfo" onsubmit="return submitForm();">
        <label>Select a file:</label><br>
        <input type="file" name="file" required />
        <input type="submit" value="Upload" />
    </form>
</body>
</html>