<?php
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

// Get the user ID to update from the GET parameter
$user_id = $_POST['user_id'];

// Query the database for the user's current data
$query = "SELECT * FROM users WHERE ID = " . $user_id;
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" action="../profile/user_update.php">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="form-group">
                    <label for="nickname" class="form-label">Nickname:</label>
                    <input type="text" class="form-control" name="nickname" value="<?php echo $user_data['nickname']; ?>">
                </div>
                <div class="form-group">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $user_data['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="surname" class="form-label">Surname:</label>
                    <input type="text" class="form-control" name="surname" value="<?php echo $user_data['surname']; ?>">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $user_data['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="phoneNumber" class="form-label">Phone Number:</label>
                    <input type="text" class="form-control" name="phoneNumber" value="<?php echo $user_data['phoneNumber']; ?>">
                </div>
                <button type="submit" class="btn btn-primary" name="update_user">Update</button>
            </form>
        </div>
    </div>
</div>

<?php
mysqli_close($conn);
?>