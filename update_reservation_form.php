<?php

include 'header.php';

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
        echo "<h2>Update Reservation</h2>";
        echo "<form method='post' action='update_reservation.php'>";
        echo "<input type='hidden' name='reserv_id' value='" . $reserv_id . "'>";
        echo "<input type='hidden' name='discount' value='" . $row['discount'] . "'>";
        echo "<input type='hidden' name='room_id' value='" . $row['room_id'] . "'>";
        echo "<label for='date'>Date:</label>";
        echo "<input type='date' id='date' name='date' value='" . $row['date'] . "' min='" . date('Y-m-d') . "'>";
        echo "<br><br>";
        echo "<label for='room_id'>Time (was):</label>";
        echo "<p>" . $row['time'] . "</p>";
        echo "<button type='button' name='time' value='10:00' onclick='selectTime(\"10:00\", 60)'>10:00 (60 EUR)</button>";
        echo "<button type='button' name='time' value='11:30' onclick='selectTime(\"11:30\", 60)'>11:30 (60 EUR)</button>";
        echo "<button type='button' name='time' value='13:30' onclick='selectTime(\"13:30\", 60)'>13:30 (60 EUR)</button>";
        echo "<button type='button' name='time' value='14:30' onclick='selectTime(\"14:30\", 60)'>14:30 (60 EUR)</button>";
        echo "<button type='button' name='time' value='16:00' onclick='selectTime(\"16:00\", 60)'>16:00 (60 EUR)</button>";
        echo "<button type='button' name='time' value='17:30' onclick='selectTime(\"17:30\", 60)'>17:30 (60 EUR)</button>";
        echo "<button type='button' name='time' value='19:00' onclick='selectTime(\"19:00\", 60)'>19:00 (60 EUR)</button>";
        echo "<button type='button' name='time' value='20:30' onclick='selectTime(\"20:30\", 80)'>20:30 (80 EUR)</button>";
        echo "<button type='button' name='time' value='22:00' onclick='selectTime(\"22:00\", 80)'>22:00 (80 EUR)</button>";        
        echo "<br><br>";
        
        echo "<div id='payment-modal' class='modal'>
                    <div class='modal-content'>
                        <h2>Payment</h2>
                        <p>You have selected:</p>
                        <p id='selected-time'></p>
                        <p>Total price: <span id='total-price'></span> EUR</p>
                    </div>
                </div>";
        echo "<input type='submit' name='submit' value='Update Reservation'>";
        echo "</form>";
        ?>

        <script>
                
                var discount = <?php echo $row['discount'] ?>;

                function selectTime(time, price) {
                    var selectedTime = time;
                    var total = price;
                    
                    totaldiscount = total / 100 * discount ;
                    total = total - totaldiscount ;
                    cost = parseFloat(total.toFixed(2));

                    document.getElementById("selected-time").innerHTML = selectedTime;
                    document.getElementById("total-price").innerHTML = cost;
                    document.getElementById("payment-modal").style.display = "block";

                    cookie(time);
                    
                    // $.ajax({
                    //     type: "POST",
                    //     url: "reserve.php",
                    //     data: { 
                    //         time: selectedTime, 
                    //         cost: cost 
                    //     },
                    //     success: function(response) {
                    //         console.log(response);
                    //     }
                    // });
                    

                }
                function cookie(time){
                    document.cookie = "time="+time;
                }

            </script>
            <?php
    } else {
        // The reservation was not found, show an error message
        echo "Reservation not found.";
    }

}

mysqli_close($conn);

?>