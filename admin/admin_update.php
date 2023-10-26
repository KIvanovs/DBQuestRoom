<?php
session_start();
// Check if the user is not an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Redirect the user to the admin login page
    header("Location: ../register_login/loginform.php");
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

// Check if form is submitted
if(isset($_POST['update_admin'])){

    // Get admin ID
    $admin_id = $_POST['admin_id'];

    // Get form data
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $personCode = $_POST['personCode'];
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phoneNumber = $_POST['phoneNumber'];

    // Validate form data
	if (empty($name) || empty($surname) || empty($email) || empty($personCode) || empty($phoneNumber)) {
		echo "Please fill in all fields!";
		exit();
	}

	if (strlen($name) > 30 || strlen($surname) > 30 || strlen($email) > 30 || strlen($phoneNumber) > 30){
		echo "Too long text , maximum 30 symbols!";
		exit();
	}

    if (strlen($personCode) !== 12){
        echo "Personal Code should be 12 symbols!";
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

	if (!preg_match("/^[0-9]*$/", $phoneNumber)) {
		echo "Invalid phone number format!";
		exit();
	}

    if (!preg_match("/^[0-9-]*$/", $personCode)) {
		echo "Invalid personal code format!";
		exit();
	}


    // Check if email, personCode and phoneNumber already exist in database
    $query = "SELECT * FROM admin WHERE (email='$email' OR personCode='$personCode' OR phoneNumber='$phoneNumber') AND ID!='$admin_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "Error: Email, person code, or phone number already exists in the database A.";
        exit();
    }

    $query = "SELECT * FROM users WHERE email='$email' OR phoneNumber='$phoneNumber'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "Error: Email, person code, or phone number already exists in the database U.";
        exit();
    }


        // Hash the password if it's not empty
    if (!empty($password)) {
        if (strlen($password) < 8) {
            echo "Password should be at least 8 characters long!";
            exit();
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET name='$name', surname='$surname', email='$email', password='$hashed_password', personCode='$personCode', phoneNumber='$phoneNumber' WHERE ID='$admin_id'";
    } else {
        $query = "UPDATE admin SET name='$name', surname='$surname', email='$email', personCode='$personCode', phoneNumber='$phoneNumber' WHERE ID='$admin_id'";
    }
    // Update data in database
    

    if(mysqli_query($conn, $query)){
        header("Location: admin/admin.php");
        exit();
    } else{
        echo "Error updating admin: " . mysqli_error($conn);
    }
}

// Get current admin data
$admin_id = $_POST['admin_id'];
$query = "SELECT * FROM admin WHERE ID='$admin_id'";
$result = mysqli_query($conn, $query);

// Check if admin was found
if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_assoc($result);

    // Display edit form
    echo "<form method='post' action=''>";
    echo "<p><label for='name'>Name:</label> <input type='text' name='name' value='" . $row['name'] . "'></p>";
    echo "<p><label for='surname'>Surname:</label> <input type='text' name='surname' value='" . $row['surname'] . "'></p>";
    echo "<p><label for='email'>Email:</label> <input type='email' name='email' value='" . $row['email'] . "'></p>";
    echo "<p><label for='personCode'>Personal Code:</label> <input type='text' name='personCode' value='" . $row['personCode'] . "'></p>";
    echo "<p><label for='password'>Password:</label> <input type='password' name='password'></p>";
    echo "<p><label for='phoneNumber'>Phone Number:</label> <input type='text' name='phoneNumber' value='" . $row['phoneNumber'] . "'></p>";
    echo "<input type='hidden' name='admin_id' value='" . $row['ID'] . "'>";
    echo "<input type='submit' name='update_admin' value='Update'>";
    echo "</form>";
} else {
    // Admin not found
    echo "Admin not found.";
}

mysqli_close($conn);
