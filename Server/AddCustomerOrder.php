<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = 'mysql:dbname=til1;host=mysql-server-1.macs.hw.ac.uk;charset=utf8';
$db = new PDO($dsn, 'til1', 'abctil1354');
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->beginTransaction();

//file_get_contents('php://input')

$jason = json_decode(utf8_encode('{
"OrderNumber" : 2,
"OrderName" : "Test",
"ETA" : null,
"Items" : [
	{
	"Amount" : 5,
	"Request" : "",
	"MenuItem" : {
		"ItemID" : 2,
		"ParentSectionID" : 1,
		"DisplayName" : "Prawn Cocktail",
		"Description" : "King prawns served with 5-spice cocktail sauce",
		"Price" : 3.20
		},
	"ETA" : null,
	"GroupNumber" : 1
	},
	{
	"Amount" : 6,
	"Request" : "No salad dressing",
	"MenuItem" : {
		"ItemID" : 42,
		"ParentSectionID" : 31,
		"DisplayName" : "Balmoral",
		"Description" : "Venison steak with Bleu cheese and crispy shallots. Served with side of home-style chips, salad (with dressing), and cranberry sauce",
		"Price" : 14.75
		},
	"ETA" : null,
	"GroupNumber" : 2
	},
	{
	"Amount" : 7,
	"Request" : "",
	"MenuItem" : {
		"ItemID" : 48,
		"ParentSectionID" : 33,
		"DisplayName" : "Beef Wellington",
		"Description" : "Tenderloin beef coated in pâté de foie gras and duxelles, baked in puff pastry. Accompanied by rich gravy and roast potatoes",
		"Price" : 13.10
		},
	"ETA" : null,
	"GroupNumber" : 2
	},
	{
	"Amount" : 8,
	"Request" : "No ice",
	"MenuItem" : {
		"ItemID" : 20,
		"ParentSectionID" : 2,
		"DisplayName" : "Bottomless Glass",
		"Description" : "Unlimited refills of Pepsi, 7 Up, Schweppes, and Irn Bru",
		"Price" : 2.00
		},
	"ETA" : null,
	"GroupNumber" : 0
	},
	{
	"Amount" : 9,
	"Request" : "",
	"MenuItem" : {
		"ItemID" : 20,
		"ParentSectionID" : 2,
		"DisplayName" : "Bottomless Glass",
		"Description" : "Unlimited refills of Pepsi, 7 Up, Schweppes, and Irn Bru",
		"Price" : 2.00
		},
	"ETA" : null,
	"GroupNumber" : 0
	}
]

}'), true, 512, JSON_BIGINT_AS_STRING);

//echo file_get_contents('php://input');

if (is_null($jason) || (json_last_error() !== JSON_ERROR_NONE)){
	http_response_code(400);
	die();
}


try {
	$statement = $db->prepare('Insert into Orders (OrderNumber, OrderName) VALUES (:ONum, :ONam)');
	$statement->bindValue(':ONam', $jason['OrderName']);
	$statement->bindValue(':ONum', $jason['OrderNumber']);
	$statement->execute();
} catch (PDOException $e){
	echo $e->getCode(), PHP_EOL;
	echo $e->getMessage();
	http_response_code(400);
	die();
}

if($statement->rowCount() != 1){
	$db->rollBack();
	http_response_code(500);
	die();
}

foreach($jason['Items'] as $orderItem){
	try {
		$statement = $db->prepare('Insert into OrderItems VALUES (:ONum, :ONam, :ID, :Amount, :Detail, :GNum, :ETA)');
		$statement->bindValue(':ONam', $jason['OrderName']);
		$statement->bindValue(':ONum', $jason['OrderNumber']);
		$statement->bindValue(':ID', $orderItem['MenuItem']['ItemID']);
		$statement->bindValue(':Amount', $orderItem['Amount']);
		$statement->bindValue(':Detail', $orderItem['Request']);
		$statement->bindValue(':GNum', $orderItem['GroupNumber']);
		$statement->bindValue(':ETA', $orderItem['ETA']);
		
		$statement->execute();
	} catch (PDOException $e){
		echo $e->getCode(), PHP_EOL;
		echo $e->getMessage();
		http_response_code(400);
		die();
	}
	
	if($statement->rowCount() != 1){
		$db->rollBack();
		http_response_code(500);
		die();
	}
}


$db->commit();


?>