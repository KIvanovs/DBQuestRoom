<?php
session_start();
include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$comment_id = $_POST['comment_id'];
$quest_id = $_POST['quest_id'];
$comment_text = mysqli_real_escape_string($conn, $_POST['comment']);

$update_query = "UPDATE comment SET comment = '$comment_text' WHERE ID = $comment_id";

if (mysqli_query($conn, $update_query)) {
    header("Location: ../room/quest_info.php?ID=$quest_id");
    exit();
} else {
    echo "Error updating comment: " . mysqli_error($conn);
}

mysqli_close($conn);
?>