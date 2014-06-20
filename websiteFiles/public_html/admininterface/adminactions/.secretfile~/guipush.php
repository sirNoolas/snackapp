<!DOCTYE html>
<html lang="nl">
	<body>
		<form action="sendpush.php" method="post">
			<input type="text" name="subject" size="30" maxlength="50" value="Betreft"/><br>
			<input type="textfield" name="text"/><br>
			<input type="submit" name="submit_push" value="Verzenden"/>
			<input type="hidden" name="push" value="TRUE"/>
		</form>
	</body>
</html>
