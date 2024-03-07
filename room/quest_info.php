<?php
include '../includes/dbcon.php';

    $name = "";
    $category = "";
    $address = "";
    $peopleAmount = "";
    $ageLimit = "";
    $description = "";
    $photoPath = "";

    // Function to escape user input
function escape_input($input) {
    global $conn;
    return mysqli_real_escape_string($conn, $input);
}

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ID'])) {
    $quest_id = mysqli_real_escape_string($conn, $_GET['ID']);

		// Select quest from the database
		
        $query = "SELECT q.ID, q.name, q.peopleAmount, q.description, q.photoPath,
                  a.buildingAdress, 
                  qc.ageLimit, qc.categoryName
              FROM quests q
              LEFT JOIN adress a ON q.adress_id = a.ID
              LEFT JOIN questcategory qc ON q.questCategory_id = qc.ID  WHERE q.ID='$quest_id'";
		$result = mysqli_query($conn, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$name = $row['name'];
			$category = $row['categoryName'];
			$address = $row['buildingAdress'];
			$peopleAmount = $row['peopleAmount'];
			$ageLimit = $row['ageLimit'];
			$description = $row['description'];
			$photoPath = $row['photoPath'];
		} else {
			die("Quest not found!");
		}
}

// Close database connection
mysqli_close($conn);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    include '../includes/dbcon.php'; // Reconnect to the database

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $quest_id = mysqli_real_escape_string($conn, $_POST['quest_id']);

    // Insert comment into the database
    $insert_query = "INSERT INTO comment (comment, user_id, quest_id, creation_date) 
                    VALUES ('$comment', NULL, '$quest_id', CURRENT_TIMESTAMP)";
    if (mysqli_query($conn, $insert_query)) {
        echo "Comment added successfully!";
        // Redirect to the same page to prevent form resubmission
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $name; ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .selected {
            background-color: yellow; /* Цвет выделенной кнопки */
        }
        .selected-button {
            background-color: green; /* Цвет выбранной кнопки */
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-image">
        <img src="<?php echo $photoPath; ?>" alt="photo of <?php echo $name; ?>" style="max-width: 200px; max-height: 200px;">
        </div>
        <div class="card-content">
            <h2><?php echo $name; ?></h2>
            <p>Category: <?php echo $category; ?></p>
            <p>Address: <?php echo $address; ?></p>
            <p>Number of people: <?php echo $peopleAmount; ?></p>
            <p>Age limit: <?php echo $ageLimit; ?></p>
            <p>Description: <?php echo $description; ?></p>
            <form action="../room/reserve.php" method="post">
                <h1>Reservation</h1>
                <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
                <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                <input type="hidden" name="time" id="selected-time">
                <input type="hidden" name="cost" id="cost-input">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required>
                <br><br>
                <label>Time:</label><br>
                <?php
                    include '../includes/dbcon.php';

                    // Проверяем соединение
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Выполняем запрос к базе данных для получения данных о времени и цене
                    $query = "SELECT timePeriod, cost FROM prices";
                    $result = mysqli_query($conn, $query);

                    // Обрабатываем результаты запроса
                    if (mysqli_num_rows($result) > 0) {
                        // Выводим кнопки на основе данных из таблицы prices
                        while ($row = mysqli_fetch_assoc($result)) {
                            $time = $row['timePeriod'];
                            $cost = $row['cost'];
                            echo "<button type='button' name='time' value='$time' onclick='selectTime(\"$time\", $cost, this)'>$time ($cost EUR)</button>";
                        }
                    } else {
                        echo "No results";
                    }

                    // Закрываем соединение с базой данных
                    mysqli_close($conn);
                ?>
                <br><br>
                <div id="payment-modal" class="modal">
                    <div class="modal-content">
                        <h2>Payment</h2>
                        <p>You have selected:<span id="timePeriod"></span></p>
                        <p>Total price: <span id="cost"></span></p>
                        <label for="payment-method">Payment method:</label>
                        <select id="payment-method" name="payment_method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                        <button type="submit" name="submit">Reserve</button>
                    </div>
                </div>
            </form>

            <script>
                function selectTime(time, cost, button) {
                    // Установка выбранного времени и цены в блоке оплаты
                    document.getElementById("timePeriod").textContent = time;
                    document.getElementById("cost").textContent = cost + " EUR";

                    // Установка выбранной кнопке класса для изменения стиля
                    var buttons = document.querySelectorAll('button[name="time"]');
                    buttons.forEach(function(btn) {
                        btn.classList.remove('selected-button'); // Удаление класса у всех кнопок
                    });
                    button.classList.add('selected-button'); // Добавление класса выбранной кнопке


                    // Set the selected time and cost in the hidden input fields
                    document.getElementById("selected-time").value = time;
                    document.getElementById("cost-input").value = cost;

                    // Send the cost and selected time to a PHP
                    $.ajax({
                        type: "POST",
                        url: "reserve.php",
                        data: {
                            cost: cost,
                            time: time 
                        },
                        success: function (response) {
                            console.log("Cost and time sent to PHP: " + cost + " " + time);
                        },
                        error: function () {
                            console.error("AJAX request failed");
                        }
                    });

                    // Показ блока оплаты
                    document.getElementById("payment-modal").style.display = "block";
                }
            </script>
        </div>
    </div>
    <?php
    // Fetch comments for the current quest if ID is set
    if (isset($_GET['ID'])) {
        include '../includes/dbcon.php';

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $quest_id = mysqli_real_escape_string($conn, $_GET['ID']);
        $comments_query = "SELECT * FROM comment WHERE quest_id='$quest_id' ORDER BY creation_date DESC";
        $comments_result = mysqli_query($conn, $comments_query);

        if (mysqli_num_rows($comments_result) > 0) {
            // Display comments
            while ($comment = mysqli_fetch_assoc($comments_result)) {
                echo "<p>Comment: " . $comment['comment'] . "</p>";
                echo "<p>Posted on: " . $comment['creation_date'] . "</p>";
            }
        } else {
            echo "No comments found.";
        }

        // Close database connection
        mysqli_close($conn);
    }
    ?>

    <form action="../room/quest_info.php?ID=<?php echo $quest_id; ?>" method="post">
        <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
        <textarea name="comment" placeholder="Enter your comment"></textarea>
        <button type="submit">Submit</button>
    </form>
</body>
</html>