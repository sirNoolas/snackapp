<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");
?>
<?php
	if (!isset($_SESSION[userid]))
	{
		header('Location: http://itspixeled.nl/login/redirectlogin.php');
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
		{
			header('Location: http://itspixeled.nl/login/activate.php');
			exit();
		}
	}
	
	if ($_SESSION['admin_value'] != 1)
	{
		header('Location:	http://www.itspixeled.nl/login/mijnsnackit.php');	
		exit();
	}
?>
<!DOCTYE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="itspixeled.nl/cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/admininterface.css">
	</head>
	
	<body>
		<a href="../index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitemselected" onclick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/patat.php';">
  	         		Patat
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/snacks.php';">
  	         		Snacks
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/burgers.php';">
  	         		Burgers
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/dranken.php';">
  	         		Dranken
  	        	 </td>
  	         	<td id="menuitem" onclick="window.location = '../login/logout.php';">
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
			<h3>Aanpassen</h3>
				<?php
					# Check whether a type value has been passed
					if (isset($_GET['type']))
						{
						$type = escape_data($_GET['type']);
						
						echo 
							("
							Als u een bepaald gegeven niet wil aanpassen - laat dan het desbetreffende veld leeg.<br><br>
							<form margin='20px' action='adminactions/updatedb.php?type=$type' method='post'>					
							Huidige naam: <input type='text' name='name' size='15' maxlength='30'>
							Nieuwe naam: <input type='text' name='newname' size='15' maxlength='30'><br><br>
						");
						
						switch ($type) 
							{
							case 'page':
								echo "Het is alleen mogelijk om de naam van een tablad te veranderen.";
								break;
							case 'folder':
								echo "Nieuw page_id<br>(Deze wordt gebruikt om uit te vinden op welk tabblad de folder weergegeven moet worden)<br>";
								echo '<input type="text" name="newid" size="5" maxlength="10"><br>';
								break;
							case 'base':
								echo "Nieuw folder_id<br>(Deze wordt gebruikt om uit te vinden onder welke folder het product weergegeven moet worden)<br>";
								echo '<input type="text" name="newid" size="5" maxlength="10"><br>';
								break;
							case 'sub':
								echo "Nieuw basis_product_id<br>(Deze wordt gebruikt om uit te vinden bij welk basisproduct het subproduct hoort)<br>";
								echo '<input type="text" name="newid" size="5" maxlength="10"><br>';
								echo "Nieuwe prijs<br>";
								echo '<input type="text" name="newprice" size="5" maxlength="10" value="a.b"><br>';
								break;
							default:
								echo "No match was made!";
						}					
					} else {
						header ('Location: http://itspixeled.nl/admininterface/actionadmin.php');
						exit();
					} # END of main IF					
				
				# Finish the table	
				echo 
					('
					<br><br><br>
					Admin wachtwoord: <input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br><br>
					<input type="submit" name="submit_edit" value="Aanpassen"/>
					<input type="hidden" name="edit_row" value="TRUE"/>
				');
				echo "</form><br><br><br>"; 
			
			
			
				/* 
				*
				* START of ECHO currenttable 
				*
				* Display the table that will be edited			 
				* Print the current table
				*
				*/
				
				echo "<h3>huidige tabel ($type)</h3>";
				
				switch ($type) 
					{
					case 'page':
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
						
					case 'folder':
						#init table
						echo (
							"<table id=producttable>
					   		 <tr id=productfirstrow>
					   		 	<td>Folder_id</td>
						     	 	<td>PageId</td> 
						     	 	<td>Naam</td>                 	 	
								</tr>"
						);
				
						# query for data
						$query0 = "SELECT * FROM folders ORDER BY folder_id ASC";
						$result = mysql_query($query0) or trigger_error("Error while trying to access database");
						# print data to screen
						while($row = mysql_fetch_array($result))
							{
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
						break;
					# END of CASE folder
						
					case 'base':
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
						$query0 = "SELECT * FROM basis_product ORDER BY basis_product_id ASC";
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
					
					case 'sub':
						#init table
						echo (
							"<table id=producttable>
					   		 <tr id=productfirstrow>
					   		 	<td>Product_id</td>
						     	 	<td>Basis_product_Id</td> 
						     	 	<td>Naam</td> 
						     	 	<td>Prijs</td>                	 	
								</tr>"
						);
				
						# query for data
						$query0 = "SELECT * FROM sub_products ORDER BY product_id ASC";
						$result = mysql_query($query0) or trigger_error("Error while trying to access database");
						# print data to screen
						while($row = mysql_fetch_array($result))
							{
							echo (
								"<tr>
									<td id=producttd> $row[0] </td>
									<td id=producttd> $row[1] </td>
									<td id=producttd> $row[2] </td>
									<td id=producttd> $row[3] </td>
								</tr>"
							);
						}	
					   echo "</table>";
					   	
						mysql_free_result($result);
						break;
					# END of CASE sub
					
					default:
						echo "No match was made!";
				}
		
			/*
			*
			* END of ECHO currenttable
			*
			*/
			?>
      </div>
		
	</body>
	<?php
		mysql_close();
		exit();
	?>
</html>
