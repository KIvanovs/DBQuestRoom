<!DOCTYPE html>
<html>
<head>
	<title>Login Form</title>
	<link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
	<?php include '../includes/header.php'; 
	?>
	<form method="post" action="../register_login/login.php">
		<h2>Login Form</h2>
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" required>
		
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>
		
		<input type="submit" name="login" value="Login">
	</form>
</body>
</html>



