<?php
//connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Get form data
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$adress = $_POST['adress'];
	$category = $_POST['category'];
	$discount = $_POST['discount'];
	$peopleAmount = $_POST['peopleAmount'];
	$ageLimit = $_POST['ageLimit'];
	$description = mysqli_real_escape_string($conn, $_POST['description']);


	// Validate form data
	if (!is_numeric($discount) || $discount < 0 || $discount > 100) {
		echo "Discount should be a number between 0 and 100!";
		exit();
	}

	if (empty($name) || empty($category) || empty($adress) || empty($peopleAmount) || empty($ageLimit) || empty($description)) {
		echo "Please fill in all fields!";
		exit();
	}
	  
	if (strlen($name) > 50 || strlen($category) > 30 || strlen($adress) > 50 || strlen($discount) > 30 || strlen($peopleAmount) > 30 || strlen($ageLimit) > 30 || strlen($description) > 500) {
		echo "Too long text, maximum 30 symbols for the name, category, address, discount and peopleAmount and maximum 500 symbols for the description!";
		exit();
	}

	if (!preg_match("/^[a-zA-Z ]*$/", $category)) {
		echo "Category should only contain letters and spaces!";
		exit();
	}
	  
	  
	if (!preg_match('/^[0-9-]+$/', $peopleAmount)) {
		echo "People Amount should be a number and hyphen!";
		exit();
	}
	  
	if (!is_numeric($ageLimit) || $ageLimit <= 0 && $ageLimit >= 99) {
		echo "Age Limit should be a number greater than 0 and without symbols!";
		exit();
	}

	// Check for duplicate name
	$query = "SELECT * FROM quests WHERE name='$name'";
	$result = mysqli_query($conn, $query);

	if (mysqli_num_rows($result) > 0) {
		echo "Quest with this name already exists!";
		exit;
	}

	// Process uploaded file
	if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
		$file = $_FILES['photo'];
		$filename = $file['name'];
		$filetmp = $file['tmp_name'];
		$filetype = $file['type'];
		$filesize = $file['size'];
		$fileerror = $file['error'];

		// Проверяем, что файл является изображением
		$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
		if (!in_array($filetype, $allowed_types)) {
			die('Invalid file type.');
		}

		// Сохраняем файл в папку с картинками
		$upload_dir = '../images/';
		$upload_path = $upload_dir . $filename;
		move_uploaded_file($filetmp, $upload_path);
	} else {
		die('Please upload a photo!');
	}
		// Insert data into database with photo path
		$sql = "INSERT INTO quests (name,category, adress, discount, peopleAmount, ageLimit, description, photoPath) VALUES ('$name','$category', '$adress', '$discount' ,'$peopleAmount','$ageLimit','$description', '$upload_path')";

	if (mysqli_query($conn, $sql)) {
		echo "New quest added successfully!";
		echo "<a href=../room/quest_form.php>Back to quests info page<a> ";
	} else {
		echo "Error: " . mysqli_error($conn);
	}
}

// Close database connection
mysqli_close($conn);
?>
