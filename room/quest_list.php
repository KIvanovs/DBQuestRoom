<?php
  $pageTitle = 'Quest list';
  include_once '../includes/header.php';
  
  
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
<div class='container my-4'>
  <div class='row'>
    <div class='col-md-3 mb-3'>
      <label for='category' class='form-label'>Category:</label>
      <select id='category' class='form-select' onchange='filterCards()'>
        <option value=''>All</option>
        <?php foreach ($categories as $category): ?>
          <option value='<?php echo htmlspecialchars($category['categoryName']); ?>'><?php echo htmlspecialchars($category['categoryName']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class='col-md-4 mb-3'>
      <label for='ageLimit' class='form-label'>Age Limit:</label>
      <select id='ageLimit' class='form-select' onchange='filterCards()'>
        <option value=''>All</option>
        <?php foreach ($ageLimits as $ageLimit): ?>
          <option value='<?php echo htmlspecialchars($ageLimit['ageLimit']); ?>'><?php echo htmlspecialchars($ageLimit['ageLimit']); ?>+</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class='col-md-5 mb-3'>
      <label for='peopleAmount' class='form-label'>People Amount:</label>
      <select id='peopleAmount' class='form-select' onchange='filterCards()'>
        <option value=''>All</option>
        <?php foreach ($peopleAmounts as $peopleAmount): ?>
          <option value='<?php echo htmlspecialchars($peopleAmount['peopleAmount']); ?>'><?php echo htmlspecialchars($peopleAmount['peopleAmount']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
</div>





	<?php
  // Select all quests from the database, or search for a specific quest if a search query was submitted
    $query = "SELECT q.ID, q.name, q.peopleAmount, q.description, q.photoPath,
                a.buildingAdress, 
                qc.ageLimit, qc.categoryName
            FROM quests q
            LEFT JOIN adress a ON q.adress_id = a.ID
            LEFT JOIN questcategory qc ON q.questCategory_id = qc.ID";

  if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query .= " WHERE q.name LIKE '%$search%'";
  }

  $result = mysqli_query($conn, $query);

 ?>


<div class='container my-4'>
  <div class='row'>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class='col-md-4 mb-4'>
        <div class='card shadow' style='overflow: hidden; transition: filter 0.5s ease; filter: brightness(95%);' onmouseover='this.style.filter="brightness(100%)"' onmouseout='this.style.filter="brightness(95%)"' data-category='<?php echo htmlspecialchars($row['categoryName']); ?>' data-age-limit='<?php echo htmlspecialchars($row['ageLimit']); ?>' data-people-amount='<?php echo htmlspecialchars($row['peopleAmount']); ?>'>
          <img src='<?php echo htmlspecialchars($row['photoPath']); ?>' class='card-img-top' style='height: 250px; object-fit: cover;' alt='photo of <?php echo htmlspecialchars($row['name']); ?>'>
          <div class='card-body'>
            <div class='mb-2'>
              <span class='badge bg-secondary'><?php echo htmlspecialchars($row['ageLimit']); ?>+</span>
            </div>
            <h5 class='card-title'><?php echo htmlspecialchars($row['name']); ?></h5>
            <p class='card-text'><small><?php echo htmlspecialchars($row['peopleAmount']); ?></small></p>
          </div>
          <a href='../room/quest_info.php?ID=<?php echo htmlspecialchars($row['ID']); ?>' class='stretched-link'></a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script>
function filterCards() {
  var category = document.getElementById("category").value;
  var ageLimit = document.getElementById("ageLimit").value;
  var peopleAmount = document.getElementById("peopleAmount").value;

  var cards = document.getElementsByClassName("card");

  for (var i = 0; i < cards.length; i++) {
    var card = cards[i];
    var cardCategory = card.getAttribute("data-category");
    var cardAgeLimit = parseInt(card.getAttribute("data-age-limit"));
    var cardPeopleAmount = card.getAttribute("data-people-amount");

    if (
      (category === "" || category === cardCategory) &&
      (ageLimit === "" || parseInt(ageLimit) >= cardAgeLimit) &&
      (peopleAmount === "" || peopleAmount === cardPeopleAmount)
    ) {
      card.parentElement.style.display = "block";
    } else {
      card.parentElement.style.display = "none";
    }
  }
}
</script>

<?php 
include_once '../includes/footer.php';
?>