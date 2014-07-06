<?php
	header("Access-Control-Allow-Origin: http://itspixeled.nl");
	session_start();
	
	$productID = $_POST['productID'];	
	$productName = $_POST['productName'];	
	$productPrice = $_POST['productPrice'];	
	 
	array_push($_SESSION['productID'], $_POST['productID']);
	array_push($_SESSION['productName'], $_POST['productName']); 
	array_push($_SESSION['productPrice'], $_POST['productPrice']); 
?>