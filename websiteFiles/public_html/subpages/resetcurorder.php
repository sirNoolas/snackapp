<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");	
?>
<?php
	if (!isset($_SESSION[userid]))
	{
		header('Location: /login/redirectlogin.php');
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
		{
			header('Location: /login/activate.php');
			exit();
		}
	}
	
	# Check for valid token
	$query0 = "SELECT token_id FROM users WHERE user_id='$_SESSION[userid]'";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");
	
	if (mysql_affected_rows() == 1) 
		{	
		$currenttoken = mysql_fetch_array($result, MYSQL_NUM);
		if ($currenttoken[0] != $_SESSION[token_id])
			{
			header('Location: /login/logout.php');
			mysql_close();
			exit();
		}
	}		
?>
<?php
	$query = "SELECT bestelling_id FROM bestellingen WHERE user_id=$_SESSION[userid] AND datum=CURDATE()";
	$result = mysql_query($query) or trigger_error("Error while trying to access database");
	
	$orderid = mysql_fetch_array($result);
	$orderid = (int) $orderid[0];
	
	# Update user money value
	$query = "SELECT product_id FROM bestellingen_producten WHERE bestelling_id=$orderid";
	$result = mysql_query($query) or trigger_error("Error while trying to access database");
	
	while ($row = mysql_fetch_array($result))
		{
		$query1 = "SELECT prijs FROM sub_products WHERE product_id=$row[0]";
		$result1 = mysql_query($query1) or trigger_error("Error while trying to access database");
		
		$moneytoadd = mysql_fetch_array($result1);
		$_SESSION[saldo] += (float) $moneytoadd[0];
	}
	$query = "UPDATE users SET saldo=$_SESSION[saldo] WHERE user_id=$_SESSION[userid]";
	$result = mysql_query($query) or trigger_error("Error while trying to access database");
	
	
	# Start deleting:
	$query = "DELETE FROM bestellingen WHERE user_id=$_SESSION[userid] AND datum=CURDATE()";
	$result = mysql_query($query) or trigger_error("Error while trying to access database");
	
	$query = "DELETE FROM bestellingen_producten WHERE bestelling_id=$orderid";
	$result = mysql_query($query) or trigger_error("Error while trying to access database");
	
	# update second token
	$tokenId = rand(10000, 9999999);
	$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
	$result = mysql_query($query4);
	$_SESSION['token_id'] = $tokenId;
	
	session_regenerate_id();
	
	header("Location: mijnsnackit.php");
	mysql_close();
	exit();
?>
