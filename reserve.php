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
    $room_id = $_POST['room_id'];
    $date = $_POST['date'];
    $time = $_COOKIE['time'];
    $discount = $_POST['discount'];
    if($time == '20:30' || $time == '22:00'){
        $total = 80;
        
    }
    else{
        $total = 60;  
    }
    $totaldiscount = $total / 100 * $discount ;
    $cost = $total - $totaldiscount ;
    $cost = (round($cost,2));

    // Insert reservation into the database
    $client_id = $_SESSION['user_id'];

    // Check if the logged-in user is an admin or a regular user
    $query = "INSERT INTO reservation (date, time, cost, room_id, client_id) VALUES ( '$date', '$time', '$cost', '$room_id' , '$client_id')";
    
    if (mysqli_query($conn, $query)) {
        echo "Reservation saved successfully!";
        echo $time;
    } else {
        echo "Error saving reservation: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
