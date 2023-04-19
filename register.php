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
	
	// Get form data
	$nickname = $_POST['nickname'];
	$name = $_POST['name'];
	$surname = $_POST['surname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	$phone = $_POST['phone'];
	

	
	// Check if password and confirm password match
	if ($password !== $confirm_password) {
		echo "Password and confirm password do not match!";
		exit;
	}
	
	// Hash password
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	
	// Insert data into database
	$sql = "INSERT INTO users (nickname,  password, email, name, surname, phoneNumber) VALUES ('$nickname', '$hashed_password', '$email' ,'$name','$surname','$phone')";
	
	if ($conn->query($sql) === TRUE) {
	  echo "New record created successfully";
	} else {
	  echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	// Close database connection
	$conn->close();
?>