<?php
session_start();
// Check if the user is not an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Redirect the user to the admin login page
    header("Location: admin_login.php");
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
    $phoneNumber = $_POST['phoneNumber'];

    // Check if email, personCode and phoneNumber already exist in database
    $query = "SELECT * FROM admin WHERE email='$email' OR personCode='$personCode' OR phoneNumber='$phoneNumber'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "Error: Email, person code, or phone number already exists in the database.";
        exit();
    }

    // Update data in database
    $query = "UPDATE admin SET name='$name', surname='$surname', email='$email', personCode='$personCode', phoneNumber='$phoneNumber' WHERE ID='$admin_id'";

    if(mysqli_query($conn, $query)){
        header("Location: admin.php");
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
    echo "<p><label for='phoneNumber'>Phone Number:</label> <input type='text' name='phoneNumber' value='" . $row['phoneNumber'] . "'></p>";
    echo "<input type='hidden' name='admin_id' value='" . $row['ID'] . "'>";
    echo "<input type='submit' name='update_admin' value='Update'>";
    echo "</form>";
} else {
    // Admin not found
    echo "Admin not found.";
}

mysqli_close($conn);
