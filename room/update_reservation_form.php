<?php

include '../includes/header.php';

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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the reservation ID from the form
    $reserv_id = $_POST['reserv_id'];

    // Query the database for the reservation
    $query = "SELECT r.*, q.* FROM reservation r
              JOIN quests q ON r.room_id = q.ID
              WHERE r.ID = " . $reserv_id;
    $result = mysqli_query($conn, $query);

    // Check if the reservation was found
    if (mysqli_num_rows($result) > 0) {
        // Display the reservation data in a form for updating
        $row = mysqli_fetch_assoc($result);
        ?>

<h2>Update Reservation</h2>
<form method='post' action='update_reservation.php'>
    <input type='hidden' name='reserv_id' value='<?php echo $reserv_id; ?>'>
    <input type='hidden' name='room_id' id='room-id' value='<?php echo $row['room_id']; ?>'>
    <input type='hidden' name='time' id='selected-time'>
    <input type='hidden' name='cost' id='cost-input'>

    <div class="d-flex justify-content-between" style="margin-top: 20px;">
        <div id="calendar-container" class="card" style="width: 50%; background-color: #f9f9f9; padding: 20px; margin-top: 20px;">
            <label for="date" class="card-header" style="font-size: 1.2em;">Date:</label>
            <input type="text" id="date" name="date" class="form-control" value="<?php echo $row['date']; ?>" min="<?php echo date('Y-m-d'); ?>">

            <script>
                var picker = new Pikaday({
                    container: document.getElementById('calendar-container'),
                    field: document.getElementById('date'),
                    bound: false,
                    format: 'YYYY-MM-DD',
                    minDate: new Date(),
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

        <div style="width: 45%; padding: 20px; margin-top: 20px;">
            <label>Time:</label><br>
            <?php
                $query = "SELECT timePeriod, cost FROM prices";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($timeRow = mysqli_fetch_assoc($result)) {
                        $time = $timeRow['timePeriod'];
                        $cost = $timeRow['cost'];
                        echo "<button type='button' class='btn btn-primary my-1 mb-1' name='time' value='$time' onclick='chooseTime(\"$time\", $cost, this)'>$time ($cost EUR)</button>";
                    }
                } else {
                    echo "No results";
                }
            ?>
        </div>
    </div>

    <input type='submit' name='submit' value='Update Reservation'>
</form>

<div id='payment-modal' class='modal'>
    <div class='modal-content'>
        <h2>Payment</h2>
        <p>You have selected:</p>
        <p id='selected-time'></p>
        <p>Total price: <span id='total-price'></span> EUR</p>
    </div>
</div>

<script>
    document.getElementById('date').addEventListener('change', function() {
        var selectedDate = this.value;
        var roomId = document.getElementById('room-id').value;
        fetch('../room/update_reserve_check.php?date=' + selectedDate + '&room_id=' + roomId)
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

        <?php
    } else {
        // The reservation was not found, show an error message
        echo "Reservation not found.";
    }
}

mysqli_close($conn);

?>
