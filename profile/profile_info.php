<?php
$pageTitle = "User's profile";
include '../includes/header.php';
?>

<!-- Add Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php
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
    echo '<table class="table table-striped table-bordered">'; // Добавлен класс table-striped
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

        // Display number of reservations for this user
        $reservations_query = "SELECT * FROM reservation WHERE client_id = " . $row['ID'];
        $reservations_result = mysqli_query($conn, $reservations_query);
        $reservation_count = mysqli_num_rows($reservations_result);

        if ($reservation_count > 0) {
            echo '<div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">';
            echo 'User has ' . $reservation_count . ' reservations';
            echo '<a href="#!" onclick="toggleReservations(' . $row['ID'] . ')" class="toggle-arrow" data-target="#reservations-' . $row['ID'] . '"><i class="fas fa-chevron-down"></i></a>';
            echo '</div>';

            echo '<ul id="reservations-' . $row['ID'] . '" class="collapse reservations-container" style="list-style: none; padding: 0; width: 100%;">';

            while ($reservation_row = mysqli_fetch_assoc($reservations_result)) {
                echo '<li style="margin-bottom: 10px;">';
                echo 'Reservation ID: ' . $reservation_row['ID'] . ', Date: ' . $reservation_row['date'] . ', Room ID: ' . $reservation_row['room_id'];
                echo '<div style="display: flex; gap: 10px; margin-top: 5px;">';
                echo '<form method="post" action="../room/delete_reservation.php" style="display:inline;">';
                echo '<input type="hidden" name="reserv_id" value="' . $reservation_row['ID'] . '">';
                echo '<input type="submit" name="delete_reservation" value="Delete" class="btn btn-danger btn-sm">';
                echo '</form>';
                echo ' ';
                echo '<form method="post" action="../room/update_reservation_form.php" style="display:inline;">';
                echo '<input type="hidden" name="reserv_id" value="' . $reservation_row['ID'] . '">';
                echo '<input type="submit" name="update_reservation" value="Update" class="btn btn-primary btn-sm">';
                echo '</form>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No reservations found for this user.';
        }

        echo '</td>';
        echo '<td>';
        echo '<div style="display: flex; gap: 10px;">';
        echo '<form method="post" action="../profile/user_delete.php" style="display:inline;">';
        echo '<input type="hidden" name="user_id" value="' . $row['ID'] . '">';
        echo '<input type="submit" name="delete_user" value="Delete" class="btn btn-danger btn-sm">';
        echo '</form>';
        echo ' ';
        echo '<form method="post" action="../profile/user_update_form.php" style="display:inline;">';
        echo '<input type="hidden" name="user_id" value="' . $row['ID'] . '">';
        echo '<input type="submit" name="update_user" value="Update" class="btn btn-primary btn-sm">';
        echo '</form>';
        echo '</div>';
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

<script>
function toggleReservations(userId) {
    var reservationsList = document.getElementById('reservations-' + userId);
    var toggleArrow = document.querySelector('.toggle-arrow[data-target="#reservations-' + userId + '"] i');

    if (reservationsList.classList.contains('show')) {
        reservationsList.classList.remove('show');
        toggleArrow.classList.remove('fa-chevron-up');
        toggleArrow.classList.add('fa-chevron-down');
    } else {
        reservationsList.classList.add('show');
        toggleArrow.classList.remove('fa-chevron-down');
        toggleArrow.classList.add('fa-chevron-up');
    }
}
</script>

<style>
.toggle-arrow {
    cursor: pointer;
    display: inline-block;
    margin-top: 10px;
    background-color: #f0f0f0;
    padding: 5px 10px;
    border-radius: 5px;
}

.toggle-arrow i {
    color: #333;
}

.collapse {
    display: none;
}

.collapse.show {
    display: block;
}

.reservations-container {
    width: 100%; /* Fixed width for the container */
}
</style>
