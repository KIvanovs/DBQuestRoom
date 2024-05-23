<?php
$pageTitle = 'Admin info';
include_once '../includes/header.php';
?>


<div class="container mt-5">
    <h1>Admin info</h1>
    <?php
    if (isset($_SESSION['admin_name'])) {
        echo "<p class='alert alert-success'>Welcome, " . $_SESSION['admin_name'] . "!</p>";
    } else {
        header("Location: ../register_login/loginform.php");
        exit();
    }
    ?>
    <hr>
    <?php
    if (isset($_SESSION['admin_id'])) {
        echo "<h2>Add Admin</h2>";
        echo "<form method='post' action='../admin/admin_add.php' class='needs-validation' novalidate>";
        echo "<div class='form-group'>";
        echo "<label for='name'>Name:</label>";
        echo "<input type='text' class='form-control' id='name' name='name' required>";
        echo "<div class='invalid-feedback'>Please enter a name.</div>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='surname'>Surname:</label>";
        echo "<input type='text' class='form-control' id='surname' name='surname' required>";
        echo "<div class='invalid-feedback'>Please enter a surname.</div>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='email'>Email:</label>";
        echo "<input type='email' class='form-control' id='email' name='email' required>";
        echo "<div class='invalid-feedback'>Please enter a valid email.</div>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='password'>Password:</label>";
        echo "<input type='password' class='form-control' id='password' name='password' required>";
        echo "<div class='invalid-feedback'>Please enter a password.</div>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='personCode'>Personal Code:</label>";
        echo "<input type='text' class='form-control' id='personCode' name='personCode' required>";
        echo "<div class='invalid-feedback'>Please enter a personal code.</div>";
        echo "</div>";
        
        echo "<div class='form-group'>";
        echo "<label for='phoneNumber'>Phone Number:</label>";
        echo "<input type='text' class='form-control' id='phoneNumber' name='phoneNumber' required>";
        echo "<div class='invalid-feedback'>Please enter a phone number.</div>";
        echo "</div>";
        
        echo "<button type='submit' class='btn btn-primary'>Add</button>";
        echo "</form>";
    } else {
        header("Location: ../register_login/loginform.php");
        exit();
    }
    ?>
    <hr>
    <?php
    if (isset($_SESSION['admin_id'])) {
        include '../admin/admin_info.php';
    } else {
        header("Location: ../register_login/loginform.php");
        exit();
    }
    ?>
</div>



<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php 
include_once '../includes/footer.php';
?>
