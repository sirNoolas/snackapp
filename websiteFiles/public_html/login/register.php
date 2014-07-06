<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
	require_once("../../include/configdb.php");	
?>
<?php
	if (isset($_POST['register_submitted'])) # Check whether the form has been submitted
	{ 
		# Check for valid email
		if (preg_match ('%^[A-Za-z0-9._\%-]+@(xantes|itspixeled)\.nl$%', stripslashes(trim($_POST['email']))))
		{
			$e = escape_data($_POST['email']);
		} else {
			$e = FALSE;
			$error .= "Please enter a valid email!<br>";
		}
		
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			# Check for match with control password
			if (escape_data($_POST['password']) == escape_data($_POST['controlpassword']))
			{
				$p = escape_data($_POST['password']);
			} else {
				$p = FALSE;
				$error .= "The passwords you provided didn't match!<br>";	
			}	
		} else {
			$p = FALSE;
			$error .= "please enter a valid password!<br>Make sure the password is between 4 and 20 characters!<br>";
		}
		
		# Check for valid names
		if (preg_match ('%^[A-Za-z.-]{2,12}$%', stripslashes(trim($_POST['first_name']))) && preg_match('%^[A-Za-z.-]{2,12}$%', stripslashes(trim($_POST['last_name']))))
		{
			$fn = escape_data($_POST['first_name']);
			$ln = escape_data($_POST['last_name']);
		} else {
			$fn = $ln = FALSE;
			$error .= "please enter a valid first- and last name!<br>";
		}
		
		# Check whether the email is not allready in the database:
		$query1 = "SELECT * FROM users WHERE email='$e'";
		$result = mysql_query($query1) or trigger_error("Error while trying to access database");
			
		if (mysql_affected_rows() != 0) 
		{
			$unique_email = FALSE;
			$error .= "This email is already in use!<br>";
		} else {
			$unique_email = 1;
		}
		mysql_free_result($result);			
		
		# Start writing to database
		if ($e && $p && $fn && $ln && $unique_email)
		{																										
			$accescode = md5(uniqid(rand(),true));
	
			$query = "INSERT INTO users (user_id, token_id, email, password, first_name, last_name, saldo, admin_value, active) VALUES (NULL, NULL, '$e', md5('$p'), '$fn', '$ln', 0, 0, '$accescode')";
			$result = mysql_query($query) or trigger_error("Error while trying to access database".mysql_error());
	
			if (mysql_affected_rows() == 1)
			{
				$body = "Bedankt voor het registreren bij Xantes Snack-IT. Om uw account te activeren verzoeken wij u op deze link te klikken: \n\n";
				$body .= "http://www.itspixeled.nl/login/activate.php?x=" . mysql_insert_id() . "&y=$accescode" . "\nMet vriendelijke groet,\nSystem administrator van Snack-IT";
				mail($_POST['email'], 'Registratie bevestiging', $body, 'From:no_reply@itspixeled.nl');
				$_SESSION['active'] = $accescode;
				
				#create second token
				$tokenId = rand(10000, 9999999);
				$query2 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query2) or trigger_error("Error while trying to access database".mysql_error());
				$_SESSION['token_id'] = $tokenId;
				
				session_regenerate_id();
				# Print result to user:
				$error .= "<h2>Uw registratie was succesvol!<h2>";
				$error .= "<h3>Er is een e-mail verzonden naar het opgegeven adres met een activatie mail.<br>";
				$error .= "Om gebruik te maken van Snack-IT moet u uw account activeren; klik hiervoor op de link in de mail.<h3>";
			
			} else if (mysql_affected_rows() > 1){
				# log error
				$error .= log_error("More than one row affected after insert-user query");				
			} #end of log error
			
			header("Location: /index.php?x=$error");
			mysql_close(); # Close database connection
			exit();
		} else {
			$error .= "Your registration was not succesful!";
			header("Location: /index.php?x=$error");
			mysql_close(); # Close database connection
			exit();
		}
		$error .= "Your registration was not succesful!";
		header("Location: /index.php?x=$error");
		mysql_close(); # Close database connection
		exit();
	}
?>
