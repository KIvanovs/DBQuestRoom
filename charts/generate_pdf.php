<?php
include '../includes/dbcon.php';
require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$html = '<h1>Reservation Report for ' . $month . '/' . $year . '</h1>';

// First query - Total cost by date
$sql1 = "SELECT SUM(cost) as total_cost, DATE(date) as date 
         FROM reservation 
         WHERE MONTH(date) = '$month' AND YEAR(date) = '$year'
         GROUP BY date";
$result1 = $conn->query($sql1);
$html .= '<h2>Total Cost by Date</h2>';
$html .= '<table border="1" cellpadding="10"><thead><tr><th>Date</th><th>Total Cost</th></tr></thead><tbody>';
$total_month_cost = 0;
while($row = $result1->fetch_assoc()) {
    $html .= '<tr><td>' . $row['date'] . '</td><td>' . $row['total_cost'] . '</td></tr>';
    $total_month_cost += $row['total_cost'];
}
$html .= '<tr><td><strong>Total</strong></td><td><strong>' . $total_month_cost . '</strong></td></tr>';
$html .= '</tbody></table>';

// Second query - Number of reservations by day of the week
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$sql2 = "SELECT WEEKDAY(date) AS day_of_week, COUNT(*) AS reservation_count 
         FROM reservation 
         WHERE MONTH(date) = '$month' AND YEAR(date) = '$year'
         GROUP BY day_of_week";
$result2 = $conn->query($sql2);

$reservations_by_day = array_fill(0, 7, 0);
while ($row = $result2->fetch_assoc()) {
    $reservations_by_day[$row['day_of_week']] = $row['reservation_count'];
}

$html .= '<h2>Number of Reservations by Day of the Week</h2>';
$html .= '<table border="1" cellpadding="10"><thead><tr><th>Day of the Week</th><th>Number of Reservations</th></tr></thead><tbody>';
foreach ($days_of_week as $index => $day_name) {
    $html .= '<tr><td>' . $day_name . '</td><td>' . $reservations_by_day[$index] . '</td></tr>';
}
$html .= '</tbody></table>';

// Third query - Visitors by rooms and quests
$sql3 = "SELECT q.name, COUNT(r.room_id) as visitors 
         FROM reservation r 
         JOIN quests q ON r.room_id = q.id 
         WHERE MONTH(r.date) = '$month' AND YEAR(r.date) = '$year'
         GROUP BY q.name";
$result3 = $conn->query($sql3);
$html .= '<h2>Visitors by Rooms and Quests</h2>';
$html .= '<table border="1" cellpadding="10"><thead><tr><th>Quest</th><th>Visitors</th></tr></thead><tbody>';
while($row = $result3->fetch_assoc()) {
    $html .= '<tr><td>' . $row['name'] . '</td><td>' . $row['visitors'] . '</td></tr>';
}
$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output('report.pdf', 'D'); // D - download the file

// Close the connection
$conn->close();
?>