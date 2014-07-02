<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	#open database connection
	require_once("../../include/configdb.php");
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
		}
	}			
?>
<?php
	$query0 = "SELECT page_name FROM pages ORDER BY page_id ASC";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");
	
	$names = array();
	while ($namerow = mysql_fetch_array($result, MYSQL_NUM))
	{
		array_push($names, $namerow[0]);
	}
	mysql_free_result($result);
?>

<!DOCTYE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="itspixeled.nl/cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<a href="../index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = 'patat.php';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitemselected" onClick="window.location = 'snacks.php';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = 'burgers.php';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = 'dranken.php';">
  	         		<?php echo $names[3]; ?>
  	        	 </td>
  	         	<td id="menuitem" onClick="window.location = '../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
		
		<div id="main">
      	<?php
	      	$query0 = "SELECT folder_naam FROM folders WHERE page_id=1";
				$result = mysql_query($query0) or trigger_error("Error while trying to access database");
				
				$names = array();
				while ($namerow = mysql_fetch_array($result, MYSQL_NUM))
				{
					array_push($names, $namerow[0]);
				}
				mysql_free_result($result);
				
      		for ($n = 0; $n < sizeof($names); $n++) # Main For. display's all names for every folder that has a matching pageid
      		{
        			# get the folder ID to look for 
					$query0 = "SELECT folder_id, opened FROM folders WHERE folder_naam='$names[$n]'";
					$result = mysql_query($query0) or trigger_error("Error while trying to access database");
					
					$folderrow = mysql_fetch_array($result, MYSQL_NUM);
					
					mysql_free_result($result);
					$folderid = $folderrow[0];
					$status = $folderrow[1];
					
					# there should be one folder returned:
					if ((mysql_affected_rows() == 1) && $status)
					{
						# Init array's for later use
						$basisproductids = array();
						$productnames = array();
						$productprices = array();
						
						# Get the base_product id's
						$query1 = "SELECT basis_product_id FROM basis_product WHERE folder_id='$folderid'";
						$result = mysql_query($query1) or trigger_error("Error while trying to access database");
						
						while($arrayvalue = mysql_fetch_array($result, MYSQL_NUM)){
							array_push($basisproductids, $arrayvalue[0]);
						}	
						mysql_free_result($result);
						
						# query loop
						for ($i = 0; $i < sizeof($basisproductids); $i ++) 
						{					
							# set th basis ID to look for in query
							$tempbasisid = $basisproductids[$i];
						
							# query for name and price					
							$query2 = "SELECT sub_naam, prijs FROM sub_products WHERE basis_product_id='$tempbasisid' ORDER BY sub_naam ASC";
							$result = mysql_query($query2) or trigger_error("Error while trying to access database");
							
							# store variables
							while($row = mysql_fetch_array($result)){
								array_push($productnames, $row[0]);
								array_push($productprices, $row[1]);	
							}
							mysql_free_result($result);
							
						} # END of query loop
					
						# start to write to write to table
						echo (
						"<table id=producttable>
         	           <tr id=productfirstrow>
         	              	<td>Product</td>
         	               <td>Prijs</td>
         	           </tr>"
						);
						
         	   	for($i = 0; $i < sizeof($productnames); $i++) {
					  		$iprice = number_format((float)$productprices[$i], 2, ',', '');
         	     		echo (
         	   			"<tr>
									<td id=productlefttd>$productnames[$i]</td>
         	     				<td id=productrighttd>$iprice</td>
								</tr>"
							); 
         	  		}
         	  		echo "</table>";
					} else if (((mysql_affected_rows() == 1) && !$status)){
						//echo "<br><h3>Sorry, you can't order!<br>This table is closed for the time being...</h3>If you think this is an error, please contact an admin.";
						
					} else if (mysql_affected_rows() > 1) {
					# log error
					$error .= log_error("More than one row affected after get folderid query");
					
					} else {
						echo "Sorry!<br>Couldn't acces correct database table!<br>Please contact the system administrator...";
					} # END of IF
				} # END of main FOR
				if (sizeof($names) < 1)
				{
					//echo "<br><h3>Sorry, you can't order!<br>This table is closed for the time being...</h3>If you think this is an error, please contact an admin.<br><br><br>";
				}
				mysql_close();
				exit();			
			?>  
      </div>
		
	</body>
</html>
