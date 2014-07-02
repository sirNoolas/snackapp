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
		<link rel="shortcut icon" href="itspixeled.nl/cssstylesheets/logo.gif" />
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT</title>
		<link rel="stylesheet" type="text/css" href="../cssstylesheets/admininterface.css">
	</head>
	
	<body>
		<a href="itspixeled.nl"><header></header></a>
		
		<table id="menu">
  	 		<tr>
  	     		<td id="menuitemselected" onClick="window.location = '../login/mijnsnackit.php';">
  	         		Mijn Snack-IT
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/patat.php';">
  	         		<?php echo $names[0]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/snacks.php';">
  	         		<?php echo $names[1]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/burgers.php';">
  	         		<?php echo $names[2]; ?>
  	         	</td>
  	         	<td id="menuitem" onClick="window.location = '../subpages/dranken.php';">
  	         		<?php echo $names[3]; ?>
  	        	 </td>
  	         	<td id="menuitem" onClick="window.location = '../login/logout.php';">
  	         		Log uit 
  	         	</td>
  			</tr>
  		</table>
  		<table id="adminmenu">
  			<tr>
  				<td id="adminmenuitem" onclick="window.location = 'adminindex.php';">
  					Admin index
  				</td>
  				<td id="adminmenuitemselected" onclick="window.location = 'actionadmin.php';">
  					Admin acties
  				</td>
  				<td id="adminmenuitem" onclick="window.location = 'useradminindex.php';">
  					User index
  				</td>
  			</tr>
  		</table>
  		
  		<!-- END of MENU -->	
		
		<div id="main">
			<br>
			<h3>Beste admin:</h3>
			<p>Alle acties die op deze pagina hebben zeer veel invloed op het systeem.<br> 
			Zorg alstublieft voor correct gebruik, en controleer altijd de ingevoerde gegevens!</p>
		
			<!-- upper left -->	
			<div id="bodyleftdiv"><br /><b>Saldo van een gebruiker opwaarderen</b><br /><br />
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
					<form margin="20px"  action="./adminactions/addmoney.php" method="post" autocomplete="on">
						<input type="text" name="email" size="15" maxlength="30" value="Betreft"/><br>
						<input type="text" name="first_name" size="15" maxlength="12" value="Betreft"/><br>
						<input type="text" name="value" size="15" maxlength="12" value="Hoeveelheid"/><br>
						<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br>
						<input type="submit" name="submit_add" value="Toevoegen"/>
						<input type="hidden" name="money_add" value="TRUE"/>
					</form>
				</div>
				<b><u>LET OP</u>:<i> Deze actie is onomkeerbaar</i></b>
			</div>

			<!-- upper right -->
			<div id="bodyrightdiv"><br /><b>DATABASE wijzigen</b><br /><br />
				<table id=producttable>
	               <tr id=productfirstrow>
    	             	<td>Naam</td>
        	            <td></td>
					</tr>
					<tr>
						<td id=producttd> Page </td>
						<td id=producttd><a href="edit.php?type=page">Wijzig</a> of <a href="add.php?type=page&request=remove">verwijder</a> rij</td>
					</tr>
					<tr>
						<td id=producttd> Folder </td>
						<td id=producttd><a href="edit.php?type=folder">Wijzig</a>, <a href="add.php?type=folder&request=remove">verwijder</a> of <a href="add.php?type=folder&request=add">voeg</a> rij toe</td>
					</tr>
					<tr>
						<td id=producttd> Base_product </td>
						<td id=producttd><a href="edit.php?type=base">Wijzig</a>, <a href="add.php?type=base&request=remove">verwijder</a> of <a href="add.php?type=base&request=add">voeg</a> rij toe</td>
					</tr>
					<tr>
						<td id=producttd> Sub_product </td>
						<td id=producttd><a href="edit.php?type=sub">Wijzig</a>, <a href="add.php?type=sub&request=remove">verwijder</a> of <a href="add.php?type=sub&request=add">voeg</a> rij toe</td>
					</tr>
				</table>						
			</div>

			<!-- lower left -->
			<div id="bodyleftdiv"><br /><b>Een gebruiker verwijderen</b><br><br>
				<div id="internalleft">
					<div id="hightext"><br>
						E-mail: <br>
						Voornaam: <br>
						Admin wachtwoord: <br>
					</div>
					
				</div>
				<div id="internalright">
					<br>
					<form margin="20px"  action="./adminactions/deleteuser.php" method="post" autocomplete="on">
						<input type="text" name="email" size="15" maxlength="30" value="Betreft"/><br>
						<input type="text" name="first_name" size="15" maxlength="12" value="Betreft"/><br>
						<input type="password" name="password" size="15" maxlength="20" value="Wachtwoord"/><br>
						<input type="submit" name="submit_rm" value="Verwijderen"/>
						<input type="hidden" name="rm_user" value="TRUE"/>
					</form>	
				</div><br>
				<b><u>LET OP</u>:<i> Deze actie is onomkeerbaar</i></b>
			</div>

			<!-- lower right -->
			<div id="bodyrightdiv"><br /><b></b><br /><br />
			</div>
        
      </div>
		
	</body>
	<?php
		mysql_close();
		exit();
	?>
</html>
