<?php
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
        $passcheck = password_verify($password, $row['password']);
    
        // retrieve user data from database
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // user exists, check if password matches
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // login successful, redirect to dashboard
			    $_SESSION['user_id'] = $row['ID'];
			    $_SESSION['nickname'] = $row['nickname'];
                header("Location: home.php");
                exit();
            }
            else{
                echo"Invalid  password. Please try again.";
            }
        }
        else{
            echo"Invalid username . Please try again.";
        }
    }

    // close database connection
    $conn->close();
?>