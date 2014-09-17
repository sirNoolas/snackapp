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
	
	if ($_SESSION['admin_value'] != 1)
	{
		header('Location:	/login/mijnsnackit.php');	
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
		<link rel="shortcut icon" href="../cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/admininterface.css">
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
  		<table id="adminmenu">
  			<tr>
  				<td id="adminmenuitem" onclick="window.location = 'adminindex.php';">
  					Admin index
  				</td>
  				<td id="adminmenuitemselected" onclick="window.location = 'actionadmin.php';">
  					Admin acties
  				</td>
  				<td id="adminmenuitem" onclick="window.location = 'useradminindex.php';">
  					User index
  				</td>
  			</tr>
  		</table>
  		
  		<!-- END of MENU -->	
		
		<div id="main">
			<br>
				<?php
					if (isset($_GET['type']) && isset($_GET['request']))
						{
						# Define type
						$type = escape_data($_GET['type']);
						
						if ($_GET['request'] == 'remove')
							{
							# Tell the user what they are doing
							echo "<h3>Een rij verwijderen van het type: $type</h3>";
							
							echo 
								("
								<form margin='20px' action='adminactions/delrow.php?type=$type' method='post'>
								" . '					
								Naam: <input type="text" name="name" size="15" maxlength="30">
								<br><br>
								<br><br>
								Admin wachtwoord: <input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/>
								<br><br>
								<input type="submit" name="submit_del" value="Verwijderen"/>
								<input type="hidden" name="del_row" value="TRUE"/>
								</form><br><br><br>
							');
							
							switch ($type)
								{
								case 'page':
									$type = pages;
									break;
								case 'folder':
									$type = folders;
									break;
								case 'base':
									$type = basis_product;
									break;
								case 'sub':
									$type = sub_product;
									break;
								default:
									$error.= "Dit soort rij word niet ondersteund!";
									$type = FALSE;
									header("Location: /admininterface/adminindex.php?x=$error");
									mysql_close();
									exit();
							}
							
						# Check whether a type value has been passed
						} else if ($_GET['request'] == 'add')
							{
							
							
							# Tell the user what they are doing
							echo "<h3>Een rij toevoegen van het type: $type</h3>";
							
							echo 
								("
								<form margin='20px' action='adminactions/addrow.php?type=$type' method='post'>					
								Naam: <input type='text' name='name' size='15' maxlength='30'><br><br>
							");
							
							switch ($type) 
								{
								case 'folder':
									echo "page_id<br>(Deze wordt gebruikt om uit te vinden op welk tabblad de folder weergegeven moet worden)<br>";
									echo '<input type="text" name="id" size="5" maxlength="10"><br>';
									break;
								case 'base':
									echo "folder_id<br>(Deze wordt gebruikt om uit te vinden onder welke folder het product weergegeven moet worden)<br>";
									echo '<input type="text" name="id" size="5" maxlength="10"><br>';
									break;
								case 'sub':
									echo "basis_product_id<br>(Deze wordt gebruikt om uit te vinden bij welk basisproduct het subproduct hoort)<br>";
									echo '<input type="text" name="id" size="5" maxlength="10"><br>';
									echo "prijs<br>";
									echo '<input type="text" name="price" size="5" maxlength="10" value="a.b"><br>';
									break;
								default:
									echo "Dit type rij word niet ondersteunt!";
							}
							
							# Finish the table		
							echo 
								('
									<br><br>
									Admin wachtwoord: <input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br><br>
									<input type="submit" name="submit_add" value="Toevoegen"/>
									<input type="hidden" name="add_row" value="TRUE"/>
							');	
							
							switch ($type)
								{
								case 'folder':
									$type = pages;
									break;
								case 'base':
									$type = folders;
									break;
								case 'sub':
									$type = basis_product;
									break;
								default:
									$error.= "Dit soort rij word niet ondersteund!";
									$type = FALSE;
									header("Location: /admininterface/adminindex.php?x=$error");
									mysql_close();
									exit();
							}
							
							echo "</form><br><br><br>";
						} else {
							echo "Dit verzoek word niet ondersteunt!";
						}
					} else {
						echo "Uw bezoek aan deze pagina is invalide!";
						mysql_close();
					} # END of main IF					
				
			
					/* 
					*
					* START of ECHO currenttable 
					*
					* Display the table that will be edited			 
					* Print the current table
					*
					*/
				
					echo "<h3>huidige tabel (haal hier je id uit)</h3>";
				
					switch ($type) 
						{
						case 'pages':
							#init table
							echo 
								("
								<table id=producttable>
									 <tr id=productfirstrow>
			 						 	<td>PageId</td>
			  						 	<td>Naam</td>                  	 	
									</tr>"
							);
											
							# query for data
							$query0 = "SELECT * FROM pages ORDER BY page_id ASC";
							$result = mysql_query($query0) or trigger_error("Error while trying to access database" . mysql_error());
						
							# print data to screen
							while($row = mysql_fetch_array($result))
								{
								echo (
									"<tr>
										<td id=producttd> $row[0] </td>
										<td id=producttd> $row[1] </td>
									</tr>"
								);
							}	
							echo "</table>";
								
							mysql_free_result($result);
							break;
							# END of CASE page
					
						case 'folders':
							#init table
							echo 
								("
								<table id=producttable>
									 <tr id=productfirstrow>
			 						 	<td>PageId</td>
			  						 	<td>Naam</td>                  	 	
									</tr>"
							);
											
							# query for data
							$query0 = "SELECT * FROM folders ORDER BY page_id ASC";
							$result = mysql_query($query0) or trigger_error("Error while trying to access database" . mysql_error());
						
							# print data to screen
							while($row = mysql_fetch_array($result))
								{
								echo (
									"<tr>
										<td id=producttd> $row[0] </td>
										<td id=producttd> $row[3] </td>
									</tr>"
								);
							}	
							echo "</table>";
								
							mysql_free_result($result);
							break;
							# END of CASE page
						
						case 'basis_product':
							#init table
							echo (
								"<table id=producttable>
									 <tr id=productfirstrow>
								  	 	<td>Basis_product_id</td> 
								  	 	<td>Folder_id</td>
								  	 	<td>Naam</td>                 	 	
									</tr>"
							);
				
							# query for data
							$query0 = "SELECT * FROM basis_product ORDER BY folder_id ASC";
							$result = mysql_query($query0) or trigger_error("Error while trying to access database");
							# print data to screen
							while($row = mysql_fetch_array($result))
								{
								echo (
									"<tr>
										<td id=producttd> $row[0] </td>
										<td id=producttd> $row[1] </td>
										<td id=producttd> $row[2] </td>
									</tr>"
								);
							}	
							echo "</table>";
								
			 				mysql_free_result($result);
							break;
						# END of CASE folder
						
						case 'sub_products':
							#init table
							echo (
								"<table id=producttable>
									 <tr id=productfirstrow>
									 	<td>Basis_product_Id</td> 
									 	<td>Folder_id</td>								     	 	
								  	 	<td>Naam</td>                 	 	
									</tr>"
							);
				
							# query for data
							$query0 = "SELECT * FROM sub_products ORDER BY basis_product_id ASC";
							$result = mysql_query($query0) or trigger_error("Error while trying to access database");
						
							# print data to screen
							while($row = mysql_fetch_array($result))
								{
								echo (
									"<tr>
										<td id=producttd> $row[0] </td>
										<td id=producttd> $row[1] </td>
										<td id=producttd> $row[2] </td>
									</tr>"
								);
							}	
							echo "</table>";
								
			 				mysql_free_result($result);
							break;
						# END of CASE base
					
						default:
							echo "Dit bestaat niet!";
					}
		
				/*
				*
				* END of ECHO currenttable
				*
				*/
			
				# update second token
				$tokenId = rand(10000, 9999999);
				$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query4);
				$_SESSION['token_id'] = $tokenId;
			?>
		</div>
		<div id="footer">
			<a href="../disclaimer.php">Disclaimer</a> ----- <a href="../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Thijs Werkman, Xantes ICT; 2014
		</div>
	</body>
	<?php
		mysql_close();
		exit();
	?>
</html>
