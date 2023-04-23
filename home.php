<!DOCTYPE html>
<html>
<head>
	<title>Card Example</title>
	<link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
	<?php
	include 'header.php';
	?>
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

if(isset($_SESSION['user_id']) && isset($_SESSION['nickname'])) {
   // The user is currently logged in as a user
   echo "You are logged in as a user!";
   echo "Welcome " . $_SESSION['nickname'];
} else if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
   // The user is currently logged in as an admin
   echo "You are logged in as an admin!";
   echo "Welcome " . $_SESSION['admin_name'];
} else {
   // The user is not currently logged in
   echo "You are not logged in!";
}
?>
</body>
</html>
