<?php
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');

$sql = "SELECT SUM(cost) as total_cost, creation_date as date 
        FROM reservation 
        WHERE YEAR(creation_date) = '$selectedYear' AND MONTH(creation_date) = '$selectedMonth'
        GROUP BY creation_date";
$result = $conn->query($sql);

$dataPoints = array();
while ($row = $result->fetch_assoc()) {
    $dataPoints[] = array(
        "x" => strtotime($row['date']) * 1000,
        "y" => $row['total_cost']
    );
}

$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            var chart = new CanvasJS.Chart("incomeChartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Income"
                },
                axisX: {
                    valueFormatString: "DD MMM"
                },
                axisY: {
                    title: "Total Cost",
                    includeZero: true,
                },
                data: [{
                    type: "splineArea",
                    color: "#6599FF",
                    xValueType: "dateTime",
                    xValueFormatString: "DD MMM",
                    yValueFormatString: "#,##0",
                    dataPoints: <?php echo json_encode($dataPoints); ?>
                }]
            });

            chart.options.data[0].dataPoints.forEach(function (point) {
                point.y = parseFloat(point.y);
            });

            chart.render();
        });
    </script>
</head>
<body>
<div id="incomeChartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>
