<!DOCTYPE html>
<html>
<head>
	<title>Search Form</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
	include 'header.php';
	?>
	<div class="search-container">
		<h2>Search</h2>
		<form action="dbcon.php" method="post">
			<input type="text" placeholder="Enter keyword(s)" name="keyword">
			<button type="submit">Search</button>
		</form>
	</div>
</body>
</html>




