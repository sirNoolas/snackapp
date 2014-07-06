<?php
	header("Access-Control-Allow-Origin: http://itspixeled.nl");
	session_start();
	
	$_SESSION['productID'] = array();
	$_SESSION['productName'] = array();
	$_SESSION['productPrice'] = array();
?>