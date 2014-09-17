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
	
	# Fetch given variables
	if (isset($_GET['x']) && isset($_GET['y']))
		{
		$x = escape_data($_GET['x']);
		$y = escape_data($_GET['y']);
		
		$query = "UPDATE folders SET opened='$y' WHERE folder_id='$x'";
		$result = mysql_query($query) or trigger_error("Error while trying to acces database");
		
		# update second token
		$tokenId = rand(10000, 9999999);
		$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
		$result = mysql_query($query4);
		$_SESSION['token_id'] = $tokenId;
		
		header('Location: /admininterface/adminindex.php');
		
		mysql_close();
		exit();
		
	} else if (isset($_POST['edit_row']) && isset($_GET['type'])){
	
		# Check for valid name
		if (preg_match ('%^[A-Za-z.-]{2,12}[\sA-Za-z.-]*$%', stripslashes(trim($_POST['name']))))
			{
			$n = escape_data($_POST['name']);
		} else {
			$n = FALSE;
			$error .= "please enter a valid name for the row you are editing. ";
		}		
		$type = escape_data($_GET['type']);
		
		# init primary type dependant variables
		switch ($type) 
			{
			case 'page':
				$type = pages;
				$idtype = NULL;
				$nametype = page_name;
				$nid = TRUE;
				$value = TRUE;
				break;
			case 'folder':
				$type = folders;
				$idtype = page_id;
				$nametype = folder_naam;
				$value = TRUE;
				break;
			case 'base':
				$type = basis_product;
				$idtype = folder_id;
				$nametype = basis_naam;
				$value = TRUE;
				break;
			case 'sub':
				$type = sub_products;
				$idtype = basis_product_id;
				$nametype = sub_naam;
				break;
			default:
				$error.= "No match was made!";
				$nametype = FALSE;
				$idtype = FALSE;
				$value = FALSE;
				$type = FALSE;
				$nid = FALSE;
				$nn = FALSE;
				$n = FALSE;
				header("Location: /admininterface/adminindex.php?x=$error");
				mysql_close();
				exit();
		}
		
		# Check for a valid new name
		if (preg_match ('%^[A-Za-z.-]{2,12}[\sA-Za-z.-]*$%', stripslashes(trim($_POST['newname']))))
			{
			$nn = escape_data($_POST['newname']);
		} else if ($_POST['newname'] != NULL) {
			$nn = FALSE;
			$error .= "please enter a valid new name for the row. ";
		} else {
			$nn = NULL;
		}
		
		# Check for a new id set -- can be null
		if (isset ($_POST['newid']))
			{
			if (preg_match ('%^[0-9]+$%', stripslashes(trim($_POST['newid']))))
				{
				$nid = (int) escape_data($_POST['newid']);
				
				if ($nid == 0)
					{
					$nid = 'zero';
				}
			} else if ($_POST['newid'] != NULL) {	
				$nid = FALSE;
				$error .= "please enter a valid new id for the row. ";
			} else {
				$nid = NULL;
			}
			
			# look for new price -- can be null
			if (isset ($_POST['newprice']))
					{
				# Check for valid new product value
				if (preg_match ('%^[0-9]+[.]{0,1}[0-9]{0,2}$%', stripslashes(trim($_POST['newprice']))))
						{
					$value = (float) escape_data($_POST['newprice']);
				} else if ($_POST['newprice'] != NULL) {
					$value = FALSE;
					$error .= "Please enter a valid monetary value! ex. 1.23. ";
				} else {
					$value = NULL;
				}
			} else {
				$value = NULL;
			}
			
		} else {
			$nid = NULL;
			$value = NULL;
		}# END of ISSET newid
		
		# Check for valid password
		if (preg_match ('%^[A-Za-z0-9]{4,20}$%', stripslashes(trim($_POST['password']))))
		{
			$p = escape_data($_POST['password']);
		} else {
			$p = FALSE;
			$error .= "please enter a valid password!. ";
		}
		
		if ($p && $n && ($nn || $nn == NULL) && ($nid || $nid == NULL) && ($value || $value == NULL) && ($idtype || $idtype == NULL) && $nametype)
			{
			$query1 = "SELECT * FROM users WHERE user_id = '$_SESSION[userid]' AND password = md5('$p')";
			$result = mysql_query($query1) or trigger_error("Error while trying to access database");
			
			if (mysql_affected_rows() == 1)
				{
			
				# Prepare custom query
				$mainquery = "UPDATE $type SET ";
				if ($nn != NULL) 
					{
					$mainquery .= "$nametype='$nn'"; 
					if ($nid != NULL && $idtype != NULL)
						{
						$mainquery .= ", ";
					} else {
						$mainquery .= " ";
					}
				}
				if ($nid != NULL && $idtype != NULL) 
					{
					if ($nid == 'zero')
						{
						$mainquery .= "$idtype=0";
					} else {
						$mainquery .= "$idtype=$nid";
					}
					if ($value != NULL)
						{
						$mainquery .= ", ";
					} else {
						$mainquery .= " ";
					}
				}
				if ($value != NULL) { $mainquery .= "prijs='$value' "; }			
				$mainquery .= "WHERE $nametype='$n'";

				#query
				$result = mysql_query($mainquery) or trigger_error("Error while trying to access database" . mysql_error());
				
				# update second token
				$tokenId = rand(10000, 9999999);
				$query4 = "UPDATE users SET token_id = $tokenId WHERE user_id = '$_SESSION[userid]'";
				$result = mysql_query($query4);
				$_SESSION['token_id'] = $tokenId;
				
				$error .= "succesful! " . mysql_affected_rows() . " row was succesfully edited...";
				
				header("Location: /admininterface/adminindex.php?x=$error");
			
			} else {
				$error .= "Your password was incorrect!!!";
				header("Location: /admininterface/adminindex.php?x=$error");	
			} # END of AFFECTED_ROWS IF		
		} else {
			header("Location: /admininterface/adminindex.php?x=$error");
		} # END of CHECK ALL VALUES 
	} # END of MAIN IF
	mysql_close();
	exit();
?>
