<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
?>
<?php
	if (isset($_SESSION[email]))
	{
		header('Location: http://itspixeled.nl/login/mijnsnackit.php');
		exit();
	}
?>

<!DOCTYE html>
<html lang="nl">

	<head>
	<link rel="shortcut icon" href="./cssstylesheets/logo.gif"
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="./cssstylesheets/general.css">
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
  	         	<td id="menuitem">
  	               		
  	         	</td>
  			</tr>
  		</table>
		
		<div id="main">
		
			<div id="bodyleftdiv"><br /><b>Registreren</b><br /><br /></div>
			<div id="bodyrightdiv"><br /><b>Inloggen</b><br /><br />
			<form margin="20px"  action="./login/login.php" method="post" autocomplete="on">
  	        	<input type="text" name="email" size="15" maxlength="30" value="Email"/>
  	        	<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/>
  	        	<input type="submit" name="submit" value="login"/>
  	        	<input type="hidden" name="submitted" value="TRUE"/>
			</form>
			<u>U bent niet ingelogd!</u></div>
			<div id="image"></div>

	
		</div>

	</body>
</html>
