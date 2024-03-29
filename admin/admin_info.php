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



// Query the database for admins
$query = "SELECT * FROM admin";

$result = mysqli_query($conn, $query);

// Check if any admins were found
if (mysqli_num_rows($result) > 0) {

    // Display each admin
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p><strong>ID:</strong> " . $row['ID'] . "</p>";
        echo "<p><strong>Name:</strong> " . $row['name'] . "</p>";
        echo "<p><strong>Surname:</strong> " . $row['surname'] . "</p>";
        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
        echo "<p><strong>Personal Code:</strong> " . $row['personCode'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $row['phoneNumber'] . "</p>";

        // Add a form for deleting this admin (if the admin is not the one currently logged in)
        if ($row['ID'] != $_SESSION['admin_id']) {
            echo "<form method='post' action='../admin/admin_delete.php'>";
            echo "<input type='hidden' name='admin_id' value='" . $row['ID'] . "'>";
            echo "<input type='submit' name='delete_admin' value='Delete'>";
            echo "</form>";
        }

        // Add a link for editing this admin (if the admin is not the one currently logged in)
        if ($row['ID'] != $_SESSION['admin_id']) {
            echo "<form method='post' action='../admin/admin_update.php'>";
            echo "<input type='hidden' name='admin_id' value='" . $row['ID'] . "'>";
            echo "<input type='submit'  value='Update'>";
            echo "</form>";
        }
    }

} else {

    // No admins were found, show a message
    echo "No admins found.";

}

mysqli_close($conn);
?>