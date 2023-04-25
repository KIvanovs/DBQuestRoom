<?php
// Connect to database
$dbhost = 'localhost';
$dbname = 'testdb';
$dbuser = 'root';
$dbpass = '';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	// Get quest ID from URL query string
	if (isset($_GET['ID'])) {
		$quest_id = $_GET['ID'];

		// Select quest from the database
		$query = "SELECT * FROM quests WHERE ID='$quest_id'";
		$result = mysqli_query($conn, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$name = $row['name'];
			$category = $row['category'];
			$address = $row['adress'];
			$discount = $row['discount'];
			$peopleAmount = $row['peopleAmount'];
			$ageLimit = $row['ageLimit'];
			$description = $row['description'];
			$photoPath = $row['photoPath'];
		} else {
			die("Quest not found!");
		}
	} else {
		die("Invalid request!");
	}
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $name; ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="card">
        <div class="card-image">
            <img src="<?php echo $photoPath; ?>" alt="photo of <?php echo $name; ?>">
        </div>
        <div class="card-content">
            <h2><?php echo $name; ?></h2>
            <p>Category: <?php echo $category; ?></p>
            <p>Address: <?php echo $address; ?></p>
            <p>Discount: <?php echo $discount; ?> %</p>
            <p>Number of people: <?php echo $peopleAmount; ?></p>
            <p>Age limit: <?php echo $ageLimit; ?></p>
            <p>Description: <?php echo $description; ?></p>
            <form action="reserve.php" method="post">
                <h1>Reservation</h1>
                <input type="hidden" name="quest_id" value="<?php echo $quest_id; ?>">
                <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required>
                <br><br>
                <label>Time:</label><br>
                <button type="button" name="time" value="10:00" onclick="selectTime('10:00', 60)">10:00 (60 EUR)</button>
                <button type="button" name="time" value="11:30" onclick="selectTime('11:30', 60)">11:30 (60 EUR)</button>
                <button type="button" name="time" value="13:30" onclick="selectTime('13:30', 60)">13:30 (60 EUR)</button>
                <button type="button" name="time" value="14:30" onclick="selectTime('14:30', 60)">14:30 (60 EUR)</button>
                <button type="button" name="time" value="16:00" onclick="selectTime('16:00', 60)">16:00 (60 EUR)</button>
                <button type="button" name="time" value="17:30" onclick="selectTime('17:30', 60)">17:30 (60 EUR)</button>
                <button type="button" name="time" value="19:00" onclick="selectTime('19:00', 60)">19:00 (60 EUR)</button>
                <button type="button" name="time" value="20:30" onclick="selectTime('20:30', 80)">20:30 (80 EUR)</button>
                <button type="button" name="time" value="22:00" onclick="selectTime('22:00', 80)">22:00 (80 EUR)</button>
                <br><br>
                <div id="payment-modal" class="modal">
                    <div class="modal-content">
                        <h2>Payment</h2>
                        <p>You have selected:</p>
                        <p id="selected-time"></p>
                        <p>Total price: <span id="total-price"></span> EUR</p>
                        <label for="payment-method">Payment method:</label>
                        <select id="payment-method" name="payment_method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                        <button type="submit" name="submit">Reserve</button>
                    </div>
                </div>
            </form>

            <script>
                
                var discount = <?php echo $discount ?>;

                function selectTime(time, price) {
                    var selectedTime = time;
                    var total = price;
                    
                    totaldiscount = total / 100 * discount ;
                    total = total - totaldiscount ;
                    cost = parseFloat(total.toFixed(2));

                    document.getElementById("selected-time").innerHTML = selectedTime;
                    document.getElementById("total-price").innerHTML = cost;
                    document.getElementById("payment-modal").style.display = "block";

                    cookie(time);
                    
                    // $.ajax({
                    //     type: "POST",
                    //     url: "reserve.php",
                    //     data: { 
                    //         time: selectedTime, 
                    //         cost: cost 
                    //     },
                    //     success: function(response) {
                    //         console.log(response);
                    //     }
                    // });
                    

                }
                function cookie(time){
                    document.cookie = "time="+time;
                }

            </script>
        </div>
    </div>
</body>
</html>
