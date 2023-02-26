<?php

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
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passcheck = password_verify($password, $row['password'])

    
    

        // retrieve user data from database
        $sql = "SELECT * FROM users WHERE user_name='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // user exists, check if password matches
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // login successful, redirect to dashboard
                header("Location: index.php");
                exit();
            }
            else{
                echo"Invalid  password. Please try again.";
            }
            
        echo"Invalid username . Please try again.";
        
        }
        
        
    }

    

    // close database connection
    $conn->close();
?>