Located here is almost everything.

Standart session regeneration and checking:

# Check for valid token
	$query0 = "SELECT token_id FROM users WHERE user_id='$_SESSION[userid]'";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");
	
	if (mysql_affected_rows() == 1) 
		{	
		$currenttoken = mysql_fetch_array($result, MYSQL_NUM);
		if ($currenttoken[0] != $_SESSION[token_id])
			{
			header('Location: /login/logout.php');
			mysql_close();
			exit();
		}
	}
				
				# update second token
				$tokenId = rand(10000, 9999999);
				$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query4);
				$_SESSION['token_id'] = $tokenId;
				
				
				
				session_regenerate_id();

