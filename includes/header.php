<!DOCTYPE html>
<html>
<head>
  <title> <?php echo $pageTitle ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <!-- Подключите скрипт Stripe.js первым -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Подключите остальные скрипты после Stripe.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Moment.js for date manipulation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- Include Pikaday JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/pikaday.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
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
                echo "<li class='nav-item'><a class='nav-link' href='../admin/admin.php'>Admin/Employee info</a></li>";
                echo "<li class='nav-item'><a class='nav-link' href='../room/quest_form.php'>Quest room info</a></li>";
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

