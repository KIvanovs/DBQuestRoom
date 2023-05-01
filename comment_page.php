<!DOCTYPE html>
<html>
<head>
	<title>Comments</title>
</head>
<body>
<?php
	include 'header.php';
	?>
	<h1>Comments</h1>
	<?php
		if (isset($_SESSION['nickname'])) {
			echo "<p>Welcome, " . $_SESSION['nickname'] . "!</p>";
		}
		if (isset($_SESSION['admin_name'])) {
			echo "<p>Welcome, " . $_SESSION['admin_name'] . "!</p>";
		}
	?>
	<hr>
	<?php
		if (isset($_SESSION['admin_id']) ||isset($_SESSION['user_id'])) {
			include 'comment_form.php';
		} 	
			else{
				echo "<p>Please <a href='loginform.php'>log in</a> to add comments.</p>";
			}
		
	?>
	<hr>
	<?php
	if (isset($_SESSION['nickname'])) {
		include 'comments.php';
	}
	elseif (isset($_SESSION['admin_name'])) {
		include 'admin_comments.php';
	}
	else{
		include 'comments.php';
	}
		
	?>
</body>
</html>