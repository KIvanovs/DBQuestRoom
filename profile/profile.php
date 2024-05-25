<?php
$pageTitle = "Profile info" ;
include '../includes/header.php';
include '../includes/dbcon.php';

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
  echo "<div class='container mt-5'>";
  echo "<div class='card'>";
  echo "<div class='card-header'>";
  echo "<h2>{$user_row['nickname']}</h2>";
  echo "</div>";
  echo "<div class='card-body'>";
  echo "<p><strong>Name:</strong> {$user_row['name']}</p>";
  echo "<p><strong>Surname:</strong> {$user_row['surname']}</p>";
  echo "<p><strong>Email:</strong> {$user_row['email']}</p>";
  echo "<p><strong>Phone number:</strong> {$user_row['phoneNumber']}</p>";
  echo "</div>";
  echo "</div>";

  // запрос для получения данных о резервациях пользователя
  $reservation_query = "SELECT res.date, res.time, res.cost, res.payment, q.name, adr.buildingAdress AS adress
                        FROM reservation AS res
                        INNER JOIN quests AS q ON res.room_id = q.ID
                        INNER JOIN adress AS adr ON q.adress_id = adr.ID
                        WHERE res.client_id = $user_id";
  $reservation_result = mysqli_query($conn, $reservation_query);

  // проверка наличия данных о резервациях
  if (mysqli_num_rows($reservation_result) > 0) {
    echo "<div class='card mt-3'>";
    echo "<div class='card-header'>";
    echo "<h3>Reservations:</h3>";
    echo "</div>";
    echo "<ul class='list-group list-group-flush'>";

    // вывод данных о резервациях
    while ($reservation_row = mysqli_fetch_assoc($reservation_result)) {
      echo "<li class='list-group-item'>";
      echo "<strong>Date:</strong> {$reservation_row['date']} | ";
      echo "<strong>Time:</strong> {$reservation_row['time']} | ";
      echo "<strong>Cost:</strong> {$reservation_row['cost']} | ";
      echo "<strong>Quest:</strong> {$reservation_row['name']} | ";
      echo "<strong>Adress:</strong> {$reservation_row['adress']} | ";
      echo "<strong>Payment type:</strong> {$reservation_row['payment']}";
      echo "</li>";
    }

    echo "</ul>";
    echo "</div>";
  } else {
    echo "<div class='card mt-3'>";
    echo "<div class='card-body'>";
    echo "<p>No reservations found.</p>";
    echo "</div>";
    echo "</div>";
  }

  $admin_query = "SELECT phoneNumber,email FROM admin";
  $admin_result = mysqli_query($conn, $admin_query);

  if (mysqli_num_rows($admin_result) > 0) {
    $admin_row = mysqli_fetch_assoc($admin_result);
    echo "<div class='alert alert-info mt-3'>";
    echo "<p>If you want to change reservation time or your personal data, please contact this phone: <strong>{$admin_row['phoneNumber']}</strong> or write to this email: <strong>{$admin_row['email']}</strong></p>";
    echo "</div>";
  } else {
    echo "<div class='alert alert-warning mt-3'>";
    echo "<p>No admin found.</p>";
    echo "</div>";
  }

  echo "</div>"; // закрытие контейнера
} else {
  echo "<div class='container mt-5'>";
  echo "<div class='alert alert-danger'>";
  echo "<p>User not found.</p>";
  echo "</div>";
  echo "</div>";
}

mysqli_close($conn);
?>
