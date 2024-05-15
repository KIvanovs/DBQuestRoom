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

  <?php
    echo "<div class='container my-4'>
      <div class='row'>";

      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['ID'];
        $name = $row['name'];
        $category = $row['categoryName'];
        $peopleAmount = $row['peopleAmount'];
        $ageLimit = $row['ageLimit'];
        $photoPath = $row['photoPath'];
 

      echo "<div class='col-md-4 mb-4'>";
      echo "<div class='card shadow' style='overflow: hidden; transition: filter 0.5s ease; filter: brightness(95%);' onmouseover='this.style.filter=\"brightness(100%)\"' onmouseout='this.style.filter=\"brightness(95%)\"'>"; //
      echo "<img src='$photoPath' class='card-img-top' style='height: 250px; object-fit: cover;' alt='photo of $name'>";
      echo "<div class='card-body'>";
      echo "<div class='mb-2'>";
      echo "<span class='badge bg-secondary'>$ageLimit+</span>";
      echo "</div>";
      echo "<h5 class='card-title'>$name</h5>";
      echo "<p class='card-text'><small>$peopleAmount </small></p>";
      echo "</div>"; // Close card-body
      echo "<a href='../room/quest_info.php?ID=$id' class='stretched-link'></a>"; // Make the whole card clickable
      echo "</div>"; // Close card
      echo "</div>"; // Close column
    }

    echo "</div>"; // Close row
    echo "</div>"; // Close container
  ?>
	
<?php 
include_once '../includes/footer.php';
?>
