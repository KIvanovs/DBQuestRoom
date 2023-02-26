<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
	<link rel="stylesheet" type="text/css" href="register.css">
	<link rel="stylesheet" type="text/css" href="header.css">
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="index.php">Search</a></li>
				<li><a href="comment_form.php">Comments</a></li>
				<li><a href="registerform.php">Register</a></li>
				<li><a href="loginform.php">Login</a></li>
			</ul>
		</nav>
	</header>
	<div class="container">
		<h1>Registration Form</h1>
		<form action="register.php" method="POST">
			<label for="name">Name</label>
			<input type="text" id="name" name="name" required>
			
			<label for="email">Email</label>
			<input type="email" id="email" name="email" required>
			
			<label for="password">Password</label>
			<input type="password" id="password" name="password" required>
			
			<label for="confirm_password">Confirm Password</label>
			<input type="password" id="confirm_password" name="confirm_password" required>
			
			<input type="submit" value="Submit">
		</form>
	</div>
</body>
</html>