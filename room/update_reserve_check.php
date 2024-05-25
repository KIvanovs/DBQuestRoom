<?php
include '../includes/dbcon.php'; // Подключение к базе данных

if(isset($_GET['date']) && isset($_GET['quest_id'])) {
    $date = $_GET['date'];
    $quest_id = $_GET['quest_id'];
    $query = "SELECT time FROM reservation WHERE date = '$date' AND room_id = '$quest_id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        http_response_code(500); // Внутренняя ошибка сервера
        echo json_encode(['error' => 'Database query failed']);
        exit;
    }

    $bookedTimes = [];
    while($row = mysqli_fetch_assoc($result)) {
        $bookedTimes[] = $row['time']; // Убедитесь, что 'time' это правильное название столбца
    }
    echo json_encode($bookedTimes);
    mysqli_close($conn);
} else {
    http_response_code(400); // Ошибка запроса
    echo json_encode(['error' => 'Date or quest_id parameter missing']);
}
?>
