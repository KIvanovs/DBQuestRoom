<?php
include '../includes/dbcon.php';

function generateRandomFilename($prefix = 'room_image') {
    $randomNumber = rand(1, 999999999); // You can adjust the range as needed
    return $prefix . $randomNumber;
}

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Get form data
	$name = mysqli_real_escape_string($conn, $_POST['name']);
    $adress = mysqli_real_escape_string($conn, $_POST['adress']); // Escape the address string
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Escape the category string
    $peopleAmount = $_POST['peopleAmount'];
    $ageLimit = $_POST['ageLimit'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);


	// Validate form data

	if (empty($name) || empty($category) || empty($adress) || empty($peopleAmount) || empty($ageLimit) || empty($description)) {
		echo "Please fill in all fields!";
		exit();
	}
	  
	if (strlen($name) > 50 || strlen($category) > 30 || strlen($adress) > 50 || strlen($peopleAmount) > 30 || strlen($ageLimit) > 30 || strlen($description) > 500) {
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
        $filetype = $file['type'];

        // Check if the file is an image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if (!in_array($filetype, $allowed_types)) {
            die('Invalid file type.');
        }

        // Generate a random filename
        $filename = generateRandomFilename();

        // Ensure the filename is unique
        while (file_exists('../images/' . $filename)) {
            $filename = generateRandomFilename();
        }

        $upload_dir = '../images/';
        $upload_path = $upload_dir . $filename;

        move_uploaded_file($file['tmp_name'], $upload_path);
    } else {
        die('Please upload a photo!');
    }
	
	 // Insert data into 'adress' table
	 $sql_insert_adress = "INSERT INTO adress (buildingAdress) VALUES ('$adress')";
	 if (mysqli_query($conn, $sql_insert_adress)) {
		 // Get the ID of the inserted address
		 $adress_id = mysqli_insert_id($conn);
 
		 // Insert data into 'questcategory' table
		 $sql_insert_questcategory = "INSERT INTO questcategory (ageLimit, categoryName) VALUES ('$ageLimit', '$category')";
		 if (mysqli_query($conn, $sql_insert_questcategory)) {
			 // Get the ID of the inserted category
			 $questcategory_id = mysqli_insert_id($conn);
 
			 // Insert data into 'quests' table with photo path, address_id, and questcategory_id
			 $sql_insert_quest = "INSERT INTO quests (name, peopleAmount, description, photoPath, adress_id, questCategory_id) VALUES ('$name', '$peopleAmount', '$description', '$upload_path', '$adress_id', '$questcategory_id')";
			 if (mysqli_query($conn, $sql_insert_quest)) {
				 echo "New quest added successfully!";
				 echo "<a href=../room/quest_form.php>Back to quests info page<a> ";
			 } else {
				 echo "Error inserting into 'quests' table: " . mysqli_error($conn);
			 }
		 } else {
			 echo "Error inserting into 'questcategory' table: " . mysqli_error($conn);
		 }
	 } else {
		 echo "Error inserting into 'adress' table: " . mysqli_error($conn);
	 }
 }

// Close database connection
mysqli_close($conn);
?>
