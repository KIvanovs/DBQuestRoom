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

    <?php
    $pageTitle = 'Quest list';
    include_once '../includes/header.php';
    ?>

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
                        <br><br>
                        <div id="card_details" style="display: none;">
                            <label for="cardDate">Card Expiry Date:</label>
                            <input type="text" id="cardDate" name="cardDate">
                            <br><br>
                            <label for="cardNumber">Card Number:</label>
                            <input type="text" id="cardNumber" name="cardNumber">
                            <br><br>
                            <label for="cardName">Cardholder Name:</label>
                            <input type="text" id="cardName" name="cardName">
                            <br><br>
                            <label for="cardFilial">Card Filial:</label>
                            <input type="text" id="cardFilial" name="cardFilial">
                            <br><br>
                            <label for="cardCode">Card Security Code:</label>
                            <input type="text" id="cardCode" name="cardCode">
                            <br><br>
                            <input type="checkbox" id="save_card_info" name="save_card_info">
                            <label for="save_card_info">Save card information for future use</label>
                        </div>
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
            echo "<p><strong>@" . $comment['nickname'] . "</strong> <span style='font-size: 0.8em; color: gray;'>posted on " . $comment['creation_date'] . "</span></p>";
            echo "<p>" . $comment['comment'] . "</p>";
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
                <input type='hidden' name='reply_to' id='replyTo{$comment['ID']}' value='{$comment['ID']}'>
                <textarea name='comment' placeholder='Enter your reply'></textarea>
                <button type='submit'>Submit</button>
            </form>
            </div>";

                //КОМЕНТАРИИ НЕЛЬЗЯ УДАЛЯТЬ МЕНЯТЬ , НАДО ФИКСИТЬ!!!!!э
                //КОМЕНТАРИИ НЕЛЬЗЯ УДАЛЯТЬ МЕНЯТЬ , НАДО ФИКСИТЬ!!!!!
                //КОМЕНТАРИИ НЕЛЬЗЯ УДАЛЯТЬ МЕНЯТЬ , НАДО ФИКСИТЬ!!!!!
                //КОМЕНТАРИИ НЕЛЬЗЯ УДАЛЯТЬ МЕНЯТЬ , НАДО ФИКСИТЬ!!!!!
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
        var replyPopup = document.getElementById('replyPopup' + commentId);
        document.getElementById('replyTo' + commentId).value = commentId;  // Обновляем правильный элемент
        replyPopup.style.display = 'block';
        replyPopup.scrollIntoView({ behavior: 'smooth' });
    }

         // Показать/скрыть поля данных карты в зависимости от выбора метода оплаты
         document.getElementById("payment-method").addEventListener("change", function() {
            var cardDetails = document.getElementById("card_details");
            if (this.value === "card") {
                cardDetails.style.display = "block";
            } else {
                var saveCardInfo = document.getElementById("save_card_info");
                if (!saveCardInfo.checked) {
                    cardDetails.style.display = "none";
                }
            }
        });

        // Показать/скрыть поля данных карты в зависимости от выбора метода оплаты при загрузке страницы
        window.addEventListener("load", function() {
            var cardDetails = document.getElementById("card_details");
            var paymentMethod = document.getElementById("payment-method");
            if (paymentMethod.value === "card") {
                cardDetails.style.display = "block";
            } else {
                var saveCardInfo = document.getElementById("save_card_info");
                if (!saveCardInfo.checked) {
                    cardDetails.style.display = "none";
                }
            }
        });

        // Сохранить данные карты только если выбрана опция сохранения карты
        document.getElementById("save_card_info").addEventListener("change", function() {
            var cardDetails = document.getElementById("card_details");
            if (this.checked) {
                cardDetails.style.display = "block";
            } else {
                var paymentMethod = document.getElementById("payment-method");
                if (paymentMethod.value !== "card") {
                    cardDetails.style.display = "none";
                }
            }
        });
    </script>
<?php 
include_once '../includes/footer.php';
?>