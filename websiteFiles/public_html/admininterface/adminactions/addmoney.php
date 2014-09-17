<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../../include/configdb.php");	
	
	DEFINE ('MAX_VALUE', 83);
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
	if (isset($_POST['money_add'])) # Check whether the form has been submitted
	{
		# Check for valid email
		if (preg_match ('%^[A-Za-z0-9._\%-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$%', stripslashes(trim($_POST['email']))))
		{
			$e = escape_data($_POST['email']);
			if ($e == $_SESSION[email])
			{
				$e = FALSE;
				$error .= "You cannot write to your own account!<br>";
			}
		} else {
			$e = FALSE;
			$error .= "Please enter a valid email!<br>";
		}
		
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
			$error .= "please enter a valid password!<br>";
		}
		
		# Check for valid subject name
		if (preg_match ('%^[A-Za-z.-]{2,12}$%', stripslashes(trim($_POST['first_name']))))
		{
			$fn = escape_data($_POST['first_name']);
		} else {
			$fn = FALSE;
			$error .= "please enter a valid firstname from the subject<br>";
		}
		
		# Check for valid upgrade value
		if (preg_match ('%^-?[0-9]+[.]{0,1}[0-9]{0,2}$%', stripslashes(trim($_POST['value']))))
		{
			$value = (float) escape_data($_POST['value']);
		} else {
			$value = FALSE;
			$error .= "Please enter a valid monetary value! ex. 1.23<br>";
		}
		
		if ($e && $p && $fn && $value){
			#check for valid admin password and whether the person isn't adding money to its own account
			$query0 = "SELECT token_id FROM users WHERE user_id='$_SESSION[userid]' AND password=md5('$p')";
			$result = mysql_query($query0) or trigger_error("Error while trying to access database");
			
			if (mysql_affected_rows() == 1) 
			{	
				# Check for up to date token
				$currenttoken = mysql_fetch_array($result, MYSQL_NUM);
				if ($currenttoken[0] != $_SESSION[token_id])
				{
					header('Location: /login/logout.php');
					mysql_close();
					exit();
				}		
					
				$query1 = "SELECT * FROM users WHERE email='$e' AND first_name='$fn'";
				$result = mysql_query($query1) or trigger_error("Error while trying to access database");
				if (mysql_affected_rows() == 1) 
				{	
					$row = mysql_fetch_array($result, MYSQL_NUM);
					mysql_free_result($result);
					$upgradeuser = $row[0];
					$error .= "Attempting to write: " . $value . " euro, to the account of: " . $row[2] . ", op naam van " . $row[4];
					$currentvalue = (float) $row[6];
					
					# Check whether the value doesn't exceed the maximum
					if($currentvalue + $value <= MAX_VALUE && $currentvalue + $value >= 0){
					
						# Write to transaction log
						$query2 = "INSERT INTO transacties (transactie_id, user_id, datum, hoeveelheid, behandelaar_id) VALUES (NULL, '$upgradeuser', NULL, '$value', '$_SESSION[userid]')";
						$result = mysql_query($query2) or trigger_error("Error while trying to access database");					
						if (mysql_affected_rows() == 1) 
						{
							# add desired value
							$currentvalue += $value;
							
							# Write back new value
							$query3 = "UPDATE users SET saldo='$currentvalue' WHERE email='$e' AND first_name='$fn'";
							$result = mysql_query($query3) or trigger_error("Error while trying to access database");
							if (mysql_affected_rows() == 1) 
							{
								$error .= "Transaction succesful!<br>";
							}
											
						} else {
							$error .= "De transactie kon niet opgeslagen worden! transactie geannuleerd";
						} #end of write to log IF
					} else {
						$error .= "Het bedrag dat u wilt opwaarderen valt buiten de gestelde limieten<br>";
					} #end of Check for value doesn't exceed maximum.	
				} else {
					$error .= "De gegeven gebruiker kon niet worden gevonden (let op -- hoofdlettergevoelig).<br>";
				} # end of select user to upgrade IF				
			} else {
				$error .= "Dit wachtwoord is incorrect!<br>Voer alstublieft uw eigen valide wachtwoord in!<br>";
			} # end of Check admin IF		
		} else {
			$error .= "De transactie was onsuccesvol.";
		} # End of secondary if
		
		# update second token
		$tokenId = rand(10000, 9999999);
		$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
		$result = mysql_query($query4);
		$_SESSION['token_id'] = $tokenId;
				
		session_regenerate_id();
		
		header("Location: /admininterface/adminindex.php?x=$error");
		mysql_close();
		exit();
	}
	mysql_close(); # Close database connection
	exit();

?>
