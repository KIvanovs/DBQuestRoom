<?php
session_start();

// Database connection
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Get the input values
	$comment = $_POST['comment'];
	$user_id = $_SESSION['user_id'];

	// Insert the comment into the database
	$query = "INSERT INTO comment (comment, user_id) VALUES ('$comment', '$user_id')";
	$result = mysqli_query($conn, $query);

	if ($result) {

		// The comment was inserted successfully, show a success message
		echo "Comment added successfully.";

	} else {

		// The comment was not inserted, show an error message
		echo "Error adding comment.";

	}

}

mysqli_close($conn);
?>