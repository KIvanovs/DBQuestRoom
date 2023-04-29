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

// Get the room ID from the form
$reserv_id = $_POST['reserv_id'];

// Delete the comment from the database
$query = "DELETE FROM reservation WHERE ID = $reserv_id";
$result = mysqli_query($conn, $query);

if ($result) {

	// The comment was deleted successfully, 
	header("Location: profile_info.php");
    exit();

} else {

	// The comment was not deleted, show an error message
	echo "Error deleting comment.";

}

mysqli_close($conn);
?>