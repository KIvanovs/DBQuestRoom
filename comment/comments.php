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
$query = "SELECT comment.id, comment.comment, users.nickname, admin.name AS admin_name, comment.user_id, comment.admin_id FROM comment LEFT JOIN users ON comment.user_id = users.ID LEFT JOIN admin ON comment.admin_id = admin.ID";

$result = mysqli_query($conn, $query);

// Check if any comments were found
if (mysqli_num_rows($result) > 0) {

	// Display each comment
	while ($row = mysqli_fetch_assoc($result)) {
        if ($row['admin_name']) {
            echo "<p><strong>" . $row['admin_name'] . ":</strong> " . $row['comment'];
        } else {
            echo "<p><strong>" . $row['nickname'] . ":</strong> " . $row['comment'];
        }

        
        // Check if the user wrote the comment and add update and delete buttons
        if (isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id']) {
            echo "<form method='post' action='comment/delete_comment.php'>";
            echo "<input type='hidden' name='comment_id' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
    
            echo "<form method='post' action='comment/update_comment.php'>";
            echo "<input type='hidden' name='comment_id' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Update'>";
            echo "</form>";
        }
        
        echo "</p>";
    }

} else {

	// No comments were found, show a message
	echo "No comments found.";

}

mysqli_close($conn);
?>