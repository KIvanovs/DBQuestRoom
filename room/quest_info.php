<?php
include '../includes/dbcon.php';

$name = "";
$category = "";
$address = "";
$peopleAmount = "";
$ageLimit = "";
$description = "";
$photoPath = "";

function escape_input($input) {
    global $conn;
    return mysqli_real_escape_string($conn, $input);
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ID'])) {
    $quest_id = mysqli_real_escape_string($conn, $_GET['ID']);
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

mysqli_close($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    include '../includes/dbcon.php';
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $quest_id = mysqli_real_escape_string($conn, $_POST['quest_id']);
    $reply_to = isset($_POST['reply_to']) ? mysqli_real_escape_string($conn, $_POST['reply_to']) : null;
    $insert_query = "INSERT INTO comment (comment, user_id, quest_id, reply_to, creation_date) 
                    VALUES ('$comment', NULL, '$quest_id', '$reply_to', CURRENT_TIMESTAMP)";
    if (mysqli_query($conn, $insert_query)) {
        echo "Comment added successfully!";
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
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
            background-color: yellow;
        }
        .selected-button {
            background-color: green;
        }
        .reply-popup {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 15px;
            z-index: 1;
        }
        .comment {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        }

        .reply {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            margin-left: 50px; /* Для размещения ответа правее комментария */
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
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    $query = "SELECT timePeriod, cost FROM prices";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $time = $row['timePeriod'];
                            $cost = $row['cost'];
                            echo "<button type='button' name='time' value='$time' onclick='selectTime(\"$time\", $cost, this)'>$time ($cost EUR)</button>";
                        }
                    } else {
                        echo "No results";
                    }
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
        </div>
    </div>

    <?php
    include '../includes/dbcon.php';
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $comments_query = "SELECT c.*, u.nickname 
                    FROM comment c 
                    LEFT JOIN users u ON c.user_id = u.ID
                    WHERE c.quest_id='$quest_id' 
                    ORDER BY c.creation_date DESC";
    $comments_result = mysqli_query($conn, $comments_query);
    if (mysqli_num_rows($comments_result) > 0) {
        while ($comment = mysqli_fetch_assoc($comments_result)) {
            echo "<div class='" . ($comment['reply_to'] > 0 ? 'reply' : 'comment') . "'>";
            echo "<p><strong>Comment by: " . $comment['nickname'] . "</strong></p>";
            echo "<p>Comment: " . $comment['comment'] . "</p>";
            echo "<p>Posted on: " . $comment['creation_date'] . "</p>";
            if ($comment['reply_to'] > 0) {
                // Выводим информацию о том, на какой комментарий был дан ответ
                $reply_to_query = "SELECT nickname FROM users WHERE ID=" . $comment['reply_to'];
                $reply_to_result = mysqli_query($conn, $reply_to_query);
                if (mysqli_num_rows($reply_to_result) > 0) {
                    $reply_to_user = mysqli_fetch_assoc($reply_to_result);
                    echo "<p>Reply to: " . $reply_to_user['nickname'] . "</p>";
                }
            }
            echo "<button type='button' onclick='replyToComment(\"{$comment['ID']}\")'>Reply</button>";
            echo "</div>";
            
            // Вставляем форму для ответа прямо под комментарием
            echo "<div class='reply-popup' id='replyPopup{$comment['ID']}' style='display: none;'>
                    <form action='../comment/quest_comment.php' method='post'>
                        <input type='hidden' name='quest_id' value='$quest_id'>
                        <input type='hidden' name='reply_to' value='{$comment['ID']}'>
                        <textarea name='comment' placeholder='Enter your reply'></textarea>
                        <button type='submit'>Submit</button>
                    </form>
                </div>";
        }
    } else {
        echo "No comments found.";
    }
    mysqli_close($conn);
    ?>

    <form action="../comment/quest_comment.php" method="post">
        <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
        <input type="hidden" name="reply_to" id="replyTo" value="">
        <textarea name="comment" placeholder="Enter your comment"></textarea>
        <button type="submit">Submit</button>
    </form>

    <!-- Popup for replying to comments -->
    <div class="reply-popup" id="replyPopup">
        <form action="../comment/quest_comment.php" method="post">
            <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
            <input type="hidden" name="reply_to" id="replyTo" value="">
            <textarea name="comment" placeholder="Enter your reply"></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        function selectTime(time, cost, button) {
            
            document.getElementById("timePeriod").textContent = time;
            document.getElementById("cost").textContent = cost + " EUR";
            var buttons = document.querySelectorAll('button[name="time"]');
            buttons.forEach(function(btn) {
                btn.classList.remove('selected-button');
            });
            button.classList.add('selected-button');
            document.getElementById("selected-time").value = time;
            document.getElementById("cost-input").value = cost;
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
            document.getElementById("payment-modal").style.display = "block";
        }

        function replyToComment(commentId) {
            document.getElementById('replyTo').value = commentId;
            document.getElementById('replyPopup').style.display = 'block';
            document.getElementById('replyPopup').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
<!-- если нажать reply и потом писать ответ там где комент , все гуд , а если писать через попап то нихрена -->