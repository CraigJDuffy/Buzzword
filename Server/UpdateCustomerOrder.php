<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$jason = json_decode(utf8_encode(file_get_contents('php://input')), true, 512, JSON_BIGINT_AS_STRING);

if (is_null($jason) || (json_last_error() !== JSON_ERROR_NONE) || !array_key_exists('Items', $jason) || count($jason['Items']) == 0){
	http_response_code(400);
	die();
}

$dsn = 'mysql:dbname=til1;host=mysql-server-1.macs.hw.ac.uk;charset=utf8';
$db = new PDO($dsn, 'til1', 'abctil1354');
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->beginTransaction();

$estimateChanged = 0;
$orderChanged = false;

foreach($jason['Items'] as $orderItem){

	if (!array_key_exists('Modified', $orderItem)){
		echo "Missing modification flag";
		http_response_code(400);
		die();
	}
	
	switch($orderItem['Modified']){
		case 0:
			insertItem($db, $jason, $orderItem);
			$orderChanged = true;
			break;
		case 2:
			updateETA($db, $jason, $orderItem);
			$estimateChanged++;
			break;
		case 1:
			removeItem($db, $jason, $orderItem);
			$orderChanged = true;
			break;
		case 3:
			updateDetails($db, $jason, $orderItem);
			$orderChanged = true;
			break;
	}

}

if ($orderChanged){
	$statement = $db->prepare('Update Orders set OrderChanged = DEFAULT where OrderName = :ONam and OrderNumber = :ONum');
	$statement->bindValue(':ONam', $jason['OrderName']);
	$statement->bindValue(':ONum', $jason['OrderNumber']);
	$statement->execute();
}

if ($estimateChanged){
	$statement = $db->prepare('Update Orders set EstimateChanged = CURTIME() where OrderName = :ONam and OrderNumber = :ONum');
	$statement->bindValue(':ONam', $jason['OrderName']);
	$statement->bindValue(':ONum', $jason['OrderNumber']);
	$statement->execute();

}

$db->commit();

function insertItem ($db, $jason, $item){
	try {
		$statement = $db->prepare('Insert into OrderItems VALUES (:ONum, :ONam, :ID, :Amount, :Detail, :GNum, :ETA)');
		$statement->bindValue(':ONam', $jason['OrderName']);
		$statement->bindValue(':ONum', $jason['OrderNumber']);
		$statement->bindValue(':ID', $item['MenuItem']['ItemID']);
		$statement->bindValue(':Amount', $item['Amount']);
		$statement->bindValue(':Detail', $item['Request']);
		$statement->bindValue(':GNum', $item['GroupNumber']);
		$statement->bindValue(':ETA', $item['ETA']);
		
		$statement->execute();
	} catch (PDOException $e){
		echo $e->getCode(), PHP_EOL;
		echo $e->getMessage();
		http_response_code(400);
		die();
	}
}

function removeItem($db, $jason, $item){
	try {
		$statement = $db->prepare('Delete from OrderItems where OrderNumber = :ONum and OrderName = :ONam and ItemID = :ID and Details = :Request');
		$statement->bindValue(':ONam', $jason['OrderName']);
		$statement->bindValue(':ONum', $jason['OrderNumber']);
		$statement->bindValue(':ID', $item['MenuItem']['ItemID']);
		$statement->bindValue(':Request', $item['Request']);
		$statement->execute();
		
	} catch (PDOException $e){
		echo $e->getCode(), PHP_EOL;
		echo $e->getMessage();
		http_response_code(400);
		die();
	}
}

function updateETA($db, $jason, $item){
	try {
		$statement = $db->prepare('Update OrderItems set ETA = :ETA where OrderNumber = :ONum and OrderName = :ONam and ItemID = :ID and Details = :Request');
		$statement->bindValue(':ONam', $jason['OrderName']);
		$statement->bindValue(':ONum', $jason['OrderNumber']);
		$statement->bindValue(':ID', $item['MenuItem']['ItemID']);
		$statement->bindValue(':Request', $item['Request']);
		$statement->bindValue(':ETA', $item['ETA']);
		$statement->execute();
		
	} catch (PDOException $e){
		echo $e->getCode(), PHP_EOL;
		echo $e->getMessage();
		http_response_code(400);
		die();
	}
}

function updateDetails($db, $jason, $item){
	try {
		$statement = $db->prepare('Update OrderItems set Amount = :Amount, GroupNumber = :GNum where OrderNumber = :ONum and OrderName = :ONam and ItemID = :ID and Details = :Request');
		$statement->bindValue(':ONam', $jason['OrderName']);
		$statement->bindValue(':ONum', $jason['OrderNumber']);
		$statement->bindValue(':ID', $item['MenuItem']['ItemID']);
		$statement->bindValue(':Request', $item['Request']);
		$statement->bindValue(':Amount', $item['Amount']);
		$statement->bindValue(':GNum', $item['GroupNumber']);
		$statement->execute();
		
	} catch (PDOException $e){
		echo $e->getCode(), PHP_EOL;
		echo $e->getMessage();
		http_response_code(400);
		die();
	}
}




?>