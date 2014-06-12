<?php
	# initialize session and request cookies -> for keeping the user logged in everywhere on the website
	session_start();
	# open database connection and extra security
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
			exit();
		}
	}
	
	if ($_SESSION['admin_value'] != 1)
	{
		header('Location:	http://www.itspixeled.nl/login/mijnsnackit.php');	
		exit();
	}
?>

<!DOCTYE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="./cssstylesheets/logo.gif"
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/admininterface.css">
	</head>
	
	<body>
		<header>
		</header>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitemselected" onclick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/patat.php';">
  	         		Patat
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/snacks.php';">
  	         		Snacks
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/burgers.php';">
  	         		Burgers
  	         	</td>
  	         	<td id="menuitem" onclick="window.location = '../subpages/dranken.php';">
  	         		Dranken
  	        	 </td>
  	         	<td id="menuitem" onclick="window.location = '../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
  		<table id="adminmenu">
  			<tr>
  				<td id="adminmenuitemselected" onclick="window.location = 'adminindex.php';">
  					Admin index
  				</td>
  				<td id="adminmenuitem" onclick="window.location = 'useradminindex.php';">
  					User index
  				</td>
  			</tr>
  		</table>
  				
		
		<div id="main">
			<div id="bodyleftdiv"><br /><b>Saldo van een gebruiker opwaarderen</b>
				<div id="internalleft">
					<div id="hightext"><br>
						E-mail: <br>
						Voornaam: <br>
						Opwaarderen met: <br>
						Admin wachtwoord: <br>
					</div>
					
				</div>
				<div id="internalright">
					<br>
					<form margin="20px"  action="./addmoney.php" method="post" autocomplete="on">
						<input type="text" name="email" size="15" maxlength="30" value="Betreft"/><br>
						<input type="text" name="first_name" size="15" maxlength="12" value="Betreft"/><br>
						<input type="text" name="value" size="15" maxlength="12" value="Hoeveelheid"/><br>
						<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br>
						<input type="submit" name="submit_add" value="Toevoegen"/>
						<input type="hidden" name="money_add" value="TRUE"/>
					</form>
				</div>
			</div>

			<div id="bodyrightdiv"><br />
			<b>Uw bestelling voor [datum vrijdag]</b><br /><br />
			</div>

			<div id="bodyleftdiv"><br /><b>Transacties</b>
				<?php 
					$query0 = "SELECT * FROM transacties";
					$result = mysql_query($query0) or trigger_error("Error while trying to access database");
				
					# Init array's for later use
					$basisproductids = array();
					
					$productnames = array();
					$productprices = array();

					$tempproductname = array();
					$tempproductprice = array();
					
					# Get the base_product id's
					$query1 = "SELECT basis_product_id FROM basis_product WHERE folder_id='$folderid'";
					$result = mysql_query($query1) or trigger_error("Error while trying to access database");
					
					#init table
					echo "<table id=producttable>
                    <tr id=productfirstrow>
                       	<td>User ID</td>
                        <td>Datum</td>
						<td>Hoeveelheid</td>
						<td>Behandelaar ID</td>
                    </tr>";
					
					while($arrayvalue = mysql_fetch_array($result)){
						echo (
							"<tr>
								<td id=productlefttd>$arrayvalue[0]</td>
								<td id=productrighttd>$arrayvalue[1]</td>
								<td id=productlefttd>$arrayvalue[2]</td>
								<td id=productlefttd>$arrayvalue[3]</td>
							</tr>"
						); 
					}	
					mysql_free_result($result);
				
					
					
            		

            		echo "</table>";
				
			?>
			</div>

			<div id="bodyrightdiv"><br /><b>System output</b><br /><br />
				<div id="errordiv">
					<?php
						# Print provided error messages:
						if (isset($_GET['x']))
						{
							$x = $_GET['x'];
							echo "<br>$x";
						}
					?>
				</div>
			</div>
        
      </div>
		
	</body>
</html>
