<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");	
?>
<?php 
	if (isset($_POST['submitted'])) # Check whether the form has been submitted
	{ 
		# Check for valid email
		if (preg_match ('%^[A-Za-z0-9._\%-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$%', stripslashes(trim($_POST['email']))))
		{
			$u = escape_data($_POST['email']);
		} else {
			$u = FALSE;
		}
		
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
		}
		
		if ($u && $p)
		{																											
			$query = "SELECT email, first_name, password FROM users WHERE email='$u' AND password='$p'";
			$result = mysql_query($query) or trigger_error();
			
			if (mysql_affected_rows() == 1) {
				$row = mysql_fetch_array($result, MYSQL_NUM);
				mysql_free_result($result);
				$_SESSION['email'] = $row[0];
				$_SESSION['userid'] = $row[5];
				
				#create second token
				$tokenId = rand(10000, 9999999);
				$query2 = "UPDATE users SET tokenid = $tokenId WHERE userid = '$_SESSION[userid]'";
				$result = mysql_query($query2);
				$_SESSION['token_id'] = $tokenId;
				
				session_regenerate_id();
				
				header('Location: http://itspixeled.nl/login/mijnsnackit.php');
				mysql_close(); # Close database connection
				exit();
			}
		} else {	# No math was made
			header('Location: http://itspixeled.nl/index.php');
			mysql_close();
			exit();
		}
		header('Location: http://itspixeled.nl/index.php');
		mysql_close();
		exit();
	}
	# End of submit conditional.		
?>
