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

// Get the user ID to update from the GET parameter
$user_id = $_POST['user_id'];

// Query the database for the user's current data
$query = "SELECT * FROM users WHERE ID = " . $user_id;
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

// Display the user data in a form for editing
echo "<form method='post' action='user_update.php'>";
echo "<input type='hidden' name='user_id' value='" . $user_id . "'>";
echo "<p><label for='nickname'>Nickname:</label>";
echo "<input type='text' name='nickname' value='" . $user_data['nickname'] . "'></p>";
echo "<p><label for='name'>Name:</label>";
echo "<input type='text' name='name' value='" . $user_data['name'] . "'></p>";
echo "<p><label for='surname'>Surname:</label>";
echo "<input type='text' name='surname' value='" . $user_data['surname'] . "'></p>";
echo "<p><label for='email'>Email:</label>";
echo "<input type='email' name='email' value='" . $user_data['email'] . "'></p>";
echo "<p><label for='password'>Password:</label>";
echo "<input type='password' name='password'></p>";
echo "<p><label for='phoneNumber'>Phone Number:</label>";
echo "<input type='text' name='phoneNumber' value='" . $user_data['phoneNumber'] . "'></p>";
echo "<input type='submit' name='update_user' value='Update'>";
echo "</form>";

mysqli_close($conn);
?>