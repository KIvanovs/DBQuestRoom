<?php

include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$date = $_GET['date'];
$room_id = $_GET['room_id'];

$query = "SELECT timePeriod FROM reservation WHERE date = '$date' AND room_id = $room_id";
$result = mysqli_query($conn, $query);

$bookedTimes = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimes[] = $row['timePeriod'];
    }
}

echo json_encode($bookedTimes);

mysqli_close($conn);
?>
