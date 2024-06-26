
<?php
include '../includes/dbcon.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
	

	
	// Validate form data
	if (empty($nickname) || empty($name) || empty($surname) || empty($email) || empty($password) || empty($confirm_password) || empty($phone)) {
		echo "Please fill in all fields!";
		exit();
	}

	if (strlen($nickname) > 30 || strlen($name) > 30 || strlen($surname) > 30 || strlen($email) > 30 || strlen($confirm_password) > 30 || strlen($phone) > 30){
		echo "Too long text , maximum 30 symbols!";
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

	if (!preg_match("/^[0-9]*$/", $phone)) {
		echo "Invalid phone number format!";
		exit();
	}

	if (strlen($password) < 8) {
		echo "Password should be at least 8 characters long!";
		exit();
	}

	if ($password !== $confirm_password) {
		echo "Password and confirm password do not match!";
		exit();
	}
	
	// Hash password
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	// Check if nickname, email or phone number already exists in database
    $check_duplicate_user_query = "SELECT * FROM users WHERE nickname = '$nickname' OR email = '$email' OR phoneNumber = '$phone'";
    $check_duplicate_user_result = $conn->query($check_duplicate_user_query);
    
    if ($check_duplicate_user_result->num_rows > 0) {
        echo "Nickname ,email or phone number already exists!";
        exit();
    }

    $check_duplicate_admin_query = "SELECT * FROM admin WHERE email = '$email' OR phoneNumber = '$phone'";
    $check_duplicate_admin_result = $conn->query($check_duplicate_admin_query);
    
    if ($check_duplicate_admin_result->num_rows > 0) {
        echo "Email or phone number already exists!";
        exit();
    }
	
	// Insert data into database
	$sql = "INSERT INTO users (nickname,  password, email, name, surname, phoneNumber) VALUES ('$nickname', '$hashed_password', '$email' ,'$name','$surname','$phone')";
	
	if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        echo "<p>Please <a href='../register_login/loginform.php'>log in</a> to start session.</p>";

        require '../vendor/autoload.php';


		

		$mail = new PHPMailer(true);
		try {
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'kirillquestroom@gmail.com'; // SMTP username
			$mail->Password = 'tdiscwhdffittzmz';        // SMTP password
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			$mail->setFrom('kirillquestroom@gmail.com', 'KirillQuestRoom');
			$mail->addAddress($email, $name); // Add a recipient

			$mail->isHTML(true); // Set email format to HTML
			$mail->Subject = 'Registration Successful';
			$mail->Body    = "Hello $name,<br><br>Thank you for registering on our site.<br><br>Best Regards,<br>The Team Kirill Quest Room";

			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
	
	// Close database connection
	$conn->close();
?>

