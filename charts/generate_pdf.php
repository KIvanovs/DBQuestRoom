<?php
include '../includes/dbcon.php';
require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
echo 'mPDF loaded successfully';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$html = '<h1>Отчет по резервациям</h1>';

// Первый запрос
$sql1 = "SELECT SUM(cost) as total_cost, DATE(creation_date) as date FROM reservation GROUP BY creation_date";
$result1 = $conn->query($sql1);
$html .= '<h2>Общая стоимость по датам</h2>';
$html .= '<table border="1" cellpadding="10"><thead><tr><th>Дата</th><th>Общая стоимость</th></tr></thead><tbody>';
while($row = $result1->fetch_assoc()) {
    $html .= '<tr><td>' . $row['date'] . '</td><td>' . $row['total_cost'] . '</td></tr>';
}
$html .= '</tbody></table>';

// Второй запрос
$sql2 = "SELECT DATE(date) AS reservation_date, COUNT(*) AS reservation_count FROM reservation GROUP BY WEEKDAY(reservation_date)";
$result2 = $conn->query($sql2);
$html .= '<h2>Количество резерваций по дням недели</h2>';
$html .= '<table border="1" cellpadding="10"><thead><tr><th>День недели</th><th>Количество резерваций</th></tr></thead><tbody>';
while($row = $result2->fetch_assoc()) {
    $html .= '<tr><td>' . $row['reservation_date'] . '</td><td>' . $row['reservation_count'] . '</td></tr>';
}
$html .= '</tbody></table>';

// Третий запрос
$sql3 = "SELECT r.room_id, q.name, COUNT(r.room_id) as visitors FROM reservation r JOIN quests q ON r.room_id = q.id GROUP BY r.room_id, q.name";
$result3 = $conn->query($sql3);
$html .= '<h2>Посетители по комнатам и квестам</h2>';
$html .= '<table border="1" cellpadding="10"><thead><tr><th>ID комнаты</th><th>Квест</th><th>Посетители</th></tr></thead><tbody>';
while($row = $result3->fetch_assoc()) {
    $html .= '<tr><td>' . $row['room_id'] . '</td><td>' . $row['name'] . '</td><td>' . $row['visitors'] . '</td></tr>';
}
$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output('report.pdf', 'D'); // D - скачивание файла

// Закрытие соединения
$conn->close();
?>
