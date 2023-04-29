<?php
session_start();
// Connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get reservation data from POST request
    $reserv_id = $_POST['reserv_id'];
    $date = $_POST['date'];
    $time = $_COOKIE['time'];
    $discount = $_POST['discount'];
    $room_id = $_POST['room_id'];
    // $cost = $_POST['cost'];
    // $time = $_POST['time'];
    if($time == '20:30' || $time == '22:00'){
        $total = 80;
        
    }
    else{
        $total = 60;  
    }
    $totaldiscount = $total / 100 * $discount ;
    $cost = $total - $totaldiscount ;
    $cost = (round($cost,2));

    // Check if a reservation already exists for the given date and time
    $query = "SELECT * FROM reservation WHERE room_id='$room_id' AND date='$date' AND time='$time'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "A reservation already exists for this date and time. Please choose another date/time.";
        echo "<a href='profile_info.php'>Back to info page</a>";
        exit();
    }


    // Check if the logged-in user is an admin or a regular user
    $query = "UPDATE reservation
    SET date = '$date', time = '$time', cost = '$cost'
    WHERE ID = '$reserv_id'";
    
    if (mysqli_query($conn, $query)) {
        echo "Reservation updated successfully!";
        echo "<a href='profile_info.php'>Back to info page</a>";
    } else {
        echo "Error saving reservation: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
