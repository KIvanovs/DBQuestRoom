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

// If an admin is being deleted
if (isset($_POST['delete_admin'])) {
    $admin_id = $_POST['admin_id'];

    // Delete the admin from the database
    $delete_query = "DELETE FROM admin WHERE ID = $admin_id";
    mysqli_query($conn, $delete_query);
}

header("Location: admin.php");
exit();
?>