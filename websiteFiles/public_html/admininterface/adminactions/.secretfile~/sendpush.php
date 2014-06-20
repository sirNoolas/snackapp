<?php
	# open database connection and extra security
	require_once("../../../../include/configdb.php");	
?>
<?php
	if (isset($_POST[push]))
	{
		$body = $_POST[text];
		echo $body;
		$subject = $_POST[subject];
		# query for data
		$query0 = "SELECT email FROM users";
		$result = mysql_query($query0) or trigger_error("Error while trying to access database". mysql_error());
		# print data to screen
		while($row = mysql_fetch_array($result)){
			mail($row[0], $subject, $body, 'From:no_reply@itspixeled.nl');
		}
	}
	mysql_close();
	exit();
?>
