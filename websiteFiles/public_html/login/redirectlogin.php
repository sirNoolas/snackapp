<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	#open database connection
	require_once("../../include/configdb.php");
?>
<?php
	if (isset($_SESSION[userid]) && isset($_SESSION[active]))
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
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<a href="/index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = './mijnsnackit.php';">
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
  	         	<td id="menuitem"></td>
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
		<div id="footer">
			<a href="../disclaimer.php">Disclaimer</a> ----- <a href="../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Xantes ICT; 2014
		</div>
	</body>
</html>
