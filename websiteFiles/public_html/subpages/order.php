<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");
?>
<?php
	# make sure the user is logged in
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
	# Check whether the form has been submitted
	if (isset($_GET['hash']) && isset($_SESSION['userid']) && isset($_SESSION['productID']))
		{
		#get orderarray
		$orderarray = $_SESSION['productID'];
		$orderhash = $_GET['orderhash'];
		
		# Check for valid call
		$temporderarray = implode(',', $orderarray);		
		for ($i = 0; $i < sizeof($orderarray); $i++)
			{
			if ($orderhash == md5($temporderarray))
				{
				$valid = TRUE;
			} else {
				$valid = FALSE;
			}
		}
			
		# Get current money from the user
		$getmoneyquery = "SELECT saldo FROM users WHERE user_id=$_SESSION[userid]";
		$getmoneyresult = mysql_query($getmoneyquery) or trigger_error("Error while trying to access database");
		
		if (mysql_affected_rows() == 1)
			{
			$money = mysql_fetch_array($getmoneyresult);
			$money = (float) $money[0];
			mysql_free_result($getmoneyresult);
			
			# Get the total orderprice and subtract that value from money afterwards
			for ($i=0; $i < sizeof($orderarray); $i++)
				{
				# Get the price of the selected product
				$getpricequery = "SELECT prijs FROM sub_products WHERE product_id=$orderarray[$i]";
				$getpriceresult = mysql_query($getpricequery) or trigger_error("Error while trying to access database");
				
				$price = mysql_fetch_array($getpriceresult);
				$price = (float) $price[0];
				
				$totalorderprice += $price;
			} # end of for
			mysql_free_result($getpriceresult);
			$money -= $totalorderprice;
			
			# Check for a valid value for money
			if ($money <= 0)
				{
				$error .= "Uw saldo is niet toereikend voor deze bestelling!<br>";
				$error .= "U heeft een bestelling gedaan van: $totalorderprice euro<br>";
				$error .= "Uw huidige saldo is: " . ($money + $totalorderprice) . " euro, en uw saldo mag niet beneden of op 0 komen!";
			} else {
				# Write to session
				$_SESSION[saldo] = $money;				
			
				# UPDATE user money
				$updatemoneyquery = "UPDATE users SET saldo=$money WHERE user_id=$_SESSION[userid]";
				$updatemoneyresult = mysql_query($updatemoneyquery) or trigger_error("Error while trying to access database");
				# You don't have to Check for one row affected: this is checked in the getmoneyquery.
			
				# Post order for real (as you can see, after the decreasing of the users money)
				# Check whether this person has ordered before today
				$query = "SELECT bestelling_id FROM bestellingen WHERE datum=CURDATE() AND user_id=$_SESSION[userid]";
				$result = mysql_query($query) or trigger_error("Error while trying to access database");
		
				if (mysql_affected_rows() == 0)
					{
					$mainorderquery = "INSERT INTO bestellingen (bestelling_id, datum, user_id) VALUES (NULL, CURDATE(), $_SESSION[userid])";
					$mainorderresult = mysql_query($mainorderquery) or trigger_error("Error while trying to access database" . mysql_error());
			
					#requery for order_id
					$result = mysql_query($query) or trigger_error("Error while trying to access database");
				}
				$orderid = mysql_fetch_array($result, MYSQL_NUM);
				mysql_free_result($result);
		
				# Post the order to the DB
				for ($i=0; $i < sizeof($orderarray); $i++)
					{
					$insertquery = "INSERT INTO bestellingen_producten (bestelling_product_id, bestelling_id, product_id, tijd) VALUES (NULL, $orderid[0], $orderarray[$i], CURTIME());";
					$insertresult = mysql_query($insertquery) or trigger_error("Error while trying to access database" . mysql_error());
				} # end of for
				$error .= "Uw order is geslaagd!";
				
			} # END of get current money from user
			
		} else {
			$error .= "uw saldo kon niet opgehaald worden. Hierdoor is de bestelling onsuccesvol<br>";
		} # End of get user data IF
		
	} else {
		# The call was invalid! redirect
		$error .= "<br> Er was een foutieve vraag in order.php";
		header("LOCATION: ../index.php?x=$error");
	} # END of main IF
	
	# update second token
	$tokenId = rand(10000, 9999999);
	$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
	$result = mysql_query($query4);
	$_SESSION['token_id'] = $tokenId;
	
	session_regenerate_id();
?>
<?php
	$query0 = "SELECT page_name FROM pages ORDER BY page_id ASC";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");
	
	$names = array();
	while ($namerow = mysql_fetch_array($result, MYSQL_NUM))
	{
		array_push($names, $namerow[0]);
	}
	mysql_free_result($result);
?>
<!-- END of processing the data -->
<!DOCTYPE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="../cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<a href="/index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitemselected" onClick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/orderpage.php?id=0';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/orderpage.php?id=1';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/orderpage.php?id=2';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/orderpage.php?id=3';">
  	         		<?php echo $names[3]; ?>
  	        	 	</td>
  	         	<td id="menuitem" onClick="window.location = '../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
  		<div id='main'>
  			<?php echo "<br><br><br><h3>$error</h3><br><br>Let op! Wanneer u deze pagina herlaad, word uw order ook opnieuw verzonden! <br><br>"; ?>
  		</div>
		<div id="footer">
			<a href="../disclaimer.php">Disclaimer</a> ----- <a href="../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Thijs Werkman, Xantes ICT; 2014
		</div>
  	</body>
</html>
