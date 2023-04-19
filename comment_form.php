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
				<li><a href="home.php">Home</a></li>
				<li><a href="index.php">Search</a></li>
				<li><a href="comment_page.php">Comments</a></li>
				<li><a href="registerform.php">Register</a></li>
				<li><a href="loginform.php">Login</a></li>
				
			</ul>
		</nav>
	</header>
	<div class="comment-container">		
		<h1>Add Comment</h1>
	<form method="post" action="process_comment.php">
		<label>Comment:</label>
		<textarea name="comment" required></textarea><br><br>
		<input type="submit" value="Add Comment">
	</form>
	</div>
</body>
</html>