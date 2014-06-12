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
			exit(0);
		}
	}
?>


<!DOCTYE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="./cssstylesheets/logo.gif">
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitemselected" onClick="window.location = 'patat.php';">
  	         		Patat
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = 'snacks.php';">
  	         		Snacks
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = 'burgers.php';">
  	         		Burgers
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = 'dranken.php';">
  	         		Dranken
  	        	 </td>
  	         	<td id="menuitem" onClick="window.location = '../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
		
		<div id="main">
        	<?php 
				$query0 = "SELECT folder_id FROM folders WHERE folder_naam='patat'";
				$result = mysql_query($query0) or trigger_error("Error while trying to access database");
				$folderidrow = mysql_fetch_array($result, MYSQL_NUM);
				mysql_free_result($result);
				$folderid = $folderidrow[0];
				
				if (mysql_affected_rows() == 1)
				{
					# Init array's for later use
					$basisproductids = array();
					
					$productnames = array();
					$productprices = array();

					$tempproductname = array();
					$tempproductprice = array();
					
					# Get the base_product id's
					$query1 = "SELECT basis_product_id FROM basis_product WHERE folder_id='$folderid'";
					$result = mysql_query($query1) or trigger_error("Error while trying to access database");
					
					while($arrayvalue = mysql_fetch_array($result)){
						array_push($basisproductids, $arrayvalue[0]);
					}	
					mysql_free_result($result);
					
					for ($i = 0; $i < sizeof($basisproductids); $i ++) 
					{
						$tempbasisid = $basisproductids[$i];
					
						$query2 = "SELECT sub_naam FROM sub_products WHERE basis_product_id='$tempbasisid'";
						$result = mysql_query($query2) or trigger_error("Error while trying to access database");
						while($row = mysql_fetch_array($result)){
							array_push($tempproductname, $row[0]);	
						}
						mysql_free_result($result);
						$productnames = array_merge($productnames, $tempproductname);
				
						$query3 = "SELECT prijs FROM sub_products WHERE basis_product_id='$tempbasisid'";
						$result = mysql_query($query3) or trigger_error("Error while trying to access database");
						while($row = mysql_fetch_array($result)){
							array_push($tempproductprice, $row[0]);	
						}
						mysql_free_result($result);
						$productprices = array_merge($productprices, $tempproductprice);
					}
				
					echo "<table id=producttable>
                    <tr id=productfirstrow>
                       	<td>Product</td>
                        <td>Prijs</td>
                    </tr>";
					
            		for($i =0; $i < sizeof($productnames); $i++) {
                		$iprice = number_format((float)$productprices[$i], 2, ',', '');
                		echo "<tr>
							<td id=productlefttd>$productnames[$i]</td>
                			<td id=productrighttd>$iprice</td>
						</tr>"; 
            		}

            		echo "</table>";
        		
				}
				
			?>
        </div>
		
	</body>
</html>
