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

// Get the user ID to update
$user_id = $_POST['user_id'];

// Get the new user data from the form
$nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$surname = mysqli_real_escape_string($conn, $_POST['surname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

// Hash the password if it's not empty
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET nickname='$nickname', name='$name', surname='$surname', email='$email', password='$hashed_password', phoneNumber='$phone_number' WHERE ID=$user_id";
} else {
    $query = "UPDATE users SET nickname='$nickname', name='$name', surname='$surname', email='$email', phoneNumber='$phone_number' WHERE ID=$user_id";
}

if (mysqli_query($conn, $query)) {
// User updated successfully, redirect back to the user list
header("Location: profile_info.php");
exit();
} else {
// Error updating user, display an error message
echo "Error updating user: " . mysqli_error($conn);
}

mysqli_close($conn);

