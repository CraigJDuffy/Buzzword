<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ((empty($_POST['OrderName'])) || (empty ($_POST['OrderNumber']))) {
	http_response_code(400);
	die();
} else {
	$dsn = 'mysql:dbname=til1;host=mysql-server-1.macs.hw.ac.uk;charset=utf8';
	$db = new PDO($dsn, 'til1', 'abctil1354');
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$statement = $db->prepare('Select Orders.ETA as OrderETA, Amount, Details, GroupNumber, OrderItems.ETA as ItemETA, LongName, MenuItem.ItemID, MenuItem.SectionID, Description, Price from MenuItem, Orders, OrderItems where Orders.OrderNumber = ? and Orders.OrderName = ? and OrderItems.OrderNumber = Orders.OrderNumber and OrderItems.OrderName = Orders.OrderName and OrderItems.ItemID = MenuItem.ItemID');
	$statement->bindParam(1, $_POST['OrderNumber']);
	$statement->bindParam(2, $_POST['OrderName']);
	if ($statement->execute()){
		http_response_code(200);
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result===false){
			http_response_code(500);
			die();
		}
		
		
		echo '{"OrderNumber" : '.$_POST['OrderNumber'].',
				"OrderName" : "'.$_POST['OrderName'].'",
				"ETA" : ';
		if ($result['OrderETA'] ==""){
				echo 'null';
		} else {
				echo '"'.$result['OrderETA'].'"';
			}
				echo',
				"Items" : [';
		
		echo '{
				"Amount" : '.$result['Amount'].',
				"Request" : "'.$result['Details'].'",
				"ETA" : ';
		if ($result['ItemETA'] ==""){
				echo 'null';
		} else {
				echo '"'.$result['ItemETA'].'"';
			}
				echo',
				"GroupNumber" : '.$result['GroupNumber'].',
				"MenuItem" : {
					"ItemID" : '.$result['ItemID'].',
					"ParentSectionID" : '.$result['SectionID'].',
					"DisplayName" : "'.$result['LongName'].'",
					"Price" : '.$result['Price'].',
					"Description" : "'.$result['Description'].'"
					}
				}
				';
		
		while ($result = $statement->fetch(PDO::FETCH_ASSOC)){
			echo ',';
			echo '{
				"Amount" : '.$result['Amount'].',
				"Request" : "'.$result['Details'].'",
				"ETA" : "'.$result['ItemETA'].'",
				"GroupNumber" : '.$result['GroupNumber'].',
				"MenuItem" : {
					"ItemID" : '.$result['ItemID'].',
					"ParentSectionID" : '.$result['SectionID'].',
					"DisplayName" : "'.$result['LongName'].'",
					"Price" : '.$result['Price'].',
					"Description" : "'.$result['Description'].'"
					}
				}
				';
		}
		
		echo ']}';
	} else {
		http_response_code(500);
		die();
	}
}
?>