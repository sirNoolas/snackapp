<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../../include/configdb.php");	
?>
<?php
	if (!isset($_SESSION[userid]))
	{
		header('Location: /login/redirectlogin.php');
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
		{
			header('Location: /login/activate.php');
			exit();
		}
	}
	
	if ($_SESSION[admin_value] != 1) 
	{
		header('Location: /login/mijnsnackit.php');
		exit();
	}
	
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
?>
<?php
	if (isset($_POST['rm_orders'])) # Check whether the form has been submitted
	{	
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
			
			#check for valid admin password and whether the person isn't adding money to its own account
			$query0 = "SELECT * FROM users WHERE user_id='$_SESSION[userid]' AND password=md5('$p')";
			$result = mysql_query($query0) or trigger_error("Error while trying to access database");
			
			if (mysql_affected_rows() == 1) 
			{	
				$query = "DELETE FROM bestellingen";
				$result = mysql_query($query) or trigger_error("Error while trying to access database". mysql_error());
				
				$query = "DELETE FROM bestellingen_producten";
				$result = mysql_query($query) or trigger_error("Error while trying to access database");
			}
		} else {
			$error .= "please enter a valid password!<br>";
		}
		
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
		
