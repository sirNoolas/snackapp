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
	
	if ($_SESSION[admin_value] != 1) 
	{
		header('Location: http://itspixeled.nl/login/mijnsnackit.php');
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
<link rel="shortcut icon" href="./cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/admininterface.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitemselected" onClick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/patat.php';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/snacks.php';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/burgers.php';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/dranken.php';">
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
  				<td id="adminmenuitem" onclick="window.location = 'actionadmin.php';">
  					Admin acties
  				</td>
  				<td id="adminmenuitemselected" onclick="window.location = 'useradminindex.php';">
  					User index
  				</td>
  			</tr>
  		</table>
		
		<div id="main">
			<div id="bodyleftdiv"><br /><b>Welkom, <?php echo $_SESSION[first_name]; ?></b>
			<br />
			<?php
				echo "Uw saldo is: ";
				echo $_SESSION[saldo];
			?>
			</div>				

			<div id="bodyrightdiv"><br /><b>Uw bestelling voor [datum vrijdag]</b><br /><br />
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
				

			<div id="bodyrightdiv"><br /><b>Widget 4</b><br /><br />
			</div>
        
      </div>
		
	</body>
</html>
