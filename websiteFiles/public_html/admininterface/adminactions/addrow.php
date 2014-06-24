<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../../include/configdb.php");
?>
<?php	
	if (!isset($_SESSION[userid]))
		{
		header('Location: http://itspixeled.nl/login/redirectlogin.php');
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
			{
			header('Location: http://itspixeled.nl/login/activate.php');
			exit();
		}
	}
	
	if ($_SESSION['admin_value'] != 1)
		{
		header('Location:	http://www.itspixeled.nl/login/mijnsnackit.php');	
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
			header('Location: http://itspixeled.nl/login/logout.php');
			mysql_close();
			exit();
		}
	}
	
	# Check wether there are variables passed through the header
	if (isset($_POST['add_row']) && isset($_GET['type']))
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
		
		# Check for a valid id	
		if (preg_match ('%^[0-9]+$%', stripslashes(trim($_POST['id']))))
			{
			$id = (int) escape_data($_POST['id']);
		} else {	
			$id = FALSE;
			$error .= "Voer alstublieft een goed id in<br>";
		}
			
		if (isset ($_POST['price']))
			{
			# Check for valid product price
			if (preg_match ('%^[0-9]+[.]{0,1}[0-9]{0,2}$%', stripslashes(trim($_POST['price']))))
					{
				$value = (float) escape_data($_POST['price']);
			} else {
				$value = wrong;
				$error .= "Voer alstublieft een valide waarde in! bijv. 1.23<br>";
			}
		} else {
			$value = NULL;
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
				$idtype = page_id;
				$nametype = folder_naam;
				break;
			case 'base':
				$type = basis_product;
				$idtype = folder_id;
				$nametype = basis_naam;
				break;
			case 'sub':
				$type = sub_products;
				$idtype = basis_product_id;
				$nametype = sub_naam;
				break;
			default:
				$error.= "Dit soort rij word niet ondersteund!";
				$nametype = FALSE;
				$idtype = FALSE;
				$value = FALSE;
				$type = FALSE;
				$id = FALSE;
				$n = FALSE;
				header("Location: http://itspixeled.nl/admininterface/adminindex.php?x=$error");
				mysql_close();
				exit();
		}
		
		# bring it all into practise
		if ($p && $n && ($value || $value == NULL) && $nametype && $idtype && ($value != wrong))
			{
			$query1 = "SELECT * FROM users WHERE user_id = '$_SESSION[userid]' AND password = md5('$p')";
			$result = mysql_query($query1) or trigger_error("Error while trying to access database<br>");
			
			if (mysql_affected_rows() == 1)
				{
			
				# Prepare custom query
				$mainquery = "INSERT INTO $type ($nametype, $idtype";
				
				# Look vor value
				if ($value != NULL) 
					{
					$mainquery .= ", prijs) VALUES ("; 
				} else {
					$mainquery .= ") VALUES (";
				}
				
				# standard values
				$mainquery .= "'$n', $id";
				
				# if value isset
				if ($value != NULL) 
					{
					$mainquery .= ", $value)"; 
				} else {
					$mainquery .= ")";
				}	
				
				#query
				$result = mysql_query($mainquery) or trigger_error("Error while trying to access database<br>" . mysql_error());
				
				# update second token
				$tokenId = rand(10000, 9999999);
				$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query4);
				$_SESSION['token_id'] = $tokenId;
				
				$error .= "succesvol!<br>" . mysql_affected_rows() . " De rij is succesvol toegevoegd...";
				
				header("Location: http://itspixeled.nl/admininterface/adminindex.php?x=$error");
			
			} else {
				$error .= "Dit wachtwoord komt niet overeen met uw eigen!";
				header("Location: http://itspixeled.nl/admininterface/adminindex.php?x=$error");	
			} # END of AFFECTED_ROWS IF		
		} else {
			header("Location: http://itspixeled.nl/admininterface/adminindex.php?x=$error");
		} # END of CHECK ALL VALUES 
	} # END of MAIN IF
	mysql_close();
	exit();
?>
