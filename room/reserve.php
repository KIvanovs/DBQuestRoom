<?php
session_start();

include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='../register_login/loginform.php'>log in as user</a> to make a reservation. " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $room_id = $_POST['quest_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $discount = $_POST['discount'];
    $payment = $_POST['payment_method'];
    $cost = $_POST['cost'];

    // Check if a reservation already exists for the given date and time
    $query = "SELECT * FROM reservation WHERE room_id='$room_id' AND date='$date' AND time='$time'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "A reservation already exists for this date and time. Please choose another date/time.";
        echo "<a href='../room/quest_list.php'>Back to home page</a>";
        exit();
    }

    // Insert reservation into the database
    $client_id = $_SESSION['user_id'];

    // Check if the logged-in user is an admin or a regular user
    $query = "INSERT INTO reservation (date, time, cost, payment, room_id, client_id , creation_date)
             VALUES ( '$date', '$time', '$cost','$payment', '$room_id' , '$client_id' , CURDATE())";
    
    if (mysqli_query($conn, $query)) {
        echo "Reservation saved successfully!";
        echo "<p><a href='../room/quest_list.php'>Back to home page</a> </p>";
    } else {
        echo "Error saving reservation: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
