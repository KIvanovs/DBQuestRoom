<?php
include '../includes/dbcon.php'; // Correct path to your dbcon.php file

if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
    exit;
}

$date = mysqli_real_escape_string($conn, $_POST['date']);
$query = "SELECT timePeriod, cost FROM prices";
$allTimes = mysqli_query($conn, $query);

$reservedQuery = "SELECT time FROM reservation WHERE date = '$date'";
$reservedTimesResult = mysqli_query($conn, $reservedQuery);
$reservedTimes = [];
while ($row = mysqli_fetch_assoc($reservedTimesResult)) {
    $reservedTimes[] = $row['time'];
}

if (mysqli_num_rows($allTimes) > 0) {
    while ($row = mysqli_fetch_assoc($allTimes)) {
        $time = $row['timePeriod'];
        $cost = $row['cost'];
        $disabled = in_array($time, $reservedTimes) ? 'disabled' : '';
        echo "<button type='button' class='btn btn-outline-primary me-2 mb-2 $disabled'>$time ($cost EUR)</button>";
    }
} else {
    echo "No available times";
}

mysqli_close($conn);
?>