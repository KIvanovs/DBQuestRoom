<?php

$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DATE(date) AS reservation_date, COUNT(*) AS reservation_count FROM reservation GROUP BY WEEKDAY(reservation_date)";

$result = $conn->query($sql);

$dataPoints = array(
    "Mon" => 0,
    "Tue" => 0,
    "Wed" => 0,
    "Thu" => 0,
    "Fri" => 0,
    "Sat" => 0,
    "Sun" => 0
);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dayOfWeek = date("N", strtotime($row["reservation_date"]));
        $daysOfWeekEng = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        $dataPoints[$daysOfWeekEng[$dayOfWeek - 1]] = $row["reservation_count"];
    }
}

$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Number of Reservations by Day of the Week"
                },
                axisY: {
                    title: "Number of Reservations"
                },
                axisX: {
                    title: "Day of the Week"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { label: "Mon", y: <?php echo $dataPoints["Mon"]; ?> },
                        { label: "Tue", y: <?php echo $dataPoints["Tue"]; ?> },
                        { label: "Wed", y: <?php echo $dataPoints["Wed"]; ?> },
                        { label: "Thu", y: <?php echo $dataPoints["Thu"]; ?> },
                        { label: "Fri", y: <?php echo $dataPoints["Fri"]; ?> },
                        { label: "Sat", y: <?php echo $dataPoints["Sat"]; ?> },
                        { label: "Sun", y: <?php echo $dataPoints["Sun"]; ?> }
                    ]
                }]
            });
            chart.render();
        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>
