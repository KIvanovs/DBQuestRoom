<?php
include 'header.php';

// подключение к базе данных
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// проверка подключения
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// получение id пользователя из GET-параметра
$user_id = $_SESSION["user_id"];

// запрос для получения данных пользователя
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);

// проверка наличия данных пользователя
if (mysqli_num_rows($user_result) > 0) {
  $user_row = mysqli_fetch_assoc($user_result);
  
  // вывод информации о пользователе
  echo "<h2>{$user_row['nickname']}</h2>";
  echo "<p>Name: {$user_row['name']}</p>";
  echo "<p>Surname: {$user_row['surname']}</p>";
  echo "<p>Email: {$user_row['email']}</p>";
  echo "<p>Phone number: {$user_row['phoneNumber']}</p>";
  
  // запрос для получения данных о резервациях пользователя
  $reservation_query = "SELECT r.date, r.time, r.cost, r.payment, q.name, q.adress
                        FROM reservation r
                        INNER JOIN quests q ON r.room_id = q.id
                        WHERE r.client_id = $user_id";
  $reservation_result = mysqli_query($conn, $reservation_query);
  
  // проверка наличия данных о резервациях
  if (mysqli_num_rows($reservation_result) > 0) {
    echo "<h3>Reservations:</h3>";
    echo "<ul>";
    
    // вывод данных о резервациях
    while ($reservation_row = mysqli_fetch_assoc($reservation_result)) {
      echo "<li>Date: {$reservation_row['date']} | Time: {$reservation_row['time']} | Cost: {$reservation_row['cost']} | Quest: {$reservation_row['name']} ({$reservation_row['adress']}) | Payment type: {$reservation_row['payment']}</li>";
    }
    
    echo "</ul>";
  } else {
    echo "<p>No reservations found.</p>";
  }
 
  $admin_query = "SELECT phoneNumber FROM admin";
  $admin_result = mysqli_query($conn, $admin_query);
  
  if (mysqli_num_rows($admin_result) > 0) {
    $admin_row = mysqli_fetch_assoc($admin_result);
    echo "<br><br>";
    echo "<li>If you want to change reservation time or you person data, please contact to this phone: {$admin_row['phoneNumber']}</li>";
  } else {
    echo "<li>No admin found.</li>";
  }

} else {
  echo "<p>User not found.</p>";
}

mysqli_close($conn);
?>