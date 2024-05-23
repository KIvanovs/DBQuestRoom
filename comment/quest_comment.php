<?php
session_start();
// Проверка аутентификации пользователя
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    // Если пользователь не аутентифицирован, перенаправляем на страницу входа
    header("Location: ../register_login/loginform.php");
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    include '../includes/dbcon.php'; // Reconnect to the database

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $quest_id = mysqli_real_escape_string($conn, $_POST['quest_id']);
    $reply_to = isset($_POST['reply_to']) ? mysqli_real_escape_string($conn, $_POST['reply_to']) : null;

    // Проверка, кто добавляет комментарий - пользователь или администратор
    if (isset($user_id)) {
        $insert_query = "INSERT INTO comment (comment, user_id, admin_id, quest_id, reply_to, creation_date) 
                        VALUES ('$comment', '$user_id', NULL, '$quest_id', '$reply_to', CURRENT_TIMESTAMP)";
    } elseif (isset($admin_id)) {
        $insert_query = "INSERT INTO comment (comment, user_id, admin_id, quest_id, reply_to, creation_date) 
                        VALUES ('$comment', NULL, '$admin_id', '$quest_id', '$reply_to', CURRENT_TIMESTAMP)";
    }

    if (mysqli_query($conn, $insert_query)) {
        echo "Comment added successfully!";
        // Redirect to the quest_info.php page to prevent form resubmission
        header("Location: ../room/quest_info.php?ID=$quest_id");
        exit();
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Если запрос не является POST или комментарий не установлен, перенаправляем обратно на страницу quest_info.php
    header("Location: ../room/quest_list.php");
    exit();
}
?>