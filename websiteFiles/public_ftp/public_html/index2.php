<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../include/configdb.php");	
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
  	     		<td id="menuitem" onclick="window.location = 'index.php';">
  	         	Index
  	         </td>
  	         <td id="menuitem" onclick="window.location = 'patat.php';">
  	         	Patat
  	         </td>
  	         <td id="menuitem" onclick="window.location = 'snack1.php';">
  	         	Snacks 1
  	         </td>
  	         <td id="menuitem" onclick="window.location = 'snack2.php';">
  	         	Snacks 2
  	         </td>
  	         <td id="menuitem" onclick="window.location = 'dranken.php';">
  	         	Dranken
  	         </td>
  	         <td id="menuitem">
  	         	Log uit 
  	         </td>
  			</tr>
  		</table>
		
		<main>
<?php
	if (isset($_POST['submitted'])) # Check whether the form has been submitted
	{ 
		# Check for valid email
		if (preg_match ('%^[A-Za-z0-9._\%-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$%', stripslashes(trim($_POST['email']))))
		{
			$u = escape_data($_POST['email']);
		} else {
			$u = FALSE;
			echo 'Gebruikersnaam is incorrect!'
;
		}
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
			echo '<p>Wachtwoord is incorrect!</p>';
		}
		if ($u && $p)
		{																											#TODO: remake the password encryption (SHA)
			$query = "SELECT email, first_name, password FROM users WHERE email='$u' AND password='$p'";
			$result = mysql_query($query) or trigger_error("De gegeven logingegevens zijn incorrect!");
			
			if (mysql_affected_rows() == 1) {
				$row = mysql_fetch_array($result, MYSQL_NUM);
				mysql_free_result($result);
				$_SESSION['first_name'] = $row[2];
				$_SESSION['email'] = $row[0];
				$_SESSION['userid'] = $row[5];
				
				#create second token
				$tokenId = rand(10000, 9999999);
				$query2 = "UPDATE users SET tokenid = $tokenId WHERE userid = '$_SESSION[userid]'";
				$result = mysql_query($query2);
				$_SESSION['token_id'] = $tokenId;
				
				//session_regenerate_id();
				
				//header('Location: http://www.google.com');
				mysql_close(); # Close database connection
				exit();
			}
		} else {	# No math was made
			echo '<br><br><p>Vul alstublieft correcte inloggegevens in!</p>';
			mysql_close();
			exit();
		}
		echo '<br><br><p>Vul alstublieft correcte inloggegevens in!</p>';
		mysql_close();
		exit();
	}
	# End of submit conditional.		
?>
			<div id="login"><br /><b>Inloggen</b><br /><br />
			<form margin="20px" action="index2.php" method="post">
  	        	<input type="text" name="email" size="15" maxlength="30" value="Email"/>
  	        	<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/>
  	        	<input type="submit" name="submit" value="login"/>
  	        	<input type="hidden" name="submitted" value="TRUE"/>
			</form></div>

			<div id="register"><br /><b>Registreren</b><br /><br /></div>
<div id="image"></div>

	
		</main>

	</body>
</html>