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
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: loginform.php");
    exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Get the input values
	$comment = $_POST['comment'];
	$user_id = $_SESSION['user_id'];

	// Check if the logged-in user is an admin or a regular user
	if (isset($_SESSION['admin_id'])) {
		// Insert the comment as an admin
		$admin_id = $_SESSION['admin_id'];
		$query = "INSERT INTO comment (comment, user_id, admin_id) VALUES ('$comment', NULL, '$admin_id')";
	} else {
		// Insert the comment as a regular user
		$query = "INSERT INTO comment (comment, user_id, admin_id) VALUES ('$comment', '$user_id', NULL)";
	}

	$result = mysqli_query($conn, $query);

	if ($result) {

		// The comment was inserted successfully, show a success message
		header("Location: comment_page.php");
    	exit();

	} else {

		// The comment was not inserted, show an error message
		echo "Error adding comment.";

	}

}

mysqli_close($conn);
?>