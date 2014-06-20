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
	
	# Print provided error messages:
	if (isset($_GET['x']) && isset($_GET['y']))
	{
		$x = $_GET['x'];
		$y = $_GET['y'];
		
		$query = "UPDATE folders SET opened='$y' WHERE folder_id='$x'";
		$result = mysql_query($query) or trigger_error("Error while trying to acces database");
		
		# update second token
		$tokenId = rand(10000, 9999999);
		$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
		$result = mysql_query($query4);
		$_SESSION['token_id'] = $tokenId;
		
		header('Location: http://itspixeled.nl/admininterface/adminindex.php');
		
		mysql_close();
		exit();
	} else if (isset($_POST['edit_row']) && isset($_GET['type'])){
		# Check for valid name
		if (preg_match ('%^[A-Za-z.-]{2,12}$%', stripslashes(trim($_POST['name']))))
		{
			$n = escape_data($_POST['name']);
		} else {
			$n = FALSE;
			$error .= "please enter a valid name for the row you are editing<br>";
		}
		
		$type = escape_data($_GET['type']);
		
		switch ($type) {
			case 'page':
				$type = 'pages';
				$idtype = ''
				break;
			case 'folder':
				$type = 'folders';
				break;
			case 'base':
				$type = 'basis_product';
				break;
			case 'sub':
				$type = 'sub_products';
				break;
			default:
				echo "No match was made!";
		}
						
		# Get all current data TODO: Finish!!!
		$query0 = "SELECT * FROM $type WHERE user_id='$_SESSION[userid]'";
		$result = mysql_query($query0) or trigger_error("Error while trying to access database");
		
		# Check for a valid new name
		if (preg_match ('%^[A-Za-z.-]{2,12}$%', stripslashes(trim($_POST['newname']))))
		{
			$nn = escape_data($_POST['newname']);
		} else if ($_POST != NULL) {
			$nn = FALSE;
			$error .= "please enter a valid new name for the row<br>";
		}
		
		if (isset ($_POST['newid']))
		{
			if (preg_match ('%^[0-9]+$%', stripslashes(trim($_POST['newid']))))
			{
				$nid = (int) escape_data($_POST['newid']);
			} else if ($_POST != NULL) {	
				$nid = FALSE;
				$error .= "please enter a valid new id for the row<br>";
			}
			
			if (isset ($_POST['newprice'])){
				# Check for valid upgrade value
				if (preg_match ('%^[0-9]+[.]{0,1}[0-9]{0,2}$%', stripslashes(trim($_POST['value']))))
				{
					$value = (float) escape_data($_POST['value']);
				} else if ($_POST != NULL) {
					$value = FALSE;
					$error .= "Please enter a valid monetary value! ex. 1.23<br>";
				}
			}
		} # END of ISSET newid
		
		if ($n && $nn && $nid && $value)
		{
		
		}
	} else {
		header('Location: http://itspixeled.nl/admininterface/adminindex.php');
		
		mysql_close();
		exit();
	} # END of MAIN IF
	
?>
