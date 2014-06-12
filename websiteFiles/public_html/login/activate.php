<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");
	
	if ($_SESSION[active] == NULL)
	{ # Redirect
		header("Location: http://www.itspixeled.nl/login/mijnsnackit.php");
		mysql_close();
		exit();
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
		$x = (int) $_GET['x'];
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
				$result_string = "<br><br><p>Uw account kon niet geactiveerd worden. Controleer alstublieft de link of neem contact op met de systeembeheerder.</p>";
			}
		} # end of check for correct IF
		
	} else if (isset($_SESSION[userid])) {
		$result_string = FALSE;
	}  # End of main IF
?>


<!DOCTYE html>
<html lang="nl">

	<head>
	<link rel="shortcut icon" href="../cssstylesheets/logo.gif"
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onclick="window.location = 'mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '/subpages/patat.php';">
  	         		Patat
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '/subpages/snacks.php';">
  	         		Snacks
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '/subpages/burgers.php';">
  	         		Burgers
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '/subpages/dranken.php';">
  	         		Dranken
  	        	 </td>
  	         	<td id="menuitem" onclick="window.location = '/index.php';">
  	               Login/Registreren
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
		

	</body>
</html>
