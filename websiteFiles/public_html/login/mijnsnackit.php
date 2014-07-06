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
	
	if ($_SESSION[admin_value] == 1) 
	{
		header('Location: /admininterface/adminindex.php');
		exit();
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
		
		<div id="main">
			<div id="bodyleftdiv"><br /><b>Welkom, <?php echo $_SESSION[first_name]; ?></b>
			<br />
			<?php
				echo "Uw saldo is: ";
				echo $_SESSION[saldo];
				if ($_SESSION[saldo] < 5)
					{
					echo "<br>Uw saldo is aan de lage kant.<br>Denk er aan om op te waarderen (beneden bij de balie)";
				}
			?>
			</div>				

			<div id="bodyrightdiv"><br /><b>Uw bestelling voor vandaag:</b><br /><br />
				<?php 
					#init table
					echo (
						"<table id=producttable style='margin:auto'>
		           		 <tr id=productfirstrow>
					         <td id='cardlefttd' style='background-color:#d22b44; width=150px;'>product omschrijving</td>                  	 	
					         <td id='cardcentretd' style='background-color:#d22b44; width=75px;'>prijs (euro)</td>
					         <td id='cardcentretd' style='background-color:#d22b44; width=75px;'>aantal</td>
					         <td id='cardrighttd' style='background-color:#d22b44; width=75px;'>totaal product</td>
							 </tr>"
					);
				
					# Get the order id
					$query = "SELECT bestelling_id FROM bestellingen WHERE datum = CURDATE() AND user_id = $_SESSION[userid]";
					$result = mysql_query($query) or trigger_error("Error while trying to access database");
					
					if (mysql_affected_rows() == 1)
						{
						$orderrow = mysql_fetch_array($result);
					
						# Get the product id and the order time
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
									if (mysql_affected_rows() == 1) { $productrow = mysql_fetch_array($productresult); }
								
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
							</tr>"
							);
						
						mysql_free_result($orderresult);
		      	  	mysql_free_result($productresult);
		      	  } else {
		      	  		echo "Er is nog geen bestelling voor vandaag van u gevonden.";
		      	  }
		      	  echo "</table>";
		      	  mysql_free_result($result);
				?>
			</div>

			<div id="bodyleftdiv"><br /><b>Transacties</b>
			<b>Dit zijn uw laatste transacties.</b>
				<?php  
					#init table
					echo (
						"<table id=producttable>
							<tr id=productfirstrow>
								<td>Referentie</td>                 	 	
								<td>Datum van<br>opwaarderen</td>
								<td>Opgewaardeerde<br>hoeveelheid</td>								
							</tr>"
					);
					
					# query for data
					$query0 = "SELECT * FROM transacties WHERE user_id = $_SESSION[userid] ORDER BY transactie_id DESC LIMIT 6";
					$result = mysql_query($query0) or trigger_error("Error while trying to access database");
				
					# print data to screen
					while($row = mysql_fetch_array($result)){
						echo (
							"<tr>
								<td id=producttd> $row[0] </td>
								<td id=producttd> $row[2] </td>
								<td id=producttd> $row[3] </td>
							</tr>"
						);
					}	
            	echo "</table>";
            	
            	mysql_free_result($result);
			?>
			</div>
				

			<!--div id="bodyrightdiv"><br /><b>Widget 4</b><br /><br />
			</div-->
        
		</div>
		<div id="footer">
			<a href="../disclaimer.php">Disclaimer</a> ----- <a href="../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Xantes ICT; 2014
		</div>
	</body>
</html>
