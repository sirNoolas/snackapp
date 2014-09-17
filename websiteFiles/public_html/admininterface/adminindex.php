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
		mysql_close();
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
		{
			header('Location: /login/activate.php');
			mysql_close();
			exit();
		}
	}
	
	if ($_SESSION['admin_value'] != 1)
	{
		header('Location:	/login/mijnsnackit.php');
		mysql_close();	
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
				<p>Als status een waarde geeft van 1, dan is de tabel open voor bestellingen van gebruikers. Is status 0 dan is de tabel dat niet.</p>
				<?php 
					#init table
					echo (
						"<table id=producttable>
               		 <tr id=productfirstrow>
                  	 	<td>ID</td>
                  	 	<td>Naam</td>                  	 	
                  	   	<td>Status</td>
						<td></td>
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
								<td id=producttd><a href='./adminactions/updatedb.php?x=$row[0]&y=1'>open</a> of <a href='./adminactions/updatedb.php?x=$row[0]&y=0'>sluit</a></td>
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
							$x = escape_data($_GET['x']);
							echo "<br>$x";
						}
					?>
				</div>	
         </div>

			<div id="bodyleftdiv"><br /><b>Transacties</b>
				<?php 
					#init table
					echo (
						"<table id=producttable>
               		<tr id=productfirstrow>
                  	 	<td> </td>
                  	 	<td>User ID</td>                  	 	
                  	   	<td>Datum</td>
                  		<td>Euro</td>							
						<td>Admin ID</td>	
					</tr>"
					);
					
					# query for data
					$query0 = "SELECT * FROM transacties ORDER BY transactie_id DESC LIMIT 6";
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

			<div id="bodyrightdiv"><br /><b>Bestellingen uitlezen</b><br /><br />
				<span>
					<a href="adminactions/readorders.php?display=all">Alle losse bestellingen op volgorde van tijd</a><br>
					<a href="adminactions/readorders.php?display=user">De bestellingen sorteren per gebruiker</a><br><br>
					<a href="adminactions/readorders.php?display=allsorted"><u>Alle dezelfde bestellingen op een regel zetten</u></a><br><br>
					De bovenstaande drie acties geven alleen de bestellingen van vandaag.<br><br>
				</span>
				<span>
					<a href="adminactions/readorders.php?display=allhistory">Alle losse bestellingen uit de geschiedenis</a><br><br>
				</span>
				
			</div>
        
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
