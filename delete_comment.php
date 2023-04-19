<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}

// Database connection
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Get the comment ID from the form
$comment_id = $_POST['comment_id'];

// Delete the comment from the database
$query = "DELETE FROM comment WHERE id = $comment_id";
$result = mysqli_query($conn, $query);

if ($result) {

	// The comment was deleted successfully, show a success message
	echo "Comment deleted successfully.";

} else {

	// The comment was not deleted, show an error message
	echo "Error deleting comment.";

}

mysqli_close($conn);
?>
