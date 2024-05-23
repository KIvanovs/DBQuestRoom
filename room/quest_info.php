

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

<style>
/* Скрытие выпадающих списков выбора месяца и года */
.pika-select-month,
.pika-select-year {
    display: none;
}

</style>

<div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f8f9fa;">
    <div class="card" style="width: 50rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <img src="<?php echo $photoPath; ?>" class="card-img-top" alt="photo of <?php echo $name; ?>" style="width: 100%; height: 300px; object-fit: cover;">
        <div class="card-body">
            <h2 class="card-title" style="font-weight: bold;"><?php echo $name; ?></h2>
            <p class="card-text" style="font-size: 1.1em;">Category: <strong><?php echo $category; ?></strong></p>
            <p class="card-text" style="font-size: 1.1em;">Address: <strong><?php echo $address; ?></strong></p>
            <p class="card-text" style="font-size: 1.1em;">Number of people: <strong><?php echo $peopleAmount; ?></strong></p>
            <p class="card-text" style="font-size: 1.1em;">Age limit: <strong><?php echo $ageLimit; ?></strong> +</p>
            <p class="card-text" style="font-size: 1.1em;">Description: <strong><?php echo $description; ?></strong></p>

            <form action="../room/reserve.php" method="post">
                <h3 style="margin-top: 20px;">Reservation</h3>
                <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
                <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                <input type="hidden" name="time" id="selected-time">
                <input type="hidden" name="cost" id="cost-input">
                <div class="d-flex justify-content-between" style="margin-top: 20px;">
                    <div id="calendar-container" class="card" style="width: 50%; background-color: #f9f9f9; padding: 20px; margin-top: 20px;">
                        <label for="date" class="card-header" style="font-size: 1.2em;">Date:</label>
                        <input type="text" id="date" name="date" class="form-control">
                        <script>
                            var picker = new Pikaday({
                                container: document.getElementById('calendar-container'),
                                field: document.getElementById('date'),
                                bound: false,
                                format: 'YYYY-MM-DD',
                                minDate: new Date(), // Устанавливаем минимальную дату на сегодняшний день
                                i18n: {
                                    previousMonth : 'Previous month',
                                    nextMonth     : 'Next month',
                                    months        : ['January','February','March','April','May','June','July','August','September','October','November','December'],
                                    weekdays      : ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                                    weekdaysShort : ['Su','Mo','Tu','We','Th','Fr','Sa']
                                },
                                firstDay: 1,
                                onSelect: function(date) {
                                    document.getElementById('date').value = this.getMoment().format('YYYY-MM-DD');
                                },
                                onDraw: function() {
                                    var prevButton = document.querySelector('.pika-prev');
                                    var nextButton = document.querySelector('.pika-next');
                                    var days = document.querySelectorAll('.pika-button.pika-day');

                                    // Apply Bootstrap styles
                                    if (prevButton && nextButton) {
                                        prevButton.classList.add('btn', 'btn-primary');
                                        nextButton.classList.add('btn', 'btn-primary');
                                    }
                                    days.forEach(function(day) {
                                        day.classList.add('btn', 'btn-light'); // Apply bootstrap button class

                                        // Disable past dates
                                        var date = new Date(day.getAttribute('data-pika-year'), day.getAttribute('data-pika-month'), day.getAttribute('data-pika-day'));
                                        if (date < new Date()) {
                                            day.classList.add('btn-secondary');
                                            day.disabled = true;
                                            day.style.pointerEvents = 'none'; // Make button unclickable
                                            day.style.backgroundColor = '#6c757d'; // Darken background
                                            day.style.color = '#fff'; // Change text color for contrast
                                        }
                                    });
                                }
                            });
                            </script>
                    </div>
                
                <br><br>
                <div style="width: 45%; padding: 20px; margin-top: 20px;">
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
                            echo "<button type='button' class='btn btn-primary my-1 mb-1' name='time' value='$time' onclick='chooseTime(\"$time\", $cost, this)'>$time ($cost EUR)</button>";

                        }
                    } else {
                        echo "No results";
                    }
                    mysqli_close($conn);
                
                ?>
                </div>
                </div>
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

                    function chooseTime(time, cost, element) {
                        console.log('Time selected:', time, 'Cost:', cost);  // Лог для отладки
                        document.getElementById('selected-time').value = time;
                        document.getElementById('cost-input').value = cost;
                        document.getElementById('timePeriod').innerText = time;
                        document.getElementById('cost').innerText = cost + ' EUR';

                        const timeButtons = document.querySelectorAll('button[name="time"]');
                        timeButtons.forEach(button => {
                            button.classList.remove('selected');
                        });
                        element.classList.add('selected');
                        checkValidityForReservation();
                    }

                    function checkValidityForReservation() {
                        const date = document.getElementById('date').value;
                        const time = document.getElementById('selected-time').value;
                        const reserveButton = document.getElementById('reserve-button');
                        console.log('Date:', date, 'Time:', time);  // Лог для отладки

                        if (date && time) {
                            reserveButton.disabled = false;
                            console.log('Reserve button enabled');  // Лог для отладки
                        } else {
                            reserveButton.disabled = true;
                            console.log('Reserve button disabled');  // Лог для отладки
                        }
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        checkValidityForReservation();
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

                        <button type="submit" id="reserve-button" class="btn btn-primary" disabled>Reserve</button>
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
</div>

    <?php
    include '../includes/dbcon.php';
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    ?>
    <div class="container d-flex justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Leave a Comment</h5>
                    <form action="../comment/quest_comment.php" method="post" class="mb-3">
                        <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
                        <input type="hidden" name="reply_to" id="replyTo" value="">

                        <div class="form-group mb-3">
                            <label for="comment" class="form-label">Enter your comment</label>
                            <textarea name="comment" id="comment" class="form-control" placeholder="Enter your comment" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    
    $comments_query = "
    SELECT c.*, u.nickname, a.name AS admin_name
    FROM comment c
    LEFT JOIN users u ON c.user_id = u.ID
    LEFT JOIN admin a ON c.admin_id = a.ID
    WHERE c.quest_id='$quest_id'
    ORDER BY c.ID DESC
";

    $comments_result = mysqli_query($conn, $comments_query);
    $comments = [];
    while ($comment = mysqli_fetch_assoc($comments_result)) {
        $comments[$comment['ID']] = $comment;
        $comments[$comment['ID']]['replies'] = [];
    }

    // Organize comments into a nested structure
    foreach ($comments as $id => $comment) {
        if ($comment['reply_to'] > 0 && isset($comments[$comment['reply_to']])) {
            $comments[$comment['reply_to']]['replies'][] = &$comments[$id];
        }
    }

    // Function to display comments
    function display_comments($comments, $reply_to = 0, $level = 0) {
        $sorted_comments = array_filter($comments, function($comment) use ($reply_to) {
            return $comment['reply_to'] == $reply_to;
        });

        usort($sorted_comments, function($a, $b) {
            return strtotime($b['creation_date']) - strtotime($a['creation_date']);
        });

        foreach ($sorted_comments as $comment) {
            $margin = $level > 0 ? "20px" : "0px";
            echo "<div class='card mb-3 mx-auto' style='margin-left: $margin; max-width: 600px;'>";
            echo "<div class='card-body'>";


             // Display the user's nickname or admin's name
            if (!empty($comment['admin_name'])) {
                echo "<h5 class='card-title'><strong>Admin @ " . $comment['admin_name'] . "</strong></h5>";
            } else {
                    echo "<h5 class='card-title'><strong>@" . $comment['nickname'] . "</strong></h5>";
            }



            echo "<h6 class='card-subtitle mb-2 text-muted'>" . $comment['creation_date'] . "</h6>";
             // Обертка для комментария и кнопок
            echo "<div class='d-flex justify-content-between align-items-center'>";
            
            // Отображение комментария
            echo "<p id='comment-text-{$comment['ID']}' class='card-text'>" . $comment['comment'] . "</p>";
            
            // Форма для редактирования комментария
            echo "<form id='edit-form-{$comment['ID']}' method='post' action='../comment/edit_comment.php' style='display: none;' class=' w-100'>";
            echo "<input type='hidden' name='comment_id' value='{$comment['ID']}'>";
            echo "<input type='hidden' name='quest_id' value='{$comment['quest_id']}'>";
            echo "<textarea name='comment' class='form-control me-1'>" . $comment['comment'] . "</textarea>";
            echo "<button type='submit' class='btn btn-success btn-sm me-1'>Save</button>";
            echo "<button type='button' class='btn btn-secondary btn-sm' onclick='toggleEditMode({$comment['ID']})'>Cancel</button>";
            echo "</form>";

            // Проверка, является ли пользователь автором комментария
            if (isset($comment['user_id']) && isset($_SESSION['user_id']) && $comment['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['admin_id']) && $_SESSION['admin_id'])) {
                
                echo "<div class='d-flex justify-content-between align-items-center'>";

                if (isset($comment['user_id']) && isset($_SESSION['user_id']) && $comment['user_id'] == $_SESSION['user_id']) {
                    echo "<button type='button' class='btn btn-warning btn-sm mx-1' onclick='toggleEditMode({$comment['ID']})'>Update</button>";
                }
                
                // Форма для кнопки "Удалить"
                echo "<form method='post' action='../comment/delete_comment.php' class='delete-comment-form' data-comment-id='{$comment['ID']}'>";
                echo "<input type='hidden' name='comment_id' value='{$comment['ID']}'>";
                echo "<input type='hidden' name='quest_id' value='{$comment['quest_id']}'>"; 
                echo "<button type='submit' class='btn btn-danger btn-sm mx-1 delete-comment-btn'>Delete</button>";
                echo "</form>";
                echo "</div>";
            }
            
            echo "</div>";

            echo "<button type='button' class='btn btn-primary mt-2 reply-btn' data-comment-id='{$comment['ID']}'>Reply</button>";

            // Скрытая форма ответа
            echo "<div id='reply-form-{$comment['ID']}' class='reply-form' style='display: none; margin-top: 10px;'>";
            echo "<form action='../comment/quest_comment.php' method='post' class='mb-3 p-3 bg-light border rounded'>";
            echo "<input type='hidden' name='quest_id' value='{$comment['quest_id']}'>";
            echo "<input type='hidden' name='reply_to' value='{$comment['ID']}'>";
            echo "<div class='form-group mb-3'>";
            echo "<label for='reply-comment-{$comment['ID']}' class='form-label'>Enter your reply</label>";
            echo "<textarea name='comment' id='reply-comment-{$comment['ID']}' class='form-control' placeholder='Enter your reply' rows='3'></textarea>";
            echo "</div>";
            echo "<div class='text-end'>";
            echo "<button type='submit' class='btn btn-primary'>Submit</button>";
            echo "</div>";
            echo "</form>";
            echo "</div>";

            // Кнопка для отображения/скрытия ответов
            echo "<button type='button' class='btn btn-secondary mt-2 replies-btn' data-comment-id='{$comment['ID']}'>Replies</button>";

            // Скрытая область для ответов
            echo "<div id='replies-{$comment['ID']}' class='replies' style='display: none; margin-top: 10px;'>";
            display_comments($comments, $comment['ID'], $level + 1);
            echo "</div>";

            echo "</div>"; // Close card-body
            echo "</div>"; // Close card
        }
    }

    // Display all comments
    echo "<div class='container'>";
    display_comments($comments);
    echo "</div>";

    mysqli_close($conn);
    ?>
    <script>
    function toggleEditMode(commentId) {
        const commentText = document.getElementById('comment-text-' + commentId);
        const editForm = document.getElementById('edit-form-' + commentId);

        if (commentText.style.display === 'none') {
            commentText.style.display = 'block';
            editForm.style.display = 'none';
        } else {
            commentText.style.display = 'none';
            editForm.style.display = 'block';
        }
    }
    </script>


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

        document.addEventListener('DOMContentLoaded', function () {
            var replyButtons = document.querySelectorAll('.reply-btn');

            replyButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var commentId = this.getAttribute('data-comment-id');
                    var replyForm = document.getElementById('reply-form-' + commentId);

                    if (replyForm.style.display === 'none') {
                        replyForm.style.display = 'block';
                    } else {
                        replyForm.style.display = 'none';
                    }
                });
            });

            var repliesButtons = document.querySelectorAll('.replies-btn');

            repliesButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var commentId = this.getAttribute('data-comment-id');
                    var repliesDiv = document.getElementById('replies-' + commentId);

                    if (repliesDiv.style.display === 'none') {
                        repliesDiv.style.display = 'block';
                    } else {
                        repliesDiv.style.display = 'none';
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