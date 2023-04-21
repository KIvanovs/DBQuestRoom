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

    // Insert data into database
    $query = "INSERT INTO admin (name, surname, email, password, personCode, phoneNumber)
            VALUES ('$name', '$surname', '$email', '$password', '$personCode', '$phoneNumber')";

    if(mysqli_query($conn, $query)){
        echo "New admin added successfully.";
    } else{
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);

header("Location: admin.php");
exit();
?>