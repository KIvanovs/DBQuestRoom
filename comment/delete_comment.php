<?php
session_start();

include '../includes/dbcon.php';

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Get the comment ID from the form
$comment_id = $_POST['comment_id'];
$quest_id = $_POST['quest_id'];
// Start transaction
mysqli_begin_transaction($conn);

try {
    // First, delete all replies to this comment
    $delete_replies_query = "DELETE FROM comment WHERE reply_to = $comment_id";
    $delete_replies_result = mysqli_query($conn, $delete_replies_query);

    if (!$delete_replies_result) {
        throw new Exception("Error deleting replies: " . mysqli_error($conn));
    }

    // Then, delete the main comment
    $delete_comment_query = "DELETE FROM comment WHERE ID = $comment_id";
    $delete_comment_result = mysqli_query($conn, $delete_comment_query);

    if (!$delete_comment_result) {
        throw new Exception("Error deleting comment: " . mysqli_error($conn));
    }

    // Commit the transaction
    mysqli_commit($conn);

    // The comment and its replies were deleted successfully
    header("Location: ../room/quest_info.php?ID=$quest_id");
    exit();
} catch (Exception $e) {
    // Rollback the transaction if any query failed
    mysqli_roll_back($conn);

    // Display error message
    echo "Error: " . $e->getMessage();
}

mysqli_close($conn);
?>