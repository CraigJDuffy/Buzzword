<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
</body>
<script>
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200){
				console.log(this.responseText);
	} else if (this.readyState==4 && (this.status==500||this.status==400)){
	}
	};
		xhttp.open("POST", "GetCustomerOrder.php" , true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("OrderName=Pond&OrderNumber=12");
</script>