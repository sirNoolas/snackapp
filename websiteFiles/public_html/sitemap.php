<?php
	#open database connection
	require_once("../include/configdb.php");
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
<!DOCTYPE html>
<html lang="nl">

	<head>
		<link rel="shortcut icon" href="./cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="./cssstylesheets/index.css">
	</head>
	
	<body>
		<a href="./index.php"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitem" onClick="window.location = './login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=0';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=1';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=2';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = './subpages/orderpage.php?id=3';">
  	         		<?php echo $names[3]; ?>
  	        	 </td>
  	         	<td id="menuitem"></td>
  			</tr>
  		</table>
		</div>
		
		<div id="main" >
			<ul>
				<li><b>main</b></li>
				<ul>
					<li><a href="./index.php">Index</a></li>
					<li><a href="./disclaimer.php">disclaimer</a></li>
					<li><a href="./sitemap.php">sitemap</a></li>
				</ul>
				<li><b>login</b></li>
				<ul>
					<li><a href="./login/activate.php">activeren</a></li>
					<li><a href="./login/logout.php">log uit</a></li>
					<li><a href="./login/mijnsnackit.php">Mijn snackit</a></li>
					<li><a href="./login/redirectlogin.php">redirect login</a></li>
				</ul>
				<li><b>bestellingen</b></li>
				<ul>
					<li><a href="./subpages/order.php">Bestellen</a></li>
					<li><a href="./subpages/orderpage.php?id=0">tabblad 1</a></li>
					<li><a href="./subpages/orderpage.php?id=1">tabblad 2</a></li>
					<li><a href="./subpages/orderpage.php?id=2">tabblad 3</a></li>
					<li><a href="./subpages/orderpage.php?id=3">tabblad 4</a></li>
				</ul>
				<li><b>admin</b></li>
				<ul>
					<li><a href="./admininterface/actionadmin.php">admin acties</a></li>
					<li><a href="./admininterface/adminindex.php">admin index</a></li>
					<li><a href="./admininterface/useradminindex.php">user admin index</a></li>
					<li><b>huidige orders uitlezen</b></li>
					<ul>
						<li><a href="./admininterface/adminactions/readorders.php?display=all">orders uitlezen: alles ongesorteerd</a></li>
						<li><a href="./admininterface/adminactions/readorders.php?display=user">orders uitlezen: gesorteerd per gebruiker</a></li>
						<li><a href="./admininterface/adminactions/readorders.php?display=allsorted">orders uitlezen: alles gesorteerd</a></li>
					</ul>
				</ul>
			</ul>
		</div>
		<div id="footer">
			<a href="./disclaimer.php">Disclaimer</a> ----- <a href="./sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Xantes ICT; 2014
		</div>
	</body>
</html>
