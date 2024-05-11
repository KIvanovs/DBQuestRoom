<?php
  $pageTitle = 'Admin info';
  include_once '../includes/header.php';

	?>
	<h1>Admin info</h1>
	<?php
		if (isset($_SESSION['admin_name'])) {
			echo "<p>Welcome, " . $_SESSION['admin_name'] . "!</p>";
		}
        else{
            header("Location: ../register_login/loginform.php");
            exit();
        }
	?>
	<hr>
	<?php
		if (isset($_SESSION['admin_id'])) {
			echo "<h1>Add Admin</h1>";

            echo"<form method='post' action='../admin/admin_add.php'>";
                
            echo"<label>Name:</label>";
            echo"<input type='text' name='name' required><br><br>";
        
            echo"<label>Surname:</label>";
            echo"<input type='text' name='surname' required><br><br>";
        
            echo"<label>Email:</label>";
            echo"<input type='email' name='email' required><br><br>";
        
            echo"<label>Password:</label>";
            echo"<input type='password' name='password' required><br><br>";
        
            echo "<label>Personal Code:</label>";
            echo"<input type='text' name='personCode' required><br><br>";
        
            echo "<label>Phone Number:</label>";
            echo"<input type='text' name='phoneNumber' required><br><br>";
        
            echo"<input type='submit' name='submit' value='Add'>";
        
            echo"</form>";
		} 	


		else{
            header("Location: ../register_login/loginform.php");
            exit();
		}
		
	?>
	<hr>
	<?php
	if (isset($_SESSION['admin_id'])) {
        include '../admin/admin_info.php';
    } 
	else{
        header("Location: ../register_login/loginform.php");
        exit();
    }

		
	?>
<?php 
include_once '../includes/footer.php';
?>