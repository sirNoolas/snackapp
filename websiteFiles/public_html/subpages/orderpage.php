<?php
	
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	#open database connection
	require_once("../../include/configdb.php");
	
	#prepare $_SESSION['order']
	if ( ! is_array($_SESSION['productID']))
	{
    	$_SESSION['productID'] = array();
	}
	
	if ( ! is_array($_SESSION['productName']))
	{
    	$_SESSION['productName'] = array();
	}
	
	if ( ! is_array($_SESSION['productPrice']))
	{
    	$_SESSION['productPrice'] = array();
	}
?>
<?php
	# Check whether the user is logged in
	if (!isset($_SESSION[userid]))
	{
		header('Location: /login/redirectlogin.php');
		exit();
	} else if (isset($_SESSION[active])) {
		if ($_SESSION[active] != NULL)
		{
			header('Location: /login/activate.php');
		}
	}			
?>
<?php 
	# Fetch the id-type --> 0-3
	if (isset($_GET['id']) && preg_match ('%^[0-3]{1}$%', (int) stripslashes(trim($id))))
		{
		$id = (int) escape_data($_GET['id']);
	} else {
		$id = NULL;
		echo "Er is een fout in de id.";
	}
?>
<?php
	# Get from the db the set names for the pages
	$query0 = "SELECT page_name FROM pages ORDER BY page_id ASC";
	$result = mysql_query($query0) or trigger_error("Error while trying to access database");
	
	$names = array();
	while ($namerow = mysql_fetch_array($result, MYSQL_NUM))
	{
		array_push($names, $namerow[0]);
	}
	mysql_free_result($result);
	
?>
<!DOCTYPE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="../cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/general.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script language="JavaScript" src="md5.js"></script>
        
        <script>
			var order = new Array();
			var orderString = new String();
			
			// 
			function clearCart() {
				$.ajax({
				  type: "POST",
				  url: "deletecart.php",
				  success: function() {
					  alert("Done");
					  window.order = new Array();
					  location.reload();
				  }
			   });
			}
		
			function sendOrder() {
				var md5OrderString = calcMD5(orderString);
				
			  window.location.replace("order.php?hash=" + md5OrderString);

			}
			
			function addToCard(productID, productName, productPrice) {	
				window.order[window.order.length] = productID;
				window.orderString = window.order.join();
				
				$.ajax({
				  type: "POST",
				  url: "tocard.php",
				  data: { "productID" : productID, "productName" : productName, "productPrice" : productPrice},
				  success: function() {
				  }
			   });
			}
			
			function addSessionRow(productID, productName, productPrice) {
				alert("Lele");
				var table = document.getElementById('cardtable');
				var row = table.insertRow(-1);
				var cell1 = row.insertCell(0);
				cell1.className ='cardlefttd';
				var cell2 = row.insertCell(1);
				cell2.className ='cardcentretd';
				var cell3 = row.insertCell(2);
				cell3.className ='cardrighttd';
			
				cell1.innerHTML = productName;
				cell2.innerHTML = "€" + parseFloat(Math.round(productPrice * 100) / 100).toFixed(2);;              
				cell3.innerHTML = 1;				
			}
			
			function addToTable(x, y){
				var productName = x;
				var productPrice = y;
				
				var table = document.getElementById('cardtable');
				var rowCount = table.rows.length;
				var alreadyCreated = 0;
			
				for(var i = 0; i < rowCount && alreadyCreated == 0; i++) {
					var currentRow = table.rows[i];
					var text = currentRow.cells[0].innerText;
													
					if(text == "Product"){
					}
					else if(text == productName) {
						var currentValue = parseInt(currentRow.cells[2].innerText);
						var newValue = currentValue + 1;
						currentRow.cells[2].innerText = newValue;
						alreadyCreated = 1;
					}
				}
				
				if(alreadyCreated == 0){
						var row = table.insertRow(-1);
						var cell1 = row.insertCell(0);
						cell1.className ='cardlefttd';
						var cell2 = row.insertCell(1);
						cell2.className ='cardcentretd';
						var cell3 = row.insertCell(2);
						cell3.className ='cardrighttd';
			
						cell1.innerHTML = productName;
						cell2.innerHTML = "€" + parseFloat(Math.round(productPrice * 100) / 100).toFixed(2);;              
						cell3.innerHTML = 1;
						
						alreadyCreated = 1;
				}
			}
			window.onload = function() {
				var form = document.getElementById('orderform');
				form.addEventListener('submit', function(){
					var arrayField = document.getElementById('array');
					for (var i = 0; i < order.length; i++) {
						arrayField.value += order[i];
						arrayField.value += "-";
					}
				});
			}
			

		</script>
	</head>
	
	<body>
		<a href="../index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<?php
  	         		switch ($id) 
  	         			{
  	         			case 0:
  	         				?>
  	       		  				<td id='menuitemselected' onClick="window.location = 'orderpage.php?id=0';"> <?php echo "$names[0]"; ?> </td>
  	         					<td id='menuitem' onClick="window.location = 'orderpage.php?id=1';"> <?php echo "$names[1]"; ?> </td>
		  	         			<td id='menuitem' onClick="window.location = 'orderpage.php?id=2';"> <?php echo "$names[2]"; ?> </td>
  	   		      			<td id='menuitem' onClick="window.location = 'orderpage.php?id=3';"> <?php echo "$names[3]"; ?> </td>
  	   		      		<?php
  	   		      		break;
  	   		      	case 1:
  	         				?>
  	       		  				<td id='menuitem' onClick="window.location = 'orderpage.php?id=0';"> <?php echo "$names[0]"; ?> </td>
  	         					<td id='menuitemselected' onClick="window.location = 'orderpage.php?id=1';"> <?php echo "$names[1]"; ?> </td>
		  	         			<td id='menuitem' onClick="window.location = 'orderpage.php?id=2';"> <?php echo "$names[2]"; ?> </td>
  	   		      			<td id='menuitem' onClick="window.location = 'orderpage.php?id=3';"> <?php echo "$names[3]"; ?> </td>
  	   		      		<?php
  	   		      		break;
  	   		      	case 2:
  	         				?>
  	       		  				<td id='menuitem' onClick="window.location = 'orderpage.php?id=0';"> <?php echo "$names[0]"; ?> </td>
  	         					<td id='menuitem' onClick="window.location = 'orderpage.php?id=1';"> <?php echo "$names[1]"; ?> </td>
		  	         			<td id='menuitemselected' onClick="window.location = 'orderpage.php?id=2';"> <?php echo "$names[2]"; ?> </td>
  	   		      			<td id='menuitem' onClick="window.location = 'orderpage.php?id=3';"> <?php echo "$names[3]"; ?> </td>
  	   		      		<?php
  	   		      		break;
  	   		      	case 3:
  	         				?>
  	       		  				<td id='menuitem' onClick="window.location = 'orderpage.php?id=0';"> <?php echo "$names[0]"; ?> </td>
  	         					<td id='menuitem' onClick="window.location = 'orderpage.php?id=1';"> <?php echo "$names[1]"; ?> </td>
		  	         			<td id='menuitem' onClick="window.location = 'orderpage.php?id=2';"> <?php echo "$names[2]"; ?> </td>
  	   		      			<td id='menuitemselected' onClick="window.location = 'orderpage.php?id=3';"> <?php echo "$names[3]"; ?> </td>
  	   		      		<?php
  	   		      		break;
  	   		      	default:
  	   		      		echo "Fout id";
  	   		      		break;
  	   		      }
  	       	 	 ?>
  	         	<td id="menuitem" onClick="window.location = '../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
		
		<div id="main">
      	<?php
		
		
	      	$query0 = "SELECT folder_naam FROM folders WHERE page_id=$id";
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
						$productids = array();
						
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
							$query2 = "SELECT sub_naam, prijs, product_id FROM sub_products WHERE basis_product_id='$tempbasisid' ORDER BY sub_naam ASC";
							$result = mysql_query($query2) or trigger_error("Error while trying to access database");
							
							# store variables
							while($row = mysql_fetch_array($result)){
								array_push($productnames, $row[0]);
								array_push($productprices, $row[1]);
								array_push($productids, $row[2]);	
							}
							mysql_free_result($result);
							
						} # END of query loop
					
						# start to write to write to table
						echo (
							"<table id=producttable>
         	           			<tr id=productfirstrow>
									<td id='productlefttd' style='background-color:#d22b44'>Product</td>
									<td id='productrighttd' style='background-color:#d22b44'>Prijs</td>
         	           			</tr>"
						);
						
						for($i = 0; $i < sizeof($productnames); $i++) {
							$iprice = number_format((float)$productprices[$i], 2, '.', '');
						
							echo (
								"<tr id='producttablerow' onclick=\"addToTable('{$productnames[$i]}', $iprice);addToCard('{$productids[$i]}', '{$productnames[$i]}', $iprice);\">
								<td id='productlefttd'>$productnames[$i]</td>
								<td id='productrighttd'>€ $iprice</td>
								</tr>"
							); 
						} # End of sizeof FOR
							
         	  			echo "</table>";
						
						echo "<table id='cardtable'>
						<tr id='cardfirstrow'>
							<td id='cardlefttd' style='background-color:#d22b44'>Product</td>
							<td id='cardcentretd' style='background-color:#d22b44'>Prijs</td>
							<td id='cardrighttd' style='background-color:#d22b44'>Aantal</td>
						</tr>";
						
						$productID = $_SESSION['productID'];
						$productName = $_SESSION['productName'];
						$productPrice = $_SESSION['productPrice'];
						for($i = 0; $i < sizeof($productID); $i++) {
							echo "<script type='text/javascript'>
							addToTable('$productName[$i]', '$productPrice[$i]');
							</script>";
						}
							
						echo "</table>";	
						
					} else if (((mysql_affected_rows() == 1) && !$status)){
						
						echo "<br><h3>Sorry, you can't order!<br>This table is closed for the time being...</h3>";
						echo "If you think this is an error, please contact an admin.";
						
					} else if (mysql_affected_rows() > 1) {
						# log error
						$error .= log_error("More than one row affected after get folderid query");
					
					} else {
						echo "Sorry!<br>Couldn't acces correct database table!<br>Please contact the system administrator...";
					
					} # END of IF
					
				} # END of main FOR
				
				if (sizeof($names) < 1)
				{
					echo "<br><h3>Sorry, you can't order!<br>This table is closed for the time being...</h3>";
					echo "If you think this is an error, please contact an admin.<br><br><br>";
				}
				
				mysql_close();
			?> 
            
            <div class="clickbox" onclick="sendOrder()">Bestelling versturen</div>
            <div class="clickbox" onclick="clearCart()">Bestelling verwijderen</div>
            
		</div> 
        
        
		<div id="footer">
			<a href="../disclaimer.php">Disclaimer</a> ----- <a href="../sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Xantes ICT; 2014
		</div>	
	</body>
</html>
