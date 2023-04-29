<?php

include 'header.php';

// Check if the user is not an admin
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Redirect the user to the admin login page
    header("Location: loginform.php");
    exit();
}

// Database connection
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Query the database for users and reservations
$query = "SELECT u.ID, u.nickname, u.name, u.surname, u.email, u.password, u.phoneNumber, r.ID AS reservation_id, r.date, r.room_id FROM users u LEFT JOIN reservation r ON u.ID = r.client_id;";

$result = mysqli_query($conn, $query);

// Check if any users were found
if (mysqli_num_rows($result) > 0) {

    // Display each user and their reservations
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p><strong>ID:</strong> " . $row['ID'] . "</p>";
        echo "<p><strong>Nickname:</strong> " . $row['nickname'] . "</p>";
        echo "<p><strong>Name:</strong> " . $row['name'] . "</p>";
        echo "<p><strong>Surname:</strong> " . $row['surname'] . "</p>";
        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
        echo "<p><strong>Password:</strong> " . $row['password'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $row['phoneNumber'] . "</p>";

        // Display reservations for this user
        echo "<p><strong>Reservations:</strong></p>";
        $reservations_query = "SELECT * FROM reservation WHERE client_id = " . $row['ID'];
        $reservations_result = mysqli_query($conn, $reservations_query);

        if (mysqli_num_rows($reservations_result) > 0) {
            while ($reservation_row = mysqli_fetch_assoc($reservations_result)) {
                echo "<p>Reservation ID: " . $reservation_row['ID'] . ", Date: " . $reservation_row['date'] . ", Room ID: " . $reservation_row['room_id'] . "</p>";
                // Add a form for deleting this reservation
                echo "<form method='post' action='delete_reservation.php'>";
                echo "<input type='hidden' name='reserv_id' value='" . $reservation_row['ID'] . "'>";
                echo "<input type='submit' name='delete_reservation' value='Delete reservation'>";
                echo "</form>";

                // Add a link for updating this reservation
                echo "<form method='post' action='update_reservation_form.php'>";
                echo "<input type='hidden' name='reserv_id' value='" . $reservation_row['ID'] . "'>";
                echo "<input type='submit' name='update_reservation' value='Update reservation'>";
                echo "</form>";
            }
        } else {
            echo "<p>No reservations found for this user.</p>";
        }


        // Add a form for deleting this user
        echo "<br><br>";
        echo "<form method='post' action='user_delete.php'>";
        echo "<input type='hidden' name='user_id' value='" . $row['ID'] . "'>";
        echo "<input type='submit' name='delete_user' value='Delete user data'>";
        echo "</form>";

        // Add a link for editing this user
        echo "<form method='post' action='user_update_form.php'>";
        echo "<input type='hidden' name='user_id' value='" . $row['ID'] . "'>";
        echo "<input type='submit' name='update_user' value='Update user data'>";
        echo "</form>";
    }

} else {

    // No users were found, show a message
    echo "No users found.";

}


mysqli_close($conn);
?>