<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../../include/configdb.php");	
	
	DEFINE ('MAX_VALUE', 300);
?>
<?php
	if (isset($_POST['rm_user'])) # Check whether the form has been submitted
	{
		# Check for valid email
		if (!preg_match ('%^[A-Za-z0-9._\%-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$%', stripslashes(trim($_POST['email']))))
		{
			$e = escape_data($_POST['email']);
			if ($e == $_SESSION[email])
			{
				$e = FALSE;
				$error .= "You cannot delete your own account!<br>";
			}
		} else {
			$e = FALSE;
			$error .= "Please enter a valid email!<br>";
		}
		
		# Check for valid subject name
		if (preg_match ('%^[A-Za-z.-]{2,12}$%', stripslashes(trim($_POST['first_name']))))
		{
			$fn = escape_data($_POST['first_name']);
		} else {
			$fn = FALSE;
			$error .= "please enter a valid firstname from the subject<br>";
		}
		
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
			$error .= "please enter a valid password!<br>";
		}
		
		if ($e && $fn && $p)
		{
			#check for valid admin password and whether the person isn't adding money to its own account
			$query0 = "SELECT * FROM users WHERE user_id='$_SESSION[userid]' AND password=md5('$p')";
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
					
				$query1 = "DELETE FROM users WHERE email='$e' AND first_name='$fn'";
				$result = mysql_query($query1) or trigger_error("Error while trying to access database");
				
				if (mysql_affected_rows() > 1)
				{
					$error .= "Major error! Deleted multiple users";
				}
			}			
		} # end of secondary IF
		# update second token
		$tokenId = rand(10000, 9999999);
		$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
		$result = mysql_query($query4);
		$_SESSION['token_id'] = $tokenId;
				
		session_regenerate_id();		
	} # end of main IF
	header("Location: /admininterface/adminindex.php?x=$error");
	mysql_close();
	exit();
?>
		
