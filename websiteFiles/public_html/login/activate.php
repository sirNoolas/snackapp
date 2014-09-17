<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");
	
	if (isset($_SESSION[active]))
	{
		if ($_SESSION[active] == NULL)
		{ # Redirect
			header("Location: /index.php");
			mysql_close();
			exit();
		}
	}
?>
<?php
	# Log out user
	$_SESSION = array();
	session_destroy();
	setcookie (session_name(), '', time()-300, '/', '', 0);
?>
<?php
	# Make sure x and y are valid and have a value
	# x = user_id
	# y = activation code
	
	if (isset($_GET['x']) && isset($_GET['y']))
	{
		$x = (int) escape_data($_GET['x']);
		$y = escape_data($_GET['y']);
		// If $x and $y aren't correct, redirect the user
		if (($x > 0) && (strlen($y) == 32))
		{
			$query = "UPDATE users SET active=NULL WHERE (user_id='$x' AND active='$y') LIMIT 1";
			$result = mysql_query($query) or trigger_error("Error while trying to access database");
			if (mysql_affected_rows() == 1)
			{ 
				session_regenerate_id();
				$result_string = "<br><br><h3>Gefeliciteerd: uw account is succesvol geactiveerd. Vanaf nu kunt u inloggen.</h3>";
			} else {
				$result_string = "<br><br><p>Uw account kon niet geactiveerd worden.<br><br><b>De meest waarschijnlijke oorzaak is dat uw account alreeds geactiveerd is.</b><br>Dit kunt u controleren door een inlogpoging te doen. Lukt deze, dan is uw account al geactiveerd en hoeft u verder niets te doen.<br><br>Lukt inloggen niet: Controleer dan alstublieft de link of neem contact op met de systeembeheerder.</p>";
			}
		} # end of check for correct IF
		
	} else if (isset($_SESSION[userid])) {
		$result_string = FALSE;
	}  # End of main IF
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
		<link rel="shortcut icon" href="/cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<a href="/index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = '../login/mijnsnackit.php';">
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
		</div>
		
		<div id="main">
			<?php
				if ($result_string)
				{
					echo "$result_string";
				} else {
					echo "<br><br>Er is een activatie-link naar u gestuurd.<br>";
					echo "Klik op die link om uw account te activeren.<br>";
					echo "<br><br>Geen link ontvangen? Neem dan contact op met een beheerder.";
				}
				mysql_close();
				exit();	
			?>
		</div>			
		<div id="image"></div>
		<div id="footer">
			<a href="../disclaimer.php">Disclaimer</a> ----- <a href="../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Thijs Werkman, Xantes ICT; 2014
		</div>

	</body>
</html>
