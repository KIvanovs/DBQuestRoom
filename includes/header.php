<!DOCTYPE html>
<html>
<head>
  <title> <?php echo $pageTitle ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/filter.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../room/quest_list.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php
            session_start();
            if (isset($_SESSION['user_id'])) {
                echo "<li class='nav-item'><a class='nav-link' href='../profile/profile.php'>Profile</a></li>";
            }
            if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
                echo "<li class='nav-item'><a class='nav-link' href='../admin/admin.php'>Admin info</a></li>";
                echo "<li class='nav-item'><a class='nav-link' href='../room/quest_form.php'>Add quest room</a></li>";
                echo "<li class='nav-item'><a class='nav-link' href='../profile/profile_info.php'>User's info</a></li>";
                echo "<li class='nav-item'><a class='nav-link' href='../charts/chart_graphs.php'>Charts</a></li>";
            }
            ?>
        </ul>

        <!-- Right aligned links -->
        <ul class="navbar-nav ms-auto">
            <?php
            if (isset($_SESSION['admin_id']) || isset($_SESSION['user_id'])) {
                echo "<li class='nav-item'>";
                echo "<form class='form-inline' action='../register_login/logout.php' method='post'>";
                echo "<button class='btn btn-outline-secondary my-2 my-sm-0' type='submit' name='logout'>Logout</button>";
                echo "</form>";
                echo "</li>";
            } else {
                echo "<li class='nav-item'><a class='nav-link' href='../register_login/registerform.php'>Register</a></li>";
                echo "<li class='nav-item'><a class='nav-link' href='../register_login/loginform.php'>Login</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>



