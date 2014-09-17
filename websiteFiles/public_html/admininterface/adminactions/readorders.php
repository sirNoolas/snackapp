<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	#open database connection
	require_once("../../../include/configdb.php");
?>
<?php
	# Security mesures
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
	
	if ($_SESSION[admin_value] != 1) 
	{
		header('Location: /login/mijnsnackit.php');
		exit();
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
	$query0 = "SELECT page_name FROM pages ORDER BY page_id ASC";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");
	
	$names = array();
	while ($namerow = mysql_fetch_array($result, MYSQL_NUM))
	{
		array_push($names, $namerow[0]);
	}
	mysql_free_result($result);
?>
<!DOCTYPE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="../../cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../../cssstylesheets/admininterface.css">
	</head>
	
	<body>
		<a href="/index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = '../../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../../subpages/orderpage.php?id=0';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../../subpages/orderpage.php?id=1';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../../subpages/orderpage.php?id=2';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../../subpages/orderpage.php?id=3';">
  	         		<?php echo $names[3]; ?>
  	        	 </td>
  	         	<td id="menuitem" onClick="window.location = '../../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
  		<table id="adminmenu">
  			<tr>
  				<td id="adminmenuitem" onclick="window.location = '../adminindex.php';">
  					Admin index
  				</td>
  				<td id="adminmenuitemselected" onclick="window.location = '../actionadmin.php';">
  					Admin acties
  				</td>
  				<td id="adminmenuitem" onclick="window.location = '../useradminindex.php';">
  					User index
  				</td>
  			</tr>
  		</table>
		
		<div id="main">
      	<?php
      		# Get some room between the menu and the table
      		echo "<br>";
      		
      		# Fetch the display type
				if (isset($_GET['display']) && preg_match ('%^(all|user|allsorted|allhistory)?$%', stripslashes(trim($_GET['display']))))
					{
					$display = escape_data($_GET['display']);
				} else {
					$display = NULL;
					echo "Er is een fout in het display type.";
				}
      	
      		switch ($display)
      			{
      			case 'all':
      				#Tell user
      				echo "<h3>Alles ongesorteerd weergeven</h3>";
      				
		   			#init table
						echo (
							"<table id=producttable>
		            		 <tr id=productfirstrow>
						         <td>product omschrijving</td>                  	 	
						         <td>prijs (euro)</td>
						         <td>tijd van bestelling</td>
								 </tr>"
						);
					
						# Get the order id's and the user id's
						$query = "SELECT bestelling_id FROM bestellingen WHERE datum = CURDATE() ";
						$result = mysql_query($query) or trigger_error("Error while trying to access database");
						
						while ($orderrow = mysql_fetch_array($result))
							{
							# Get the order id's and the user id's
							$orderquery = "SELECT product_id, tijd FROM bestellingen_producten WHERE bestelling_id=$orderrow[0]";
							$orderresult = mysql_query($orderquery) or trigger_error("Error while trying to access database");
							
							# print data to screen
							while($orderproductrow = mysql_fetch_array($orderresult))
								{
								# Get product details
								$productquery = "SELECT sub_naam, prijs FROM sub_products WHERE product_id = $orderproductrow[0]";
								$productresult = mysql_query($productquery) or trigger_error("Error while trying to access database" . mysql_error());
								# Check for one product returned
								if (mysql_affected_rows() >= 1) 
									{ 
									$productrow = mysql_fetch_array($productresult); 
									mysql_free_result($productresult);
								}
							
								# Echo the result
								echo ("
									<tr>
										<td id=producttd> $productrow[0] </td>
										<td id=producttd> $productrow[1] </td>
										<td id=producttd> $orderproductrow[1]</td>
									</tr>"
								);
								
								# Add product value to totalvalue
								$totalvalue += (float) $productrow[1];
								
								
							} # END of WHILE
							if (mysql_affected_rows() > 0)
								{
								mysql_free_result($orderresult);
							}
						} # END of WHILE 2
						
						# Print total
						if (mysql_affected_rows() > 0)
							{
							echo ("
								<tr>
									<td id=producttd> <b>TOTAAL bedrag</b> </td>
									<td id=producttd> $totalvalue </td>
								</tr>
								<tr>
									<td id=producttd> <b>TOTAAL aantal producten</b> </td>
									<td id=producttd> $totalvalue </td>
								</tr>
							"); 
						}
						
		         	echo "</table>";
		         	
		         	mysql_free_result($result);
		         	break;
		        	# END of case all
		        	
		        	
		        	case 'user':  	
      				#init table
						echo (
							"<table id=producttable>
		            		 <tr id=productfirstrow>
		            		 	<td>Gebruiker</td>
		               	 	<td>referentie</td>
						         <td>product omschrijving</td>                  	 	
						         <td>prijs</td>
								 </tr>"
						);
					
						# Get the order id's and the user id's
						$query = "SELECT * FROM bestellingen WHERE datum = CURDATE() ";
						$result = mysql_query($query) or trigger_error("Error while trying to access database");
						
						while ($orderrow = mysql_fetch_array($result))
							{
							# Get user details
							$userquery = "SELECT first_name FROM users WHERE user_id=$orderrow[2]";
							$userresult = mysql_query($userquery) or trigger_error("Error while trying to access database");
							# Check for one user returned
							if (mysql_affected_rows() == 1) { $userrow = mysql_fetch_array($userresult); }
							
							# print user details
							echo ("
								<tr>
									<td id=producttd> --$userrow[0] $userrow[1]-- </td>
									<td id=producttd></td>
									<td id=producttd></td>
									<td id=producttd></td>
								</tr>
							");
							
							# Get the order id's and the user id's
							$orderquery = "SELECT product_id FROM bestellingen_producten WHERE bestelling_id=$orderrow[0]";
							$orderresult = mysql_query($orderquery) or trigger_error("Error while trying to access database" . mysql_error());
							
							# print other data to screen
							while($orderproductrow = mysql_fetch_array($orderresult))
								{
								# Get product details
								$productquery = "SELECT sub_naam, prijs FROM sub_products WHERE product_id=$orderproductrow[0]";
								$productresult = mysql_query($productquery) or trigger_error("Error while trying to access database");
								# Check for one product returned
								if (mysql_affected_rows() == 1)
									{
									$productrow = mysql_fetch_array($productresult);
									mysql_free_result($productresult);
								}
							
								# Echo the result
								echo ("
									<tr>
										<td id=producttd></td>
										<td id=producttd> $orderrow[0] </td>
										<td id=producttd> $productrow[0] </td>
										<td id=producttd> $productrow[1] </td>
									</tr>"
									);
								
							} # END of WHILE
							if (mysql_affected_rows() > 0)
								{
								mysql_free_result($orderresult);
							}
						} # END of WHILE 2
						
		         	echo "</table>";
		         	
		         	mysql_free_result($result);
		         	break;
		        		
		        	# END of case user
		        	
		        	
		        	case 'allsorted':
      				#Tell user
      				echo "<h3>Alles gesorteerd weergeven</h3>";
      				
		   			#init table
						echo (
							"<table id=producttable>
		            		 <tr id=productfirstrow>
						        <td>product omschrijving</td>                  	 	
						        <td>prijs (euro)</td>
						        <td>aantal</td>
						        <td>totaal product</td>
							</tr>"
						);
					
						# Get the order id's and the user id's
						$query = "SELECT bestelling_id FROM bestellingen WHERE datum = CURDATE() ";
						$result = mysql_query($query) or trigger_error("Error while trying to access database");
						
						if (mysql_affected_rows() > 0)
							{
							$orderrow = mysql_fetch_array($result);
						
							# Get the order id's and the user id's
							$orderquery = "SELECT product_id, tijd FROM bestellingen_producten WHERE bestelling_id = $orderrow[0] ORDER BY product_id ASC";
							$orderresult = mysql_query($orderquery) or trigger_error("Error while trying to access database");
							
							$first = TRUE;
							$totalvalue = 0;
							# print data to screen
							while($orderproductrow = mysql_fetch_array($orderresult))
								{
								if ($orderproductrow[0] == $lastproductid)
									{
									$curproductamount++;
								} else {
									# End previous row (if not first row)
									if (!$first)
										{
										$curtotalvalue = $curproductamount * (float) $productrow[1];
										echo ("
												<td id=producttd> $curproductamount </td>
												<td id=producttd> $curtotalvalue </td>
											</tr>"
										);
										$totalvalue += $curtotalvalue;
									} else {
										$first = FALSE;
									}
									
									# Get product details
									$productquery = "SELECT sub_naam, prijs FROM sub_products WHERE product_id = $orderproductrow[0]";
									$productresult = mysql_query($productquery) or trigger_error("Error while trying to access database" . mysql_error());
									# Check for one product returned
									if (mysql_affected_rows() == 1)
										{ 
										$productrow = mysql_fetch_array($productresult);
										mysql_free_result($productresult);									
									}
									
									# Reset temporary value's
									$curproductamount = 1;
									
									# Echo the result
									echo ("
										<tr>
											<td id=producttd> $productrow[0] </td>
											<td id=producttd> $productrow[1] </td>
									");
								}# END of main IF
								$lastproductid = $orderproductrow[0];
							}	# END of main WHILE
							
							if (mysql_affected_rows() > 0)
								{ 
								mysql_free_result($orderresult);
							}
								
							# Finish last row
							$curtotalvalue = $curproductamount * (float) $productrow[1];
							
							echo ("
									<td id=producttd> $curproductamount </td>
									<td id=producttd> $curtotalvalue </td>
								</tr>"
							);
							
							$totalvalue += $curtotalvalue;
							
							# Print total value for whole order
							echo ("
								<tr>
									<td id=producttd><b> TOTAAL eindbedrag </b></td>
									<td id=producttd> $totalvalue </td>
								</tr>
								</table>"
							);
						} else {
							echo "</table>";
							echo "Er zijn op dit moment nog geen orders voor vandaag!";
						}
						
						break;
						# END of case allsorted
						
						
						case 'allhistory':
      				#Tell user
      				echo "<h3>Alles ongesorteerd weergeven</h3>";
      				
		   			#init table
						echo (
							"<table id=producttable>
		            		 <tr id=productfirstrow>
						         <td>product omschrijving</td>                  	 	
						         <td>prijs (euro)</td>
						         <td>tijd van bestelling</td>
								 </tr>"
						);
					
						# Get the order id's and the user id's
						$query = "SELECT bestelling_id, datum FROM bestellingen";
						$result = mysql_query($query) or trigger_error("Error while trying to access database");
						
						while ($orderrow = mysql_fetch_array($result))
							{
							# Get the order id's and the user id's
							$orderquery = "SELECT product_id, tijd FROM bestellingen_producten WHERE bestelling_id=$orderrow[0]";
							$orderresult = mysql_query($orderquery) or trigger_error("Error while trying to access database");
							
							# print data to screen
							while($orderproductrow = mysql_fetch_array($orderresult))
								{
								# Get product details
								$productquery = "SELECT sub_naam, prijs FROM sub_products WHERE product_id = $orderproductrow[0]";
								$productresult = mysql_query($productquery) or trigger_error("Error while trying to access database" . mysql_error());
								# Check for one product returned
								if (mysql_affected_rows() >= 1) 
									{ 
									$productrow = mysql_fetch_array($productresult); 
									mysql_free_result($productresult);
								}
							
								# Echo the result
								echo ("
									<tr>
										<td id=producttd> $productrow[0] </td>
										<td id=producttd> $productrow[1] </td>
										<td id=producttd> $orderrow[1] $orderproductrow[1]</td>
									</tr>"
								);
								
								# Add product value to totalvalue
								$totalvalue += (float) $productrow[1];
								
								
							} # END of WHILE
							if (mysql_affected_rows() > 0)
								{
								mysql_free_result($orderresult);
							}
						} # END of WHILE 2
						
						# Print total
						if (mysql_affected_rows() > 0)
							{
							echo ("
								<tr>
									<td id=producttd> <b>TOTAAL bedrag</b> </td>
									<td id=producttd> $totalvalue </td>
								</tr>
								<tr>
									<td id=producttd> <b>TOTAAL aantal producten</b> </td>
									<td id=producttd> $totalvalue </td>
								</tr>
							"); 
						}
						
		         	echo "</table>";
		         	
		         	mysql_free_result($result);
		         	break;
		        	# END of case allhistory
						
					default:
		        		echo " geen match";
		        		break;
				}	# END of SWITCH
				
				# update second token
				$tokenId = rand(10000, 9999999);
				$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query4);
				$_SESSION['token_id'] = $tokenId;
			?>
		</div>
		<div id="footer">
			<a href="../../disclaimer.php">Disclaimer</a> ----- <a href="../../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Thijs Werkman, Xantes ICT; 2014
		</div>
		
	</body>
</html>
