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
		mysql_close();
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
		{
			header('Location: http://itspixeled.nl/login/activate.php');
			mysql_close();
			exit();
		}
	}
	
	if ($_SESSION['admin_value'] != 1)
	{
		header('Location:	http://www.itspixeled.nl/login/mijnsnackit.php');
		mysql_close();	
		exit();
	}
?>

<!DOCTYE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="./cssstylesheets/logo.gif"
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
  				<td id="adminmenuitemselected" onclick="window.location = 'adminindex.php';">
  					Admin index
  				</td>
  				<td id="adminmenuitem" onclick="window.location = 'actionadmin.php';">
  					Admin acties
  				</td>
  				<td id="adminmenuitem" onclick="window.location = 'useradminindex.php';">
  					User index
  				</td>
  			</tr>
  		</table>
  				
		
		<div id="main">
			<div id="bodyleftdiv"><br /><b>Tabellen openstellen</b><br />
				<?php 
					#init table
					echo (
						"<table id=producttable>
               		 <tr id=productfirstrow>
                  	 	<td>ID</td>
                  	 	<td>Naam</td>                  	 	
                  	   <td>Status</td>
							</tr>"
					);
					
					# query for data
					$query0 = "SELECT * FROM folders ORDER BY folder_id ASC";
					$result = mysql_query($query0) or trigger_error("Error while trying to access database");
				
					# print data to screen
					while($row = mysql_fetch_array($result)){
						echo (
							"<tr>
								<td id=producttd> $row[0] </td>
								<td id=producttd> $row[3] </td>
								<td id=producttd> $row[1] </td>
								<td id=producttd><a href='./adminactions/updatedb.php?x=$row[0]&y=1'>open</a> or <a href='./adminactions/updatedb.php?x=$row[0]&y=0'>close</a></td>
							</tr>"
						);
					}	
            	echo "</table>";
            	
            	mysql_free_result($result);
			?>
			</div>

			<div id="bodyrightdiv"><br /><b>acties</b><br /><br />
				<ul><li><a href="actionadmin.php">Een tabel toevoegen</a></li>
				<li><a href="actionadmin.php">Een tabel aanpassen</a></li>
				<li><a href="actionadmin.php">Saldo van een gebruiker ophogen</a></li>
				<li><a href="actionadmin.php">Een gebruiker verwijderen</a></li><br>
				<li><a href="readorders.php">Bestellingen uitlezen</a></li>
				</ul>
			</div>

			<div id="bodyleftdiv"><br /><b>Transacties</b>
				<?php 
					#init table
					echo (
						"<table id=producttable>
               		 <tr id=productfirstrow>
                  	 	<td>ID</td>
                  	 	<td>User ID</td>                  	 	
                  	   <td>Datum</td>
                  		<td>Euro</td>							
								<td>Admin ID</td>	
							</tr>"
					);
					
					# query for data
					$query0 = "SELECT * FROM transacties ORDER BY transactie_id DESC LIMIT 7";
					$result = mysql_query($query0) or trigger_error("Error while trying to access database");
				
					# print data to screen
					while($row = mysql_fetch_array($result)){
						echo (
							"<tr>
								<td id=producttd> $row[0] </td>
								<td id=producttd> $row[1] </td>
								<td id=producttd> $row[2] </td>
								<td id=producttd> $row[3] </td>
								<td id=producttd> $row[4] </td>
							</tr>"
						);
					}	
            	echo "</table>";
            	
            	mysql_free_result($result);
			?>
			</div>

			<div id="bodyrightdiv"><br /><b>System output</b><br /><br />
				<div id="errordiv">
					<?php
						# Print provided error messages:
						if (isset($_GET['x']))
						{
							$x = $_GET['x'];
							echo "<br>$x";
						}
					?>
				</div>
			</div>
        
      </div>
		
	</body>
	<?php
		mysql_close();
		exit();
	?>
</html>
