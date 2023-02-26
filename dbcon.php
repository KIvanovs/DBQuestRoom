<?php
//connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

//process search query
if(isset($_POST['keyword'])){
	$keyword = $_POST['keyword'];
	$query = "SELECT * FROM users WHERE user_name LIKE '%$keyword%'";
	$result = mysqli_query($conn, $query);
}

//display search results
if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)){
		echo "<p>".$row['user_name']."</p>";
	}
}else{
	echo "<p>No results found.</p>";
}

//close database connection
mysqli_close($conn);
?>