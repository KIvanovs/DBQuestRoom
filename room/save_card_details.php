<?php
include '../includes/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Извлечение данных карты из формы
    $cardDate = mysqli_real_escape_string($conn, $_POST['cardDate']);
    $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNumber']);
    $cardName = mysqli_real_escape_string($conn, $_POST['cardName']);
    $cardFilial = mysqli_real_escape_string($conn, $_POST['cardFilial']);
    $cardCode = mysqli_real_escape_string($conn, $_POST['cardCode']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']); // Убедитесь, что user_id передаётся в POST-данных

    // Вставка данных карты в таблицу `card`, включая user_id
    $insert_card_query = "INSERT INTO card (cardDate, cardNumber, cardName, cardFilial, cardCode, user_id) 
                          VALUES ('$cardDate', '$cardNumber', '$cardName', '$cardFilial', '$cardCode', '$user_id')";
    if (mysqli_query($conn, $insert_card_query)) {
        // Перенаправление на другую страницу после успешного сохранения данных карты
        header("Location: card_saved_success.php");
        exit();
    } else {
        echo "Error adding card details: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
