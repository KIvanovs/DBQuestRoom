<?php
session_start();

include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the room ID from the form
$id = $_POST['id'];

// Fetch the photo path, adress_id, and questCategory_id from the database
$query = "SELECT photoPath, adress_id, questCategory_id FROM quests WHERE ID = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $photoPath = $row['photoPath'];
    $adress_id = $row['adress_id'];
    $questCategory_id = $row['questCategory_id'];

    // Delete the photo file
    if (unlink($photoPath)) {
        // Photo file deleted successfully, now delete the record from the database
        $deleteQuestQuery = "DELETE FROM quests WHERE ID = $id";
        $deleteAdressQuery = "DELETE FROM adress WHERE ID = $adress_id";
        $deleteCategoryQuery = "DELETE FROM questcategory WHERE ID = $questCategory_id";

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            $deleteQuestResult = mysqli_query($conn, $deleteQuestQuery);
            $deleteAdressResult = mysqli_query($conn, $deleteAdressQuery);
            $deleteCategoryResult = mysqli_query($conn, $deleteCategoryQuery);

            if ($deleteQuestResult && $deleteAdressResult && $deleteCategoryResult) {
                // Commit transaction
                mysqli_commit($conn);
                // The record was deleted successfully
                header("Location: ../room/quest_form.php");
                exit();
            } else {
                // Rollback transaction
                mysqli_rollback($conn);
                // The record was not deleted, show an error message
                echo "Error deleting record from the database.";
            }
        } catch (Exception $e) {
            // Rollback transaction in case of error
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
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
