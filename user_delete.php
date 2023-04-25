<?php
session_start();

// Check if the user is not an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Redirect the user to the admin login page
    header("Location: loginform.php");
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

// Get the user ID to delete
$user_id = $_POST['user_id'];

// Delete the user and their reservations
$query = "DELETE FROM users WHERE ID = " . $user_id;
mysqli_query($conn, $query);

$query = "DELETE FROM reservation WHERE client_id = " . $user_id;
mysqli_query($conn, $query);

mysqli_close($conn);

// Redirect the user back to the user list
header("Location: profile_info.php");
exit();
?>
