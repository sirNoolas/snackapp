<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	#open database connection
	require_once("../include/configdb.php");
?>
<?php
	if (isset($_SESSION[userid]))
	{
		if ($_SESSION[active] == NULL) {
			header('Location: /login/mijnsnackit.php');
			exit();
		} else {
			header('Location: /login/activate.php');
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
		<link rel="shortcut icon" href="./cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="./cssstylesheets/index.css">
	</head>
	
	<body>
		<a href="/index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = './login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=0';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=1';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=2';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=3';">
  	         		<?php echo $names[3]; ?>
  	        	 </td>
  	         	<td id="menuitem"></td>
  			</tr>
  		</table>
		</div>
		
		<div id="main">
		
			<div id="register"><br /><b>Registreren</b><br /><br />
				<div id="registerleft">
					<br>
					E-mail:<br>
					Wachtwoord:<br>
					Herhaal:<br>
					Voornaam:<br>
					Achternaam:
				</div>
				<div id="registerright">
					<br>
					<form margin="20px"  action="./login/register.php" method="post" autocomplete="on">
						<input type="text" name="email" size="15" maxlength="30" value=""/><br>
						<input type="password" name="password" size="15" maxlength="20" value=""/><br>
						<input type="password" name="controlpassword" size="15" maxlength="20" value=""/><br>
						<input type="text" name="first_name" size="15" maxlength="12" value=""/><br>
						<input type="text" name="last_name" size="15" maxlength="12" value=""/><br><br>
						<input type="submit" name="submit_register" value="Registreren"/>
						<input type="hidden" name="register_submitted" value="TRUE"/>
					</form>
				</div>
			</div>
			
			<div id="login"><br /><b>Inloggen</b><br /><br />
				<form margin="20px"  action="./login/login.php" method="post" autocomplete="on">
  	   	     	<input type="text" name="email" size="15" maxlength="30" value="Email"/>
  	   	     	<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/>
  	   	     	<input type="submit" name="submit_login" value="login"/>
  	   	     	<input type="hidden" name="login_submitted" value="TRUE"/>
				</form>
				<span id="error">
					<?php
						# Print provided error messages:
						if (isset($_GET['x']))
							{
							# The following may seem unsecure; but it isn't because there is no database connection and the value is only printed.
							$x = $_GET['x'];
							echo "<br>$x";
						}
					?>
				</span>
			</div>
			<div id="image"></div>
		</div>
		<div id="footer">
			<a href="./disclaimer.php">Disclaimer</a> ----- <a href="./sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Thijs Werkman, Xantes ICT; 2014
		</div>
	</body>
</html>
