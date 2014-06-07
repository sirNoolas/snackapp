<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
?>
<?php
	if (isset($_SESSION[userid]) && isset($_SESSION[active]))
	{
		if ($_SESSION[active] == NULL) {
			header('Location: http://itspixeled.nl/login/mijnsnackit.php');
			exit();
		} else {
			header('Location: http://itspixeled.nl/login/activate.php');
			exit();
		}
	}
?>

<!DOCTYE html>
<html lang="nl">

	<head>
	<link rel="shortcut icon" href="./cssstylesheets/logo.gif"
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="./cssstylesheets/index.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onclick="window.location = '/login/mijnsnackit.php';">
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
  	         	<td id="menuitemselected">
  	               Login/Registreren
  	         	</td>
  			</tr>
  		</table>
		</div>
		
		<div id="main">
		
			<div id="register"><br /><b>Registreren</b><br /><br />
				<div id="registerleft">
					<br>
					E-mail: <br>
					Wachtwoord:<br>
					Herhaal:<br>
					Voornaam: <br>
					Achternaam:
				</div>
				<div id="registerright">
					<br>
					<form margin="20px"  action="./login/register.php" method="post" autocomplete="on">
						<input type="text" name="email" size="15" maxlength="30" value="Email"/><br>
						<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br>
						<input type="password" name="controlpassword" size="15" maxlength="20" value="Wachtwoord"/><br>
						<input type="text" name="first_name" size="15" maxlength="12" value="Voornaam"/><br>
						<input type="text" name="last_name" size="15" maxlength="12" value="Achternaam"/><br><br>
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
				<div id="error">
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
			
		<div id="image"></div>

	
		

	</body>
</html>
