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

// Check if form is submitted
if(isset($_POST['update_room'])){

    // Get room ID
    $room_id = $_POST['room_id'];

    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $peopleAmount = mysqli_real_escape_string($conn, $_POST['peopleAmount']);
    $ageLimit = mysqli_real_escape_string($conn, $_POST['ageLimit']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    function saveImage($file){
        $target_dir = "images/";
        $target_file = $target_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $target_file;
        } else {
            return null;
        }
    }

    // Handle photo upload
    if ($_FILES['photo']['name'] != '') {
        $photoPath = saveImage($_FILES['photo']);
    } else {
        $photoPath = $_POST['photoPath'];
    }

    // Update data in database
    $query = "UPDATE quests SET name='$name', category='$category', adress='$address', discount='$discount', peopleAmount='$peopleAmount', ageLimit='$ageLimit', description='$description', photoPath='" . mysqli_real_escape_string($conn, $photoPath) . "' WHERE ID='$room_id'";


    if(mysqli_query($conn, $query)){
        header("Location: quest_form.php");
        exit();
    } else{
        echo "Error updating room: " . mysqli_error($conn);
    }
}

// Get current room data

$id = $_POST['id'];
$query = "SELECT * FROM quests WHERE ID='$id'";
$result = mysqli_query($conn, $query);

// Check if room was found
if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_assoc($result);

    // Display edit form
    echo "<form method='post' action='' enctype='multipart/form-data'>";
    echo "<p><label for='name'>Name:</label> <input type='text' name='name' value='" . $row['name'] . "'></p>";
    echo "<p><label for='category'>Category:</label> <input type='text' name='category' value='" . $row['category'] . "'></p>";
    echo "<p><label for='address'>Address:</label> <input type='text' name='address' value='" . $row['adress'] . "'></p>";
    echo "<p><label for='discount'>Discount:</label> <input type='text' name='discount' value='" . $row['discount'] . "'></p>";
    echo "<p><label for='peopleAmount'>People Amount:</label> <input type='text' name='peopleAmount' value='" . $row['peopleAmount'] . "'></p>";
    echo "<p><label for='ageLimit'>Age Limit:</label> <input type='text' name='ageLimit' value='" . $row['ageLimit'] . "'></p>";
    echo "<p><label for='description'>Description:</label> <textarea name='description'>" . $row['description'] . "</textarea></p>";
    echo "<p>Current photo path: " . $row['photoPath'] . "</p>";
    echo "<img src='" . $row['photoPath'] . "' alt='Room photo' style='max-width: 200px; max-height: 200px;'>";
    echo "<p><label for='photo'>Photo:</label> <input type='file' name='photo'></p>";
    echo "<input type='hidden' name='photoPath' value='" . $row['photoPath'] . "'>";
    echo "<input type='hidden' name='room_id' value='" . $row['ID'] . "'>";
    echo "<p><input type='submit' name='update_room' value='Update'></p>";
    echo "</form>";
    } else{
    echo "Room not found.";
    }
    
    // Close database connection
    mysqli_close($conn);
    ?>