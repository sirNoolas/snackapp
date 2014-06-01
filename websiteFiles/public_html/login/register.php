<!DOCTYE html>
<html lang="nl">

	<head>
		<meta charset="UTF-8">
		<title>Xantes | Snack-IT -- registreren</title>
		<link rel="stylesheet" type="text/css" href="./cssstylesheets/custinterface.css">
	</head>
	
	<body>
		<header>
			<table id="menu">
   	 		<tr>
   	     		<td id="menuitem">
   	         	Menu 1
   	         </td>
   	         <td id="menuitem">
   	         	Menu 2
   	         </td>
   	         <td id="menuitem">
   	         	Menu 3
   	         </td>
   	         <td id="menuitem">
   	         	Menu 4
   	         </td>
   	         <td id="menuitem">
   	         	Menu 5
   	         </td>
   	         <td id="menuitem">
   	         	Menu 6
   	         </td>
   			</tr>
   		</table>
		</header>
		
		<main>
			<?php
				if (isset($_POST['submitted']))
				{ # Handle form
					if (eregi('^[[:alpha:]\.\'\-]{2,15}$', stripslashes(trim($_POST['first_name']))))
					{ 
						# escape_data eliminates any extra spaces from the string
						$fn = escape_data($_POST['first_name']);
					} else {
						$fn = FALSE;
						# TODO: replace with javascript for immediat error alerting
						echo '<p>Vul alstublieft uw voornaam in!</p';
					}
					
					if (eregi('^[[:alpha:]\.\'\-]{2,15}$', stripslashes(trim($_POST['last_name']))))
					{ 
						# escape_data eliminates any extra spaces from the string
						$ln = escape_data($_POST['last_name']);
					} else {
						$ln = FALSE;
						# TODO: replace with javascript for immediat error alerting
						echo '<p>Vul alstublieft uw achternaam in!</p';
					}
					
					
			?>
		
   	
		
	</body>
</html>
