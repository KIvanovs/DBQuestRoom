<?php
$pageTitle = "User's profile";
include '../includes/header.php';

// Check if the user is not an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Redirect the user to the admin login page
    header("Location: ../register_login/loginform.php");
    exit();
}

include '../includes/dbcon.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query the database for users and reservations
$query = "SELECT u.ID, u.nickname, u.name, u.surname, u.email, u.phoneNumber FROM users u";

$result = mysqli_query($conn, $query);

// Check if any users were found
if (mysqli_num_rows($result) > 0) {
    echo '<div class="container">';
    echo '<h2>User Profiles</h2>';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nickname</th>';
    echo '<th>Name</th>';
    echo '<th>Surname</th>';
    echo '<th>Email</th>';
    echo '<th>Phone Number</th>';
    echo '<th>Reservations</th>';
    echo '<th>Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Display each user and their reservations
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['ID'] . '</td>';
        echo '<td>' . $row['nickname'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['surname'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['phoneNumber'] . '</td>';
        echo '<td>';
        
        // Display reservations for this user
        $reservations_query = "SELECT * FROM reservation WHERE client_id = " . $row['ID'];
        $reservations_result = mysqli_query($conn, $reservations_query);

        if (mysqli_num_rows($reservations_result) > 0) {
            echo '<ul>';
            while ($reservation_row = mysqli_fetch_assoc($reservations_result)) {
                echo '<li>';
                echo 'Reservation ID: ' . $reservation_row['ID'] . ', Date: ' . $reservation_row['date'] . ', Room ID: ' . $reservation_row['room_id'];
                echo '<form method="post" action="../room/delete_reservation.php" style="display:inline;">';
                echo '<input type="hidden" name="reserv_id" value="' . $reservation_row['ID'] . '">';
                echo '<input type="submit" name="delete_reservation" value="Delete" class="btn btn-danger btn-sm">';
                echo '</form>';
                echo ' ';
                echo '<form method="post" action="../room/update_reservation_form.php" style="display:inline;">';
                echo '<input type="hidden" name="reserv_id" value="' . $reservation_row['ID'] . '">';
                echo '<input type="submit" name="update_reservation" value="Update" class="btn btn-primary btn-sm">';
                echo '</form>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No reservations found for this user.';
        }
        
        echo '</td>';
        echo '<td>';
        echo '<form method="post" action="../profile/user_delete.php" style="display:inline;">';
        echo '<input type="hidden" name="user_id" value="' . $row['ID'] . '">';
        echo '<input type="submit" name="delete_user" value="Delete" class="btn btn-danger btn-sm">';
        echo '</form>';
        echo ' ';
        echo '<form method="post" action="../profile/user_update_form.php" style="display:inline;">';
        echo '<input type="hidden" name="user_id" value="' . $row['ID'] . '">';
        echo '<input type="submit" name="update_user" value="Update" class="btn btn-primary btn-sm">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

} else {
    // No users were found, show a message
    echo '<div class="container">';
    echo '<p>No users found.</p>';
    echo '</div>';
}

mysqli_close($conn);
?>
