<?php
	DEFINE ('DBUSER', 'riknibc53_dbadm');
	DEFINE ('DBPW', 'I6ewG8tX');
	DEFINE ('DBHOST', 'localhost');
	DEFINE ('DBNAME', 'riknibc53_snackapp');
	
	# Connect to database
	if ($dbc = mysql_connect(DBHOST,DBUSER,DBPW))
	{
		if (!mysql_select_db (DBNAME))
		{
			# If it can't select the database:
			trigger_error("Could not select database!<br>");
			exit();
		}# End of mysql_select_db IF
	
	} else {
		# If unable to connect to mysql
		trigger_error("Could not connect to mysql!<br>");
		exit();
	}
	
	# Function for escaping the data
	function escape_data($data)
	{
		/* Adress Magic Quotes (a program that automaticly escapes quote marks entered into php code. This can cause errors, and the following code makes sure all escape characters are un-escaped)
		*/
		if (ini_get('magic_quotes_gpc'))
		{
			$data = stripslashes($data);
		}
		
		if (function_exists('mysql_real_escape_string'))
		{
			global $dbc; #Need the connection
			$data = mysql_real_escape_string(trim($data), $dbc);
			$data = strip_tags($data);
			
		} else {
			$data = mysql_escape_string($trim($data));
			$data = strip_tags($data);
		}
		
		return $data;
	}
	
	# if a query fails
	function log_error ($errortolog) 
	{
		# Log error to email (no permission to log to server D: )
		# Get client ip adress
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		# Get current time and filelocation
		$date = date('Y-m-d H:i:s');
		$location = $_SERVER['PHP_SELF'];
		$errortolog .= "\nIn '$location'\nTriggered by ip: $ip\nOn: $date";
			
		mail("errors@itspixeled.nl", 'itspixeled Error_log message', $errortolog, "From:no_reply@itspixeled.nl");
		
		return "Ben jij niet toevallig een hacker?<br>";
	}
?>
