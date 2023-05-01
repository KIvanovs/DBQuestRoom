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
$phone_number = mysqli_real_escape_string($conn, $_POST['phoneNumber']);


	// Validate form data
	if (empty($nickname) || empty($name) || empty($surname) || empty($email) || empty($phone_number)) {
		echo "Please fill in all fields!";
		exit();
	}

	if (strlen($nickname) > 30 || strlen($name) > 30 || strlen($surname) > 30 || strlen($email) > 30 || strlen($phone_number) > 30){
		echo "Too long text , maximum 30 symbols!";
		exit();
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "Invalid email format!";
		exit();
	}

	if (!preg_match("/^[a-zA-Z ]*$/", $name) || !preg_match("/^[a-zA-Z ]*$/", $surname)) {
		echo "Name and surname should only contain letters and spaces!";
		exit();
	}

	if (!preg_match("/^[0-9]*$/", $phone_number)) {
		echo "Invalid phone number format!";
		exit();
	}

	// Check if nickname, email or phone number already exists in database
    $check_duplicate_user_query = "SELECT * FROM users WHERE (email = '$email' OR phoneNumber = '$phone_number') AND ID!='$user_id'";
    $check_duplicate_user_result = $conn->query($check_duplicate_user_query);
    
    if ($check_duplicate_user_result->num_rows > 0) {
        echo "Email or phone number already exists!";
        exit();
    }

    $check_duplicate_admin_query = "SELECT * FROM admin WHERE personCode = '$personCode' OR email = '$email' OR phoneNumber = '$phone_number'";
    $check_duplicate_admin_result = $conn->query($check_duplicate_admin_query);
    
    if ($check_duplicate_admin_result->num_rows > 0) {
        echo "Personal code, email or phone number already exists!";
        exit();
    }


// Hash the password if it's not empty
if (!empty($password)) {
    if (strlen($password) < 8) {
		echo "Password should be at least 8 characters long!";
		exit();
	}
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

