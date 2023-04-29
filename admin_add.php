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
    $personCode = $_POST['personCode'];
    $phoneNumber =  $_POST['phoneNumber'];

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
            header("Location: admin.php");
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