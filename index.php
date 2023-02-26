<!DOCTYPE html>
<html>
<head>
	<title>Search Form</title>
	<link rel="stylesheet" type="text/css" href="style.css">
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
	<div class="search-container">
		<h2>Search</h2>
		<form action="dbcon.php" method="post">
			<input type="text" placeholder="Enter keyword(s)" name="keyword">
			<button type="submit">Search</button>
		</form>
	</div>
</body>
</html>




