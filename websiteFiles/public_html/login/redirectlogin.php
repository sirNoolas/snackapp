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
		<link rel="shortcut icon" href="../cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = '/login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '/subpages/patat.php';">
  	         		Patat
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '/subpages/snacks.php';">
  	         		Snacks
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '/subpages/burgers.php';">
  	         		Burgers
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '/subpages/dranken.php';">
  	         		Dranken
  	        	 </td>
  	         	<td id="menuitemselected" onClick="window.location = '../index.php';">
  	               		Registreren
  	         	</td>
  			</tr>
  		</table>
		
		<div id="main">
		
			<br /><b>U moet ingelogd zijn als u deze informatie wilt bekijken.</b><br /><br />
			<form margin="20px"  action="../login/login.php" method="post" autocomplete="on">
  	        	<input type="text" name="email" size="15" maxlength="30" value="Email"/>
  	        	<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/>
  	        	<input type="submit" name="submit" value="login"/>
  	        	<input type="hidden" name="login_submitted" value="TRUE"/>
			</form>
				<div id="error">
						<?php
							# Print provided error messages:
							if (isset($_GET['x']))
							{
								$x = $_GET['x'];
								echo "<br><br>$x";
							}
						?>
				</div>
			
			</div>
	
		</div>

	</body>
</html>
