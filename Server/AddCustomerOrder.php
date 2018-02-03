<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = 'mysql:dbname=til1;host=mysql-server-1.macs.hw.ac.uk;charset=utf8';
$db = new PDO($dsn, 'til1', 'abctil1354');
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->beginTransaction();

$jason = json_decode(utf8_encode(file_get_contents('php://input')), true, 512, JSON_BIGINT_AS_STRING);


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

if (!array_key_exists($jason['Items']) || count($jason['Items']) == 0){
	$db->rollBack();
	http_response_code(400);
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