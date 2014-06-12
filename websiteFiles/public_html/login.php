<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");
?>
<?php 
	if (isset($_POST['login_submitted'])) # Check whether the form has been submitted
	{ 
		# Check for valid email
		if (preg_match ('%^[A-Za-z0-9._\%-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$%', stripslashes(trim($_POST['email']))))
		{
			$u = escape_data($_POST['email']);
		} else {
			$u = FALSE;
			$error .= "Foutieve invoer: email-adres<br>";
		}
		
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
			$error .= "Foutieve invoer: wachtwoord<br>";
		}
		
		if ($u && $p)
		{																											
			$query = "SELECT * FROM users WHERE email='$u' AND password=md5('$p')";
			$result = mysql_query($query) or trigger_error("Error while trying to access database");
			
			if (mysql_affected_rows() == 1) 
			{
				$row = mysql_fetch_array($result, MYSQL_NUM);
				mysql_free_result($result);
				$_SESSION['userid'] = $row[0];
				$_SESSION['email'] = $row[2];
				$_SESSION['first_name'] = $row[4];
				$_SESSION['last_name'] = $row[5];
				$_SESSION['saldo'] = $row[6];
				$_SESSION['admin_value'] = $row[7];
				$_SESSION['active'] = $row[8];
				
				#create second token
				$tokenId = rand(10000, 9999999);
				$query2 = "UPDATE users SET tokenid = $tokenId WHERE userid = '$_SESSION[userid]'";
				$result = mysql_query($query2);
				$_SESSION['token_id'] = $tokenId;
				
				session_regenerate_id();
				if ($_SESSION[active] == NULL)
				{
					header('Location: http://itspixeled.nl/login/mijnsnackit.php');
				} else {
					header('Location: http://itspixeled.nl/login/activate.php');
				}
				mysql_close(); # Close database connection
				exit();
			}
			
		} else {	# No match was made
			$error .= "De gebruikersnaam en//of het wachtwoord is fout!<br>";
			header("Location: http://itspixeled.nl/login/redirectlogin.php?x=$error");
			mysql_close();
			exit();
		}
		
		header("Location: http://itspixeled.nl/login/redirectlogin.php?x=$error");
		mysql_close();
		exit();
	}
	# End of submit conditional.		
?>
