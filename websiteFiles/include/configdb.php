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
			exit();
		}# End of mysql_select_db IF
	
	} else {
		# If unable to connect to mysql
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
?>
