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
		
		<div id="main">
			<h3>Disclaimer</h3>
			<p>Al het mogelijke is gedaan om de nauwkeurigheid te garanderen van de informatie op deze website en alle andere uitingen
			van Snack-IT (Xantes). Snack-IT (Xantes) geeft geen garantie, impliciet noch expliciet, met betrekking tot de nauwkeurigheid van de informatie, 
			met inbegrip van prijs, productinformatie en productspecificaties.</p>
			<br>
			<p>Snack-IT (Xantes) en zijn partners zijn niet aansprakelijk voor incidentele, gevolg- of speciale schade die het gevolg is van of 
			voortvloeit uit elektronische transmissie of de nauwkeurigheid van de informatie die hierin is opgenomen, zelfs indien Snack-IT (Xantes) 
			op de hoogte is gebracht van de mogelijkheid dat zodanige schade zal ontstaan.</p>
			<br>
			<p>Product- en fabrikantnamen worden uitsluitend gebruikt ter identificatie.</p>
		</div>
		<div id="footer">
			<a href="./disclaimer.php">Disclaimer</a> ----- <a href="./sitemap.php">Sitemap</a><br>
			© Rik Nijhuis, David Vonk, Geert ten Napel, Thijs Werkman, Xantes ICT; 2014
		</div>
	</body>
</html>
