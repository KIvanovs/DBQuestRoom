<?php
session_start();

include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the room ID from the form
$id = $_POST['id'];

// Fetch the photo path from the database
$query = "SELECT photoPath FROM quests WHERE ID = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $photoPath = $row['photoPath'];

    // Delete the photo file
    if (unlink($photoPath)) {
        // Photo file deleted successfully, now delete the record from the database
        $deleteQuery = "DELETE FROM quests WHERE ID = $id";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // The record was deleted successfully
            header("Location: ../room/quest_form.php");
            exit();
        } else {
            // The record was not deleted, show an error message
            echo "Error deleting record from the database.";
        }
    } else {
        // Error deleting the photo file
        echo "Error deleting photo file.";
    }
} else {
    // Error fetching photo path from the database
    echo "Error fetching photo path from the database.";
}

mysqli_close($conn);

?>