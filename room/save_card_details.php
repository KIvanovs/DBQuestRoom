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
    $saveCardInfo = isset($_POST['save_card_info']) ? 1 : 0;

    // Вставка данных карты в таблицу `card`
    $insert_card_query = "INSERT INTO card (cardDate, cardNumber, cardName, cardFilial, cardCode) 
                        VALUES ('$cardDate', '$cardNumber', '$cardName', '$cardFilial', '$cardCode')";
    if (mysqli_query($conn, $insert_card_query)) {
        // Получение ID вставленной записи карты
        $card_id = mysqli_insert_id($conn);

        // Связывание ID карты с пользователем в таблице `users`
        // Предполагается, что у вас есть переменная $user_id, которая содержит ID пользователя
        $update_user_query = "UPDATE users SET card_id = '$card_id' WHERE ID = '$user_id'";
        if (mysqli_query($conn, $update_user_query)) {
            // Перенаправление на другую страницу после успешного сохранения данных карты
            header("Location: card_saved_success.php");
            exit();
        } else {
            echo "Error linking card details to user: " . mysqli_error($conn);
        }
    } else {
        echo "Error adding card details: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
