<!DOCTYPE html>
<html>
<head>
	<title>Card Example</title>
	<link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
	<?php
	include 'header.php';

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


// Connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Select all quests from the database
$query = "SELECT * FROM quests";
$result = mysqli_query($conn, $query);

// Display each quest as a card
while ($row = mysqli_fetch_assoc($result)) {
	$id = $row['ID'];
	$name = $row['name'];
	$category = $row['category'];
	$address = $row['adress'];
	$discount = $row['discount'];
	$peopleAmount = $row['peopleAmount'];
	$ageLimit = $row['ageLimit'];
	$description = $row['description'];
	$photoPath = $row['photoPath'];
	
	echo '<div class="card">';
	echo '<div class="card-image">';
	echo '<img src="' . $photoPath . '" alt="photo of ' . $name . '">';
	echo '</div>';
	echo '<div class="card-content">';
	echo '<h2>' . $name . '</h2>';
	echo '<p>' . $description . '</p>';
	echo '<a href="quest_info.php?ID=' . $id . '" class="btn">Read More</a>';
	echo '</div>';
	echo '</div>';
}

// Close database connection
mysqli_close($conn);
?>
</body>
</html>
