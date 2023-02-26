<!DOCTYPE html>
<html>
<head>
	<title>Comment Form</title>
	<link rel="stylesheet" type="text/css" href="comment_form.css">
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
	<div class="comment-container">
		<h2>Leave a Comment</h2>
		<form action="process_comment.php" method="post">
			<label for="name">Name:</label>
			<input type="text" id="name" name="name" required>
			<label for="email">Email:</label>
			<input type="email" id="email" name="email" required>
			<label for="comment">Comment:</label>
			<textarea id="comment" name="comment" required></textarea>
			<button type="submit">Submit</button>
		</form>
	</div>
</body>
</html>