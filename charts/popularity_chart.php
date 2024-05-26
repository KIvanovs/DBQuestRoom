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

$sql = "SELECT r.room_id, q.name, COUNT(r.room_id) as visitors 
        FROM reservation r
        JOIN quests q ON r.room_id = q.id
        WHERE YEAR(r.date) = '$selectedYear' AND MONTH(r.date) = '$selectedMonth'
        GROUP BY r.room_id, q.name";
$result = $conn->query($sql);

$dataPoints = array();
while ($row = $result->fetch_assoc()) {
    $dataPoints[] = array(
        "y" => $row['visitors'],
        "label" => $row['name']
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
            var chart = new CanvasJS.Chart("popularityChartContainer", {
                animationEnabled: true,
                title: {
                    text: "Number of Visitors per Quest Room"
                },
                data: [{
                    type: "pie",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - #percent%",
                    yValueFormatString: "#,##0",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        });
    </script>
</head>
<body>
<div id="popularityChartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>
