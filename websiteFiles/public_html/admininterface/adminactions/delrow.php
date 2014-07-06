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
	
	if ($_SESSION['admin_value'] != 1)
		{
		header('Location:	/login/mijnsnackit.php');	
		exit();
	}
?>
<?php
	# Check wether the session is valid through a random token
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
	
	# Check wether there are variables passed through the header
	if (isset($_POST['del_row']) && isset($_GET['type']))
	{
		# Get the type of row we are dealing with
		$type = escape_data($_GET['type']);
		
		# Check for valid name
		if (preg_match ('%^[A-Za-z.-]{2,12}[\sA-Za-z.-]*$%', stripslashes(trim($_POST['name']))))
			{
			$n = escape_data($_POST['name']);
		} else {
			$n = FALSE;
			$error .= "Voer alstublieft een correcte naam in bestaande uit letters, punten, streepjes, en spaties.<br>";
		}		
				
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
			$error .= "Dit wachtwoord was incorrect!<br>";
		}		
		
		# Init standart type dependant variables
		switch ($type) 
			{
			case 'folder':
				$type = folders;
				$nametype = folder_naam;
				break;
			case 'base':
				$type = basis_product;
				$nametype = basis_naam;
				break;
			case 'sub':
				$type = sub_products;
				$nametype = sub_naam;
				break;
			default:
				$error.= "Dit soort rij word niet ondersteund!";
				$nametype = FALSE;
				$type = FALSE;
				$n = FALSE;
				header("Location: /admininterface/adminindex.php?x=$error");
				mysql_close();
				exit();
		}
		
		# bring it all into practise
		if ($p && $n && $nametype)
			{
			$query1 = "SELECT * FROM users WHERE user_id = '$_SESSION[userid]' AND password = md5('$p')";
			$result = mysql_query($query1) or trigger_error("Error while trying to access database<br>");
			
			if (mysql_affected_rows() == 1)
				{
			
				# Query
				$query = "DELETE FROM $type WHERE $nametype = $n LIMIT 1";
				$result = mysql_query($query) or trigger_error("Error while trying to access database<br>");
				
				# update second token
				$tokenId = rand(10000, 9999999);
				$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query4);
				$_SESSION['token_id'] = $tokenId;
				
				$error .= "succesvol!<br>" . mysql_affected_rows() . " De rij is succesvol verwijdert...";
				
				header("Location: /admininterface/adminindex.php?x=$error");
			
			} else {
				$error .= "Dit wachtwoord komt niet overeen met uw eigen!";
				header("Location: /admininterface/adminindex.php?x=$error");	
			} # END of AFFECTED_ROWS IF		
		} else {
			header("Location: /admininterface/adminindex.php?x=$error");
		} # END of CHECK ALL VALUES 
	} # END of MAIN IF
	mysql_close();
	exit();
?>
