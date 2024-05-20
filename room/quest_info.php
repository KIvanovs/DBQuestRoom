

<?php
session_start();  
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

<div class="card" style="width: 18rem;">  <!-- Card with fixed width -->
    <img src="<?php echo $photoPath; ?>" class="card-img-top" alt="photo of <?php echo $name; ?>" style="max-width: 200px; max-height: 200px;">
    <div class="card-body">
        <h5 class="card-title"><?php echo $name; ?></h5>
        <p class="card-text">Category: <?php echo $category; ?></p>
        <p class="card-text">Address: <?php echo $address; ?></p>
        <p class="card-text">Number of people: <?php echo $peopleAmount; ?></p>
        <p class="card-text">Age limit: <?php echo $ageLimit; ?></p>
        <p class="card-text">Description: <?php echo $description; ?></p>

            <form action="../room/reserve.php" method="post">
                <h1>Reservation</h1>
                <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
                <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                <input type="hidden" name="time" id="selected-time">
                <input type="hidden" name="cost" id="cost-input">
                <div id="calendar-container" class="card" style="width: auto; background-color: #f9f9f9;">
                    <label for="date" class="card-header">Date:</label>
                    <input type="text" id="date" name="date" class="form-control">
                

                    <script>
                    var picker = new Pikaday({
                        container: document.getElementById('calendar-container'),
                        field: document.getElementById('date'),
                        bound: false,
                        format: 'YYYY-MM-DD',
                        i18n: {
                            previousMonth : 'Предыдущий месяц',
                            nextMonth     : 'Следующий месяц',
                            months        : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                            weekdays      : ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
                            weekdaysShort : ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
                        },
                        firstDay: 1,
                        onSelect: function(date) {
                            document.getElementById('date').value = this.getMoment().format('YYYY-MM-DD');
                        },
                        onDraw: function() {
                            var prevButton = document.querySelector('.pika-prev');
                            var nextButton = document.querySelector('.pika-next');
                            var days = document.querySelectorAll('.pika-button.pika-day');

                            // Apply Bootstrap styles without removing existing classes
                            if (prevButton && nextButton) {
                                prevButton.classList.add('btn', 'btn-primary');
                                nextButton.classList.add('btn', 'btn-primary');
                            }
                            days.forEach(function(day) {
                                day.classList.add('btn', 'btn-light'); // Apply bootstrap button class without removing pika-day
                            });
                        }
                    });
                    </script>
                </div>
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
                            echo "<button type='button' class='btn btn-primary my-1 mb-1' name='time' value='$time' onclick='selectTime(\"$time\", $cost, this)'>$time ($cost EUR)</button>";

                        }
                    } else {
                        echo "No results";
                    }
                    mysqli_close($conn);
                ?>
                <script>
                document.getElementById('date').addEventListener('change', function() {
                    var selectedDate = this.value;
                    fetch('../room/reserve_check.php?date=' + selectedDate)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(bookedTimes => {
                            const timeButtons = document.querySelectorAll('button[name="time"]');
                            // Reset all buttons to default (available) state first
                            timeButtons.forEach(button => {
                                button.classList.add('btn-primary');
                                button.classList.remove('btn-danger');
                                button.disabled = false;
                            });
                            // Apply 'booked' status to appropriate buttons
                            bookedTimes.forEach(bookedTime => {
                                const bookedButton = Array.from(timeButtons).find(button => button.value === bookedTime);
                                if (bookedButton) {
                                    bookedButton.classList.remove('btn-primary');
                                    bookedButton.classList.add('btn-danger');
                                    bookedButton.disabled = true;
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching data: ', error);
                        });
                });
                </script>

                <br><br>
                <div class="modal-content card">
                    <div class="card-header">
                        <h2>Payment</h2>
                    </div>
                    <div class="card-body">
                        <p>You have selected: <span id="timePeriod"></span></p>
                        <p>Total price: <span id="cost"></span></p>

                        <div class="form-group">
                            <label for="payment-method">Payment method:</label>
                            <select id="payment-method" name="payment_method" class="form-control" onchange="togglePaymentMethod()">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                            </select>
                        </div>
                        <?php
                        include '../includes/dbcon.php';
                        
                        if (!isset($_SESSION['user_id'])) {
                            error_log('User ID is not set in the session.');
                            $userId = 'default_value'; // или какое-то безопасное значение по умолчанию
                        } else {
                            $userId = $_SESSION['user_id'];
                        }

                        $sql = "SELECT cardDate, cardNumber, cardName FROM card WHERE user_id = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, 'i', $userId);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        $cards = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        $hasSavedCards = count($cards) > 0;
                        
                        mysqli_free_result($result);
                        mysqli_close($conn);
                        ?>
                        

                        <div id="card_details" style="display: none;">
                            <div class="form-group">
                                <label for="cardDate">Card Expiry Date:</label>
                                <input type="text" id="cardDate" name="cardDate" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cardNumber">Card Number:</label>
                                <input type="text" id="cardNumber" name="cardNumber" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cardName">Cardholder Name:</label>
                                <input type="text" id="cardName" name="cardName" class="form-control">
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" id="save_card_info" name="save_card_info" class="form-check-input">
                                <label for="save_card_info" class="form-check-label">Save card information for future use</label>
                            </div>
                        </div>

                        <!-- HTML part for displaying the cards if available -->
                        <?php if ($hasSavedCards): ?>
                            <div class="form-group">
                                <label for="select_saved_card">Use a saved card:</label>
                                <select id="select_saved_card" name="select_saved_card" class="form-control" onchange="toggleCardDetails(this)">
                                <option value="">-- Select a saved card --</option>
                                    <?php foreach ($cards as $card): ?>
                                        <option value="<?= htmlspecialchars(json_encode([
                                                'cardDate' => $card['cardDate'],
                                                'cardNumber' => $card['cardNumber'],
                                                'cardName' => $card['cardName']
                                            ])) ?>">
                                            <?= htmlspecialchars($card['cardName']) ?> (Expires: <?= htmlspecialchars($card['cardDate']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <p>No saved cards available.</p>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Reserve</button>
                    </div>
                </div>
                <script>

                        document.addEventListener('DOMContentLoaded', function () {
                            togglePaymentMethod(); // Ensure correct display when the page loads
                        });

                        function togglePaymentMethod() {
                            var paymentMethod = document.getElementById('payment-method').value;
                            var cardDetailsDiv = document.getElementById('card_details');
                            var savedCardsDiv = document.getElementById('select_saved_card').parentNode;

                            if (paymentMethod === 'card') {
                                cardDetailsDiv.style.display = 'block';
                                if (savedCardsDiv) savedCardsDiv.style.display = 'block';
                            } else {
                                cardDetailsDiv.style.display = 'none';
                                if (savedCardsDiv) savedCardsDiv.style.display = 'none';
                            }
                        }

                    document.getElementById('payment-method').addEventListener('change', function () {
                        var cardDetails = document.getElementById('card_details');
                        var select = document.getElementById('select_saved_card');

                        if (this.value === 'card') {
                            cardDetails.style.display = 'block';
                            if (select && select.value) {
                                fillCardDetails(JSON.parse(select.value));
                            }
                        } else {
                            cardDetails.style.display = 'none';
                        }
                    });

                    function toggleCardDetails(select) {
                        var cardDetails = document.getElementById('card_details');
                        if (select.value) {
                            fillCardDetails(JSON.parse(select.value));
                        } else {
                            clearCardDetails();
                        }
                    }

                    function fillCardDetails(card) {
                        document.getElementById('cardDate').value = card.cardDate;
                        document.getElementById('cardNumber').value = card.cardNumber;
                        document.getElementById('cardName').value = card.cardName;
                    }

                    function clearCardDetails() {
                        document.getElementById('cardDate').value = '';
                        document.getElementById('cardNumber').value = '';
                        document.getElementById('cardName').value = '';
                    }
                </script>
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
    echo "<div class='container mt-4'>";
    if (mysqli_num_rows($comments_result) > 0) {
        while ($comment = mysqli_fetch_assoc($comments_result)) {
            echo "<div class='card mb-3 " . ($comment['reply_to'] > 0 ? 'border-primary' : '') . "'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'><strong>@" . $comment['nickname'] . "</strong></h5>";
            echo "<h6 class='card-subtitle mb-2 text-muted'>" . $comment['creation_date'] . "</h6>";
            echo "<p class='card-text'>" . $comment['comment'] . "</p>";
            if ($comment['reply_to'] > 0) {
                $reply_to_query = "SELECT nickname FROM users WHERE ID=" . $comment['reply_to'];
                $reply_to_result = mysqli_query($conn, $reply_to_query);
                if (mysqli_num_rows($reply_to_result) > 0) {
                    $reply_to_user = mysqli_fetch_assoc($reply_to_result);
                    echo "<small class='text-muted'>Reply to: " . $reply_to_user['nickname'] . "</small>";
                }
            }
            echo "<button type='button' class='btn btn-primary mt-2 reply-btn' data-comment-id='{$comment['ID']}'>Reply</button>";
            echo "</div>";
            // Скрытый блок формы
            echo "<div class='reply-form-container' id='reply-form-{$comment['ID']}' style='display:none;'>";
            echo "<form class='reply-form' action='../comment/quest_comment.php' method='post'>";
            echo "<input type='hidden' name='quest_id' value='<?php echo $quest_id; ?>'>";
            echo "<input type='hidden' name='reply_to' id='replyTo' value=''>";
            echo "<textarea class='form-control' name='comment' placeholder='Enter your reply'></textarea>";
            echo "<button type='submit' class='btn btn-primary mt-2'>Submit</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            //Сделать отдельыне 2 формы под реплай и под комента чтобы не ебать мозг себе
            //Сделать отдельыне 2 формы под реплай и под комента чтобы не ебать мозг себе
            //Сделать отдельыне 2 формы под реплай и под комента чтобы не ебать мозг себе

            
        }
    } else {
        echo "<p>No comments found.</p>";
    }
    echo "</div>";
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

        document.addEventListener("DOMContentLoaded", function() {
            var replyButtons = document.querySelectorAll('.reply-btn');
            replyButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var commentId = this.getAttribute('data-comment-id');
                    var replyForm = document.getElementById('reply-form-' + commentId);
                    if (replyForm.style.display === 'none') {
                        replyForm.style.display = 'block';
                    } else {
                        replyForm.style.display = 'none';
                    }
                });
            });
        });

        // Обновлённая функция для отображения формы ответа под комментарием
        function replyToComment(commentId) {
            var replyForm = document.getElementById('reply-form-' + commentId);
            if (replyForm.style.display === 'none') {
                replyForm.style.display = 'block';
                replyForm.scrollIntoView({ behavior: 'smooth' });
            } else {
                replyForm.style.display = 'none';
            }
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

        // Показать/скрыть поля данных карты при загрузке страницы
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