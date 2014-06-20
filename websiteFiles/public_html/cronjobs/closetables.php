<?php
	# open database connection and extra security
	require_once("../../include/configdb.php");
?>
<?php
	# query for data
	$query0 = "SELECT * FROM folders";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");

	# print data to screen
	while($row = mysql_fetch_array($result)){
		$query = "UPDATE folders SET opened=0 WHERE folder_id='$row[0]'";
		$result1 = mysql_query($query) or trigger_error("Error while trying to acces database");
	}
	mysql_close();
	exit();
?>
