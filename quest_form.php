<!DOCTYPE html>
<html>
<head>
	<title>Add Quest</title>
</head>
<body>
	<h2>Add Quest</h2>
	<form method="post" action="add_quest.php">
		<label for="name">Name:</label>
		<input type="text" name="name" required><br><br>

		<label for="adress">Address:</label>
		<input type="text" name="adress" required><br><br>

		<label for="discount">Discount:</label>
		<input type="text" name="discount" required><br><br>

		<label for="peopleAmount">People Amount:</label>
		<input type="text" name="peopleAmount" required><br><br>

		<label for="ageLimit">Age Limit:</label>
		<input type="text" name="ageLimit" required><br><br>

		<label for="description">Description:</label>
		<textarea name="description" required></textarea><br><br>

		<input type="submit" name="submit" value="Add Quest">
	</form>
</body>
</html>