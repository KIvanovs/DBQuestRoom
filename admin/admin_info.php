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

?>

<!-- Include Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2>Admin List</h2>
    <?php
    // Check if any admins were found
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>Surname</th>";
        echo "<th>Email</th>";
        echo "<th>Personal Code</th>";
        echo "<th>Phone Number</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Display each admin
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['surname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['personCode'] . "</td>";
            echo "<td>" . $row['phoneNumber'] . "</td>";
            echo "<td>";

            // Add a form for deleting this admin (if the admin is not the one currently logged in)
            if ($row['ID'] != $_SESSION['admin_id']) {
                echo "<form method='post' action='../admin/admin_delete.php' style='display:inline-block;'>";
                echo "<input type='hidden' name='admin_id' value='" . $row['ID'] . "'>";
                echo "<button type='submit' name='delete_admin' class='btn btn-danger btn-sm'>Delete</button>";
                echo "</form> ";

                // Add a link for editing this admin (if the admin is not the one currently logged in)
                echo "<form method='post' action='../admin/admin_update.php' style='display:inline-block; margin-left: 5px;'>";
                echo "<input type='hidden' name='admin_id' value='" . $row['ID'] . "'>";
                echo "<button type='submit' class='btn btn-warning btn-sm'>Update</button>";
                echo "</form>";
            }

            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        // No admins were found, show a message
        echo "<div class='alert alert-warning'>No admins found.</div>";
    }

    mysqli_close($conn);
    ?>
</div>

