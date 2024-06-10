<?php
include '../includes/dbcon.php';
require_once '../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем выбранные месяц и год из POST-запроса
$month = isset($_POST['selectedMonth']) ? $_POST['selectedMonth'] : date('m');
$year = isset($_POST['selectedYear']) ? $_POST['selectedYear'] : date('Y');

// Company header and title
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        h1 { color: #333; }
        h2 { color: #555; margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reservation Report</h1>
        <p>For the month of ' . $month . '/' . $year . '</p>
    </div>
';

// First query - Total cost by date
$sql1 = "SELECT SUM(cost) as total_cost, DATE(date) as date 
         FROM reservation 
         WHERE MONTH(date) = '$month' AND YEAR(date) = '$year'
         GROUP BY date";
$result1 = $conn->query($sql1);
$html .= '<h2>Total Cost by Date</h2>';
$html .= '<table><thead><tr><th>Date</th><th>Total Cost</th></tr></thead><tbody>';
$total_month_cost = 0;
while ($row = $result1->fetch_assoc()) {
    $html .= '<tr><td>' . $row['date'] . '</td><td>' . number_format($row['total_cost'], 2) . '</td></tr>';
    $total_month_cost += $row['total_cost'];
}
$html .= '<tr class="total"><td>Total</td><td>' . number_format($total_month_cost, 2) . '</td></tr>';
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
$html .= '<table><thead><tr><th>Day of the Week</th><th>Number of Reservations</th></tr></thead><tbody>';
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
$html .= '<table><thead><tr><th>Quest</th><th>Visitors</th></tr></thead><tbody>';
while ($row = $result3->fetch_assoc()) {
    $html .= '<tr><td>' . $row['name'] . '</td><td>' . $row['visitors'] . '</td></tr>';
}
$html .= '</tbody></table>';

// Footer
$html .= '
    <p>Report generated on ' . date('Y-m-d') . '</p>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('reservation_report_' . $month . '_' . $year . '.pdf', 'D');

// Close connection
$conn->close();
?>
