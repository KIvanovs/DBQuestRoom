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

// Get the comment ID from the form
$comment_id = $_POST['comment_id'];

// Retrieve the current comment from the database
$query = "SELECT comment FROM comment WHERE id = $comment_id";
$result = mysqli_query($conn, $query);

if (!$result) {
die("Error retrieving comment: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Check if the form was submitted
if (isset($_POST['update_comment'])) {

    // Get the new comment from the form
    $new_comment = mysqli_real_escape_string($conn, $_POST['new_comment']);

    // Update the comment in the database
    $query = "UPDATE comment
		  SET comment = '$new_comment'
		  WHERE id = $comment_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
	    die("Error updating comment: " . mysqli_error($conn));
    }
    
    header("Location: ../comment/comment_page.php");
                exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Update Comment</title>
</head>
<body>
	<h1>Update Comment</h1>
	<form method="post" action="">
		<label for="new_comment">New Comment:</label>
		<textarea id="new_comment" name="new_comment" rows="5" cols="50"><?php echo $row['comment']; ?></textarea>
		<br>
		<input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
		<input type="submit" name="update_comment" value="Update Comment">
	</form>
</body>
</html>