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

    // Insert data into database with duplicate check
    $query = "INSERT INTO admin (name, surname, email, password, personCode, phoneNumber)
              SELECT '$name', '$surname', '$email', '$password', '$personCode', '$phoneNumber'
              FROM admin
              WHERE NOT EXISTS (SELECT 1 FROM admin WHERE email='$email' OR personCode='$personCode' OR phoneNumber='$phoneNumber')
              LIMIT 1";

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