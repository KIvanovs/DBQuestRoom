<!DOCTYPE html>
<html>
<head>
  <title>Card Example</title>
  <link rel="stylesheet" type="text/css" href="../css/home.css">
  <script src="../js/script.js"></script>
</head>
<body>
  <?php
  include '../includes/header.php';

  if(isset($_SESSION['user_id']) && isset($_SESSION['nickname'])) {
     // The user is currently logged in as a user
     echo "You are logged in as a user!";
     echo "Welcome " . $_SESSION['nickname'];
  } else if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
     // The user is currently logged in as an admin
     echo "You are logged in as an admin!";
     echo "Welcome " . $_SESSION['admin_name'];
  } else {
     // The user is not currently logged in
     echo "You are not logged in!";
  }
  
  echo "<form method='GET'>
  <label for='search'>Search:</label>
  <input type='text' name='search' id='search'>
  <button type='submit'>Go</button>
	</form>";

  // Connect to database
  $dbhost = 'localhost';
  $dbname = 'testdb';
  $dbuser = 'root';
  $dbpass = '';

  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);


  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  	// Select all categories from the database
	$query = "SELECT DISTINCT category FROM quests";
	$result = mysqli_query($conn, $query);
	$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Select all age limits from the database
	$query = "SELECT DISTINCT ageLimit FROM quests ORDER BY ageLimit DESC";
	$result = mysqli_query($conn, $query);
	$ageLimits = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Select all age limits from the database
	$query = "SELECT DISTINCT peopleAmount FROM quests";
  	$result = mysqli_query($conn, $query);
  	$peopleAmounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Don't close database connection and save data in result
	mysqli_free_result($result);
	?>

	<div class="filter-bar">
		<label for="category">Category:</label>
		<select id="category" onchange="filterAll()">
		<option value="">All</option>
		<?php foreach($categories as $category): ?>
			<option value="<?php echo $category['category']; ?>"><?php echo $category['category']; ?></option>
		<?php endforeach; ?>
		</select>

		<label for="ageLimit">Age Limit:</label>
		<select id="ageLimit" onchange="filterAll()">
		<option value="">All</option>
		<?php foreach($ageLimits as $ageLimit): ?>
			<option value="<?php echo $ageLimit['ageLimit']; ?>"><?php echo $ageLimit['ageLimit']; ?>+</option>
		<?php endforeach; ?>
		</select>

		<label for="peopleAmount">People Amount:</label>
		<select id="peopleAmount" onchange="filterAll()">
		<option value="">All</option>
		<?php foreach($peopleAmounts as $peopleAmount): ?>
			<option value="<?php echo $peopleAmount['peopleAmount']; ?>"><?php echo $peopleAmount['peopleAmount']; ?></option>
		<?php endforeach; ?>
		</select>
	</div>




	<?php
  // Select all quests from the database, or search for a specific quest if a search query was submitted
  if(isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM quests WHERE name LIKE '%$search%'";
  } else {
    $query = "SELECT * FROM quests";
  }

  $result = mysqli_query($conn, $query);

  // Display search results message
  if(isset($_GET['search'])) {
    echo "<p>Search results for '{$_GET['search']}':</p>";
  }

  // Display each quest as a card
  while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['ID'];
    $name = $row['name'];
    $category = $row['category'];
    $address = $row['adress'];
    $discount = $row['discount'];
    $peopleAmount = $row['peopleAmount'];
    $ageLimit = $row['ageLimit'];
    $description = $row['description'];
    $photoPath = $row['photoPath'];

    echo '<div class="card" data-category="' . $category . '" data-age-limit="' . $ageLimit . '" data-people-amount="' . $peopleAmount . '">';
    echo '<div class="card-image">';
	echo '<img src="' . $photoPath . '" alt="photo of ' . $name . '">';
	echo '</div>';
	echo '<div class="card-content">';
	echo '<h2>' . $name . '</h2>';
	echo '<h3>' . $ageLimit . ' +</h3>';
	echo '<h4>' . $peopleAmount . ' </h4>';
	echo '<h5>' . $category . ' </h5>';
	echo '<a href="../room/quest_info.php?ID=' . $id . '" class="btn">Read More</a>';
	echo '</div>';
	echo '</div>';
	}
	
	// Close database connection
	mysqli_close($conn);
	?>
	
	</body>
	</html> 