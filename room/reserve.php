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
    $user_id = $_SESSION['user_id'];

    // Check if a reservation already exists for the given date and time
    $query = "SELECT * FROM reservation WHERE room_id='$room_id' AND date='$date' AND time='$time'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "A reservation already exists for this date and time. Please choose another date/time.";
        echo "<a href='../room/quest_list.php'>Back to home page</a>";
        exit();
    }

    // Insert reservation into the database
    $insert_query = "INSERT INTO reservation (date, time, cost, payment, room_id, client_id, creation_date)
             VALUES ('$date', '$time', '$cost', '$payment', '$room_id', '$user_id', CURDATE())";
    mysqli_query($conn, $insert_query);

    // Check if save card information is checked and save the card details
    if (isset($_POST['save_card_info']) && $_POST['save_card_info'] === 'on') {
        $cardDate = $_POST['cardDate'];
        $cardNumber = $_POST['cardNumber'];
        $cardName = $_POST['cardName'];

        // Insert card information with user_id into the card table
        $card_query = "INSERT INTO card (cardDate, cardNumber, cardName, user_id) VALUES
                       ('$cardDate', '$cardNumber', '$cardName', '$user_id')";
        mysqli_query($conn, $card_query);
    }

    echo "Reservation saved successfully!";
    echo "<p><a href='../room/quest_list.php'>Back to home page</a></p>";
}

mysqli_close($conn);
?>