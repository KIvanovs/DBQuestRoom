<!DOCTYPE html>
<html>
<head>
	<title>Comment Form</title>
	<link rel="stylesheet" type="text/css" href="../css/comment_form.css">
</head>
<body>
	
	<div class="comment-container">		
		<h1>Add Comment</h1>
	<form method="post" action="comment/process_comment.php">
		<label>Comment:</label>
		<textarea name="comment" required></textarea><br><br>
		<input type="submit" value="Add Comment">
	</form>
	</div>
</body>
</html>