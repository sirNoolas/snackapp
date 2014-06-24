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
		<link rel="shortcut icon" href="../cssstylesheets/logo.gif"
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/admininterface.css">
	</head>
	
	<body>
		<header>
		</header>
		
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
				<?php
					# Check whether a type value has been passed
					if (isset($_GET['type']))
						{
						$type = escape_data($_GET['type']);
						
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
						echo 
							('
								<br><br>
								Admin wachtwoord: <input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br><br>
								<input type="submit" name="submit_edit" value="Toevoegen"/>
								<input type="hidden" name="add_row" value="TRUE"/>
						');	
						echo "</form><br><br><br>"; 				
					} else {
						header ('Location: http://itspixeled.nl/admininterface/actionadmin.php');
						exit();
					} # END of main IF					
				
				# Finish the table	
				
			
			
			
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
					case 'folder':
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
						
					case 'base':
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
						
					case 'sub':
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
