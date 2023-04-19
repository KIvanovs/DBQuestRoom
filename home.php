<!DOCTYPE html>
<html>
<head>
	<title>Card Example</title>
	<link rel="stylesheet" type="text/css" href="home.css">
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
				<form action="logout.php" method="post">
    				<button type="submit" name="logout">Logout</button>
				</form>
				
			</ul>
		</nav>
	</header>
	<div class="card-container">
		<div class="card">
			<div class="card-image">
				<img src="photo/escape-room1.jpg"alt="placeholder image">
			</div>
			<div class="card-content">
				<h2>Saw</h2>
				<p>Text about this location</p>
				<a href="#" class="btn">Read More</a>
			</div>
		</div>
		<div class="card">
			<div class="card-image">
				<img src="photo/escape-room2.jpg" alt="placeholder image">
			</div>
			<div class="card-content">
				<h2>Murder's room</h2>
				<p>Text about this location</p>
				<a href="#" class="btn">Read More</a>
			</div>
			
		</div>
		<div class="card">
			<div class="card-image">
				<img src="photo/escape-room3.jpg" alt="placeholder image">
			</div>
			<div class="card-content">
				<h2>Slaughterhouse</h2>
				<p>Text about this location</p>
				<a href="#" class="btn">Read More</a>
			</div>
			
		</div>
		<div class="card">
			<div class="card-image">
				<img src="photo/escape-room4.jpg" alt="placeholder image">
			</div>
			<div class="card-content">
				<h2>Dracula's castle</h2>
				<p>Text about this location</p>
				<a href="#" class="btn">Read More</a>
			</div>
			
		</div>
		<div class="card">
			<div class="card-image">
				<img src="photo/escape-room5.jpg" alt="placeholder image">
				<div class="category">Horror</div>
			</div>
			<div class="card-content">
				<h2>Psyho's room</h2>
				<p>Text about this location</p>
				<a href="psyho_room.php" class="btn">Read More</a>
			</div>
			
		</div>
	</div>
	<?php
	session_start(); // Start the session

if(isset($_SESSION['user_id'])) {
   // The user is currently in a session
   echo "You are in session!";
} else {
   // The user is not currently in a session
   echo "You are not in session!";
}
?>
</body>
</html>