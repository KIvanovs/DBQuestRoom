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

// Check if form is submitted
if(isset($_POST['submit'])){

    // Get form data
    $name =  $_POST['name'];
    $surname =  $_POST['surname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $password_check = $_POST['password'];
    $personCode = $_POST['personCode'];
    $phoneNumber =  $_POST['phoneNumber'];


    // Validate form data
	if (empty($name) || empty($surname) || empty($email) || empty($password_check) || empty($personCode) || empty($phoneNumber)) {
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

	if (strlen($password_check) < 8) {
		echo "Password should be at least 8 characters long!";
		exit();
	}

    
	// Check if nickname, email or phone number already exists in database
    $check_duplicate_user_query = "SELECT * FROM users WHERE email = '$email' OR phoneNumber = '$phoneNumber'";
    $check_duplicate_user_result = $conn->query($check_duplicate_user_query);
    
    if ($check_duplicate_user_result->num_rows > 0) {
        echo "Email or phone number already exists!";
        exit();
    }

    $check_duplicate_admin_query = "SELECT * FROM admin WHERE personCode = '$personCode' OR email = '$email' OR phoneNumber = '$phoneNumber'";
    $check_duplicate_admin_result = $conn->query($check_duplicate_admin_query);
    
    if ($check_duplicate_admin_result->num_rows > 0) {
        echo "Personal code, email or phone number already exists!";
        exit();
    }

    // Insert data into database with duplicate check
    $query = "INSERT INTO admin (name, surname, email, password, personCode, phoneNumber)
              VALUES ('$name', '$surname', '$email', '$password', '$personCode', '$phoneNumber')";

    if(mysqli_query($conn, $query)){
        if(mysqli_affected_rows($conn) > 0){
            header("Location: ../admin/admin.php");
            exit();
        } else{
            echo "Error: Email, person code, or phone number already exists in the database.";
        }
    } else{
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);


?>