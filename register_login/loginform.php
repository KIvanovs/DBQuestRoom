<?php
  $pageTitle = 'Login form';
  include_once '../includes/header.php';
?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6 col-lg-4">
        <form method="post" action="../register_login/login.php" class="mt-5 p-4 border rounded bg-light">
            <h2 class="mb-4 text-center">Login Form</h2>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
</div>
<?php 
include_once '../includes/footer.php';
?>
