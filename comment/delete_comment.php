<?php
session_start();

include '../includes/dbcon.php';

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Get the comment ID from the form
$comment_id = $_POST['comment_id'];

// Delete the comment from the database
$query = "DELETE FROM comment WHERE id = $comment_id";
$result = mysqli_query($conn, $query);

if ($result) {

	// The comment was deleted successfully, 
	header("Location: ../comment/comment_page.php");
    exit();

} else {

	// The comment was not deleted, show an error message
	echo "Error deleting comment.";

}

mysqli_close($conn);
?>
