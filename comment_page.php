<!DOCTYPE html>
<html>
<head>
	<title>Comments</title>
</head>
<body>
	<h1>Comments</h1>
	<?php
    session_start();
		if (isset($_SESSION['nickname'])) {
			echo "<p>Welcome, " . $_SESSION['nickname'] . "!</p>";
		}
		if (isset($_SESSION['admin_name'])) {
			echo "<p>Welcome, " . $_SESSION['admin_name'] . "!</p>";
		}
	?>
	<hr>
	<?php
		if (isset($_SESSION['admin_id'])) {
			include 'comment_form.php';
		} 	
			if (isset($_SESSION['user_id'])){
				include 'comment_form.php';
			}

			else{
				echo "<p>Please <a href='loginform.php'>log in</a> to add comments.</p>";
			}
		
	?>
	<hr>
	<?php
		include 'comments.php';
	?>
</body>
</html>