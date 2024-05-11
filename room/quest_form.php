<?php
  $pageTitle = 'Quest Room ';
  include_once '../includes/header.php';
	?>
	<h2>Add Quest</h2>
	<form method="post" action="../room/add_quest.php" enctype="multipart/form-data">
		<label for="name">Name:</label>
		<input type="text" name="name" required><br><br>

		<label for="category">Category:</label>
		<input type="text" name="category" required><br><br>

		<label for="adress">Address:</label>
		<input type="text" name="adress" required><br><br>

		<label for="peopleAmount">People Amount:</label>
		<input type="text" name="peopleAmount" required><br><br>

		<label for="ageLimit">Age Limit:</label>
		<input type="text" name="ageLimit" required><br><br>

		<label for="description">Description:</label>
		<textarea name="description" required></textarea><br><br>

		<label for="photo">Photo:</label>
  		<input type="file" name="photo" id="photo" required>

		<input type="submit" name="submit" value="Add Quest">
	</form>


	<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Address</th>
      <th>People Amount</th>
      <th>Age Limit</th>
      <th>Description</th>
      <th>Photo Path</th>
	  <th>Photo </th>
      <th>Update</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>
    <?php


	  $dbhost = 'localhost';
	  $dbname = 'testdb';
	  $dbuser = 'root';
	  $dbpass = '';

	  // Подключаемся к базе данных
	  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	  // Проверяем соединение
	  if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	  }
	
	  // Выполняем запрос к базе данных для получения данных о комнатах
	  $query = "SELECT q.ID, q.name, qc.categoryName, a.buildingAdress, q.peopleAmount, qc.ageLimit, q.description, q.photoPath FROM quests q
                    LEFT JOIN questcategory qc ON q.questCategory_id = qc.ID
                    LEFT JOIN adress a ON q.adress_id = a.ID";
	  $result = mysqli_query($conn, $query);
	
	  // Обрабатываем результаты запроса
	  if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
	
		  // Выводим данные о комнате в таблицу
		  echo "<tr>";
		  echo "<td>".$row['ID']."</td>";
		  echo "<td>".$row['name']."</td>";
		  echo "<td>".$row['categoryName']."</td>";
		  echo "<td>".$row['buildingAdress']."</td>";
		  echo "<td>".$row['peopleAmount']."</td>";
		  echo "<td>".$row['ageLimit']."</td>";
		  echo "<td>".$row['description']."</td>";
		  echo "<td>".$row['photoPath']."</td>";
		  echo "<td><img src='".$row['photoPath']."' alt='Quest photo' style='max-width: 200px; max-height: 200px;'></td>";
		  echo "<td>";
		  echo "<form action='../room/update_quest.php' method='POST'>";
		  echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
		  echo "<button type='submit'>Update</button>";
		  echo "</form>";
		  echo "</td>";
		  echo "<td>";
		  echo "<form action='../room/delete_quest.php' method='POST'>";
		  echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
		  echo "<button type='submit'>Delete</button>";
		  echo "</form>";
		  echo "</td>";
		  echo "</tr>";
		}
	  } else {
		echo "No results";
	  }
	
	  // Закрываем соединение с базой данных
	  mysqli_close($conn);
	
    ?>
  </tbody>
</table>


<?php 
include_once '../includes/footer.php';
?>