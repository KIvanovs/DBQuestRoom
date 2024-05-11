<?php
  $pageTitle = 'Quest list';
  include_once '../includes/header.php';

  // if(isset($_SESSION['user_id']) && isset($_SESSION['nickname'])) {
  //    // The user is currently logged in as a user
  //    echo "You are logged in as a user!";
  //    echo "Welcome " . $_SESSION['nickname'];
  // } else if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
  //    // The user is currently logged in as an admin
  //    echo "You are logged in as an admin!";
  //    echo "Welcome " . $_SESSION['admin_name'];
  // } else {
  //    // The user is not currently logged in
  //    echo "You are not logged in!";
  // }
  
  echo "
  <div class='d-flex container py-3  '>
    <form method='GET' class='row g-3'>
      <div class='col-12 d-flex'>
        <input type='text' class='form-control me-3' name='search' id='search' placeholder='Enter search term'>
        <button type='submit' class='btn btn-primary'>Go</button>
      </div>
    </form>
  </div>";
  


	include '../includes/dbcon.php';


  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  	// Select all categories from the database
	$query = "SELECT DISTINCT categoryName FROM questcategory";
	$result = mysqli_query($conn, $query);
	$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Select all age limits from the database
	$query = "SELECT DISTINCT ageLimit FROM questcategory ORDER BY ageLimit DESC";
	$result = mysqli_query($conn, $query);
	$ageLimits = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Select all age limits from the database
	$query = "SELECT DISTINCT peopleAmount FROM quests";
  	$result = mysqli_query($conn, $query);
  	$peopleAmounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Don't close database connection and save data in result
	mysqli_free_result($result);
	?>
<?php
echo "
<div class='container my-4'>
  <div class='row'>
    <div class='col-md-3 mb-3 d-flex flex-column'> <!-- Added d-flex and flex-column -->
      <label for='category' class='form-label'>Category:</label>
      <select id='category' class='form-select' onchange='filterCards()'>
        <option value=''>All</option>";
        foreach ($categories as $category) {
            echo "<option value='" . htmlspecialchars($category['categoryName']) . "'>" . htmlspecialchars($category['categoryName']) . "</option>";
        }
      echo "</select>
    </div>
    
    <div class='col-md-4 mb-3 d-flex flex-column'> <!-- Added d-flex and flex-column -->
      <label for='ageLimit' class='form-label'>Age Limit:</label>
      <select id='ageLimit' class='form-select' onchange='filterCards()'>
        <option value=''>All</option>";
        foreach ($ageLimits as $ageLimit) {
            echo "<option value='" . htmlspecialchars($ageLimit['ageLimit']) . "'>" . htmlspecialchars($ageLimit['ageLimit']) . "+</option>";
        }
      echo "</select>
    </div>
    
    <div class='col-md-5 mb-3 d-flex flex-column'> <!-- Added d-flex and flex-column -->
      <label for='peopleAmount' class='form-label'>People Amount:</label>
      <select id='peopleAmount' class='form-select' onchange='filterCards()'>
        <option value=''>All</option>";
        foreach ($peopleAmounts as $peopleAmount) {
            echo "<option value='" . htmlspecialchars($peopleAmount['peopleAmount']) . "'>" . htmlspecialchars($peopleAmount['peopleAmount']) . "</option>";
        }
      echo "</select>
    </div>
  </div>
</div>";
?>





	<?php
  // Select all quests from the database, or search for a specific quest if a search query was submitted
  if(isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT q.ID, q.name, q.peopleAmount, q.description, q.photoPath,
                  a.buildingAdress, 
                  qc.ageLimit, qc.categoryName
              FROM quests q
              LEFT JOIN adress a ON q.adress_id = a.ID
              LEFT JOIN questcategory qc ON q.questCategory_id = qc.ID
              WHERE q.name LIKE '%$search%'";


  } else {
    $query = "SELECT q.ID, q.name, q.peopleAmount, q.description, q.photoPath,
                  a.buildingAdress, 
                  qc.ageLimit, qc.categoryName
              FROM quests q
              LEFT JOIN adress a ON q.adress_id = a.ID
              LEFT JOIN questcategory qc ON q.questCategory_id = qc.ID";
  }

  $result = mysqli_query($conn, $query);

  // Display search results message
  if(isset($_GET['search'])) {
    echo "<p>Search results for '{$_GET['search']}':</p>";
  }
 ?>

  <div class="cards-wrapper">
  <?php
  
    // Display each quest as a card
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['ID'];
      $name = $row['name'];
      $category = $row['categoryName'];
      $address = $row['buildingAdress'];
      $peopleAmount = $row['peopleAmount'];
      $ageLimit = $row['ageLimit'];
      $description = $row['description'];
      $photoPath = $row['photoPath'];

      // Wrap the entire card content in an anchor tag
      echo '<a href="../room/quest_info.php?ID=' . $id . '" class="card" data-category="' . $category . '" data-age-limit="' . $ageLimit . '" data-people-amount="' . $peopleAmount . '">';
      echo '<div class="card-image">';
      echo '<img src="' . $photoPath . '" alt="photo of ' . $name . '">';
      echo '</div>';
      echo '<div class="card-content">';
      echo '<h2>' . $name . '</h2>';
      echo '<h3>' . $ageLimit . ' +</h3>';
      echo '<h4>' . $peopleAmount . ' </h4>';
      echo '<h5>' . $category . ' </h5>';
      echo '</div>';
      echo '</a>';
    }
    ?>
	</div>
	
<?php 
include_once '../includes/footer.php';
?>
