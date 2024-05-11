<?php
  $pageTitle = 'Registration form';
  include_once '../includes/header.php';
	?>
	<div class="container">
		<h1>Registration Form</h1>
		<form action="../register_login/register.php" method="POST">
			<label for="name">Nickname</label>
			<input type="text" id="nickname" name="nickname" required>

			<label for="name">Name</label>
			<input type="text" id="name" name="name" required>

			<label for="name">Surname</label>
			<input type="text" id="surname" name="surname" required>
			
			<label for="email">Email</label>
			<input type="email" id="email" name="email" required>

			<label for="email">Phone number</label>
			<input type="text" id="phone" name="phone" required>
			
			<label for="password">Password</label>
			<input type="password" id="password" name="password" required>
			
			<label for="confirm_password">Confirm Password</label>
			<input type="password" id="confirm_password" name="confirm_password" required>
			
			<input type="submit" value="Submit">
		</form>
	</div>
	<?php 
include_once '../includes/footer.php';
?>