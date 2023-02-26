<?php
//connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

//process comment form
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['comment'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$comment = $_POST['comment'];
	$query = "INSERT INTO comment (name, email, comment) VALUES ('$name', '$email', '$comment')";
	mysqli_query($conn, $query);
}

//close database connection
mysqli_close($conn);

//redirect back to the comment form page
header('Location: comment_form.php');
exit;
?>