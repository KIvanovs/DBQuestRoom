<?php
$pageTitle = 'Quest Room ';
include_once '../includes/header.php';
?>


<div class="container mt-5">
    <h2>Add Quest</h2>
    <form method="post" action="../room/add_quest.php" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <div class="invalid-feedback">Please enter a name.</div>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" class="form-control" id="category" name="category" required>
            <div class="invalid-feedback">Please enter a category.</div>
        </div>
        
        <div class="form-group">
            <label for="adress">Address:</label>
            <input type="text" class="form-control" id="adress" name="adress" required>
            <div class="invalid-feedback">Please enter an address.</div>
        </div>
        
        <div class="form-group">
            <label for="peopleAmount">People Amount:</label>
            <input type="text" class="form-control" id="peopleAmount" name="peopleAmount" required>
            <div class="invalid-feedback">Please enter the number of people.</div>
        </div>
        
        <div class="form-group">
            <label for="ageLimit">Age Limit:</label>
            <input type="text" class="form-control" id="ageLimit" name="ageLimit" required>
            <div class="invalid-feedback">Please enter an age limit.</div>
        </div>
        
        <div class="form-group ">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
            <div class="invalid-feedback">Please enter a description.</div>
        </div>
        
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control-file" id="photo" name="photo" required>
            <div class="invalid-feedback">Please upload a photo.</div>
        </div>
        
        <button type="submit" class="btn btn-primary">Add Quest</button>
    </form>
</div>

<style>
    .table-fixed {
        table-layout: fixed;
        width: 100%;
    }
    .table-fixed th, .table-fixed td {
        white-space: normal;
        overflow: hidden;
        word-wrap: break-word;
    }
    .name-column {
        width: 100px; /* Adjust the width as needed */
    }
	.category-column {
        width: 100px; /* Adjust the width as needed */
    }
    .description-column {
        width: 350px; /* Adjust the width as needed */
    }
    .photo-path-column {
        width: 200px; /* Adjust the width as needed */
    }
	.photo-column img {
        max-width: 100px; /* Adjust the width as needed */
        max-height: 100px; /* Adjust the height as needed */
        width: auto;
        height: auto;
    }
</style>

<div class="container mt-5">
    <h2 class="mt-5">Quest List</h2>
    <table class="table table-striped table-fixed">
        <thead>
            <tr>
                <th>ID</th>
                <th class="name-column">Name</th>
                <th class="category-column">Category</th>
                <th>Address</th>
                <th>People Amount</th>
                <th>Age Limit</th>
                <th class="description-column">Description</th>
                <th class="photo-path-column">Photo Path</th>
                <th class="photo-column">Photo</th>
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
                    echo "<tr>";
                    echo "<td>".$row['ID']."</td>";
                    echo "<td class='name-column'>".$row['name']."</td>";
                    echo "<td class='category-column'>".$row['categoryName']."</td>";
                    echo "<td>".$row['buildingAdress']."</td>";
                    echo "<td>".$row['peopleAmount']."</td>";
                    echo "<td>".$row['ageLimit']."</td>";
                    echo "<td class='description-column'>".$row['description']."</td>";
                    echo "<td class='photo-path-column'>".$row['photoPath']."</td>";
                    echo "<td class='photo-column'><img src='".$row['photoPath']."' alt='Quest photo'></td>";
                    echo "<td>";
                    echo "<form action='../room/update_room.php' method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
                    echo "<button type='submit' class='btn btn-warning btn-sm'>Update</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='../room/delete_room.php' method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11' class='text-center'>No results</td></tr>";
            }

            // Закрываем соединение с базой данных
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php 
include_once '../includes/footer.php';
?>
