<?php
	DEFINE ('DBUSER', 'riknibc53_dbadm');
	DEFINE ('DBPW', '4fiAECNn');
	DEFINE ('DBHOST', 'localhost');
	DEFINE ('DBNAME', 'riknibc53_snackapp');
	
	# Connect to database
	if ($dbc = mysql_connect(DBHOST,DBUSER,DBPW))
	{
		if (!mysql_select_db (DBNAME))
		{
			trigger_error("Could not select the database<br />");
			exit();
		}
	
	} else {
		trigger_error("Could not connect to MySQL<br />");
		exit();
	}
	
	# Delete unwanted data	
	function escape_data($data)
	{
		if (function_exists('mysql_real_escape_string'))
		{
			global $dbc;
			$data = mysql_real_escape_string(trim($data), $dbc);
			$data = strip_tags($data);
			
		} else {
			$data = mysql_escape_string($trim($data));
			$data = strip_tags($data);
		}
		
		return $data;
	}
?>
