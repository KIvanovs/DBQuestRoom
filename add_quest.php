<?php
//connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$name = mysqli_real_escape_string($conn, $_POST['name']);// <-- this is ti deny syntax error
$adress = $_POST['adress'];
$discount = $_POST['discount'];
$peopleAmount = $_POST['peopleAmount'];
$ageLimit = $_POST['ageLimit'];
$description = $_POST['description'];

// Check for duplicate name
$query = "SELECT * FROM quests WHERE name='$name'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
	echo "Quest with this name already exists!";
	exit;
}

// Insert data into database
$sql = "INSERT INTO quests (name, adress, discount, peopleAmount, ageLimit, description) VALUES ('$name', '$adress', '$discount' ,'$peopleAmount','$ageLimit','$description')";

if (mysqli_query($conn, $sql)) {
	echo "New quest added successfully!";
} else {
	echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>