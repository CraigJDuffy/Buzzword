<?php
if (empty($_POST['MenuID'])) {
	http_response_code(400);
	die();
} else {
	$dsn = 'mysql:dbname=til1;host=mysql-server-1.macs.hw.ac.uk;charset=utf8';
	$db = new PDO($dsn, 'til1', 'abctil1354');
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$statement = $db->prepare('Select * from Menu where MenuID=:ID');
	$statement->bindParam(':ID', $_POST['MenuID']);
	if ($statement->execute()){
		if ($statement->rowCount() != 1) {
			http_response_code(400);
			die();
		} else {
			http_response_code(200);
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			if ($result==false){
				http_response_code(500);
				die();
			}
			echo $result["JSON"];
		}
	} else {
		http_response_code(500);
		die();
	}
}
?>