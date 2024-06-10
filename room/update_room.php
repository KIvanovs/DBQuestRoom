<?php
$pageTitle = 'Quest Room update form';
include_once '../includes/header.php';
?>
<?php
// Check if the user is not an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Redirect the user to the admin login page
    header("Location: ../register_login/loginform.php");
    exit();
}

include '../includes/dbcon.php';

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
    $peopleAmount = mysqli_real_escape_string($conn, $_POST['peopleAmount']);
    $ageLimit = mysqli_real_escape_string($conn, $_POST['ageLimit']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);


    // Validate form data

	if (empty($name) || empty($category) || empty($address) || empty($peopleAmount) || empty($ageLimit) || empty($description)) {
		echo "Please fill in all fields!";
		exit();
	}
	  
	if (strlen($name) > 50 || strlen($category) > 30 || strlen($address) > 50 || strlen($peopleAmount) > 30 || strlen($ageLimit) > 30 || strlen($description) > 500) {
		echo "Too long text, maximum 30 symbols for the name, category, address, discount and peopleAmount and maximum 500 symbols for the description!";
		exit();
	}

	if (!preg_match("/^[a-zA-Z ]*$/", $category)) {
		echo "Category should only contain letters and spaces!";
		exit();
	}
	  
	  
	if (!preg_match('/^([1-9])-([1-9])$/', $peopleAmount, $matches)) {
        echo "People Amount should be in the format 'x-y', where x and y are numbers greater than 0!";
        exit();
    }

    if ($matches[1] >= $matches[2]) {
        echo "The first number in People Amount should be less than the second number!";
        exit();
    
    }
	if (!is_numeric($ageLimit) || $ageLimit <= 0 || $ageLimit >= 99) {
        echo "Age Limit should be a number greater than 0 and less than 99!";
        exit();
    }


    // Функция для генерации случайного имени файла
    function generateRandomFilename() {
        return uniqid() . '.jpg'; // Вы можете изменить расширение или добавить более сложную логику генерации имени
    }

    // Функция для сохранения изображения
    function saveImage($file, $oldFilePath) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $filetype = $file['type'];

        // Проверка типа файла
        if (!in_array($filetype, $allowed_types)) {
            die('Invalid file type.');
        }

        $target_dir = '../images/';

        // Генерация случайного имени файла
        $filename = generateRandomFilename();

        // Убедимся, что имя файла уникально
        while (file_exists($target_dir . $filename)) {
            $filename = generateRandomFilename();
        }

        $target_file = $target_dir . $filename;

        // Удаление старого изображения
        if (!empty($oldFilePath) && file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        // Перемещение загруженного файла
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $target_file;
        } else {
            return null;
        }
    }

    // Проверка наличия файла для загрузки
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoPath = saveImage($_FILES['photo'], $_POST['photoPath']);
        if ($photoPath === null) {
            die('Error uploading photo.');
        }
    } else {
        // Если нет нового файла, оставляем старый путь к фотографии
        $photoPath = $_POST['photoPath'];
    }

    // Обновление таблицы quests
    $query = "UPDATE quests SET name='$name', peopleAmount='$peopleAmount', description='$description', photoPath='" . mysqli_real_escape_string($conn, $photoPath) . "' WHERE ID='$room_id'";
    if (!mysqli_query($conn, $query)) {
        echo "Error updating quests table: " . mysqli_error($conn);
        exit();
    }

    // Update the questcategory table
    $query = "UPDATE questcategory qc
              JOIN quests q ON q.questCategory_id = qc.ID
              SET qc.categoryName='$category', qc.ageLimit='$ageLimit'
              WHERE q.ID='$room_id'";
    if (!mysqli_query($conn, $query)) {
        echo "Error updating questcategory table: " . mysqli_error($conn);
        exit();
    }

    // Update the adress table
    $query = "UPDATE adress a
              JOIN quests q ON q.adress_id = a.ID
              SET a.buildingAdress='$address'
              WHERE q.ID='$room_id'";
    if (!mysqli_query($conn, $query)) {
        echo "Error updating adress table: " . mysqli_error($conn);
        exit();
    }

    header("Location: ../room/quest_form.php");
    exit();
}

// Get current room data

$id = $_POST['id'];
$query = "SELECT q.ID, q.name, qc.categoryName, a.buildingAdress, q.peopleAmount, qc.ageLimit, q.description, q.photoPath 
          FROM quests q
          LEFT JOIN questcategory qc ON q.questCategory_id = qc.ID
          LEFT JOIN adress a ON q.adress_id = a.ID
          WHERE q.ID='$id'";
$result = mysqli_query($conn, $query);

// Check if room was found
if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_assoc($result);
        
        ?>

        <div class="container mt-5">
        <h2>Edit Room</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" name="category" value="<?php echo $row['categoryName']; ?>">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" value="<?php echo $row['buildingAdress']; ?>">
            </div>
            <div class="form-group">
                <label for="peopleAmount">People Amount:</label>
                <input type="text" class="form-control" name="peopleAmount" value="<?php echo $row['peopleAmount']; ?>">
            </div>
            <div class="form-group">
                <label for="ageLimit">Age Limit:</label>
                <input type="text" class="form-control" name="ageLimit" value="<?php echo $row['ageLimit']; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description"><?php echo $row['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Current photo:</label><br>
                <img src="<?php echo $row['photoPath']; ?>" alt="Room photo" style="max-width: 200px; max-height: 200px;">
            </div>
            <div class="form-group">
                <label for="photo">New Photo:</label>
                <input type="file" class="form-control-file" name="photo">
            </div>
            <input type="hidden" name="photoPath" value="<?php echo $row['photoPath']; ?>">
            <input type="hidden" name="room_id" value="<?php echo $row['ID']; ?>">
            <button type="submit" class="btn btn-primary" name="update_room">Update</button>
        </form>
    </div>
   
    <?php
    } else{
        echo "Room not found.";
    }
    
    // Close database connection
    mysqli_close($conn);
    ?>