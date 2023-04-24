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
	$description = $_POST['description'];

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
			die('Файл не является изображением');
		}

		// Сохраняем файл в папку с картинками
		$upload_dir = 'images/';
		$upload_path = $upload_dir . $filename;
		move_uploaded_file($filetmp, $upload_path);
	} else {
		die('Ошибка при загрузке файла');
	}
		// Insert data into database with photo path
		$sql = "INSERT INTO quests (name,category, adress, discount, peopleAmount, ageLimit, description, photoPath) VALUES ('$name','$category', '$adress', '$discount' ,'$peopleAmount','$ageLimit','$description', '$upload_path')";

	if (mysqli_query($conn, $sql)) {
		echo "New quest added successfully!";
	} else {
		echo "Error: " . mysqli_error($conn);
	}
}

// Close database connection
mysqli_close($conn);
?>
