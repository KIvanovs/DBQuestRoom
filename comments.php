<?php


// Database connection
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Query the database for comments
$query = "SELECT comment.comment, users.nickname FROM comment JOIN users ON comment.user_id = users.ID";
$result = mysqli_query($conn, $query);

// Check if any comments were found
if (mysqli_num_rows($result) > 0) {

	// Display each comment
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<p><strong>" . $row['nickname'] . ":</strong> " . $row['comment'] . "</p>";
	}

} else {

	// No comments were found, show a message
	echo "No comments found.";

}

mysqli_close($conn);
?>