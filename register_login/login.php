<?php
session_start();

// unset all session variables
$_SESSION = array();

// destroy session
session_destroy();


session_start();

//connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
	
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// check if form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // retrieve user data from users table
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // user exists, check if password matches
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // login successful, redirect to dashboard
			$_SESSION['user_id'] = $row['ID'];
			$_SESSION['nickname'] = $row['nickname'];
			header("Location: ../room/quest_list.php");
			exit();
        } else {
            echo "Invalid password. Please try again.";
        }
    } else {
        // retrieve admin data from admin table
        $sql = "SELECT * FROM admin WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // admin exists, check if password matches
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // login successful, redirect to dashboard
                $_SESSION['admin_id'] = $row['ID'];
                $_SESSION['admin_name'] = $row['name'];
                header("Location: ../room/quest_list.php");
                exit();
            } else {
                echo "Invalid password. Please try again.";
            }
        } else {
            echo "Invalid email. Please try again.";
        }
    }
}

// close database connection
$conn->close();
?>