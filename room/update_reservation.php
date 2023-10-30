<?php
session_start();

include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $room_id = $_POST['room_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $discount = $_POST['discount'];
    $cost = $_POST['cost'];
    $reserv_id = $_POST['reserv_id'];

    // Check if a reservation already exists for the given date and time
    $query = "SELECT * FROM reservation WHERE room_id='$room_id' AND date='$date' AND time='$time'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "A reservation already exists for this date and time. Please choose another date/time.";
        echo "<a href='../profile/profile_info.php'>Back to info page</a>";
        exit();
    }


    // Check if the logged-in user is an admin or a regular user
    $query = "UPDATE reservation
    SET date = '$date', time = '$time', cost = '$cost'
    WHERE ID = '$reserv_id'";
    
    if (mysqli_query($conn, $query)) {
        echo "Reservation updated successfully!";
        echo "<a href='../profile/profile_info.php'>Back to info page</a>";
    } else {
        echo "Error saving reservation: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
