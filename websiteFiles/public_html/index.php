<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../include/configdb.php");	
?>

<?php/*
	if (isset($_POST['submitted']
	*/
?>

<!DOCTYE html>
<html lang="nl">

	<head>
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="./cssstylesheets/custinterface.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem">
  	         	Menu 1
  	         </td>
  	         <td id="menuitem">
  	         	Menu 2
  	         </td>
  	         <td id="menuitem">
  	         	Menu 3
  	         </td>
  	         <td id="menuitem">
  	         	Menu 4
  	         </td>
  	         <td id="menuitem">
  	         	Menu 5
  	         </td>
  	         <td id="menuitem">
  	         	Menu 6
  	         </td>
  	         <form action="goodlogin.php" method="post">
  	        		<td><b>E-mail</b> <input type="text" name="email" size="17" maxlength="30"/></td>
  	        		<td><b>Wachtwoord</b> <input type="password" name="pass" size="17" maxlength="20"/></td>
  	        		<td><input type="submit" name="submit" value="login"/></td>
  	        		<input type="hidden" name="submitted" value="TRUE"/>
  	         </form>
  			</tr>
  		</table>
		
		<main>

	</body>
</html>
