<?php
session_start();
include '../includes/dbcon.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='../register_login/loginform.php'>log in as user</a> to make a reservation. " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['quest_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $discount = $_POST['discount'];
    $payment = $_POST['payment_method'];
    $cost = $_POST['cost'];
    $user_id = $_SESSION['user_id'];

    // Check if a reservation already exists for the given date and time
    $query = "SELECT * FROM reservation WHERE room_id='$room_id' AND date='$date' AND time='$time'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "A reservation already exists for this date and time. Please choose another date/time.";
        echo "<a href='../room/quest_list.php'>Back to home page</a>";
        exit();
    }

    // Insert reservation into the database
    $insert_query = "INSERT INTO reservation (date, time, cost, payment, room_id, client_id, creation_date)
             VALUES ('$date', '$time', '$cost', '$payment', '$room_id', '$user_id', CURDATE())";
    mysqli_query($conn, $insert_query);

    // Check if save card information is checked and save the card details
    if (isset($_POST['save_card_info']) && $_POST['save_card_info'] === 'on') {
        $cardDate = $_POST['cardDate'];
        $cardNumber = $_POST['cardNumber'];
        $cardName = $_POST['cardName'];

        // Insert card information with user_id into the card table
        $card_query = "INSERT INTO card (cardDate, cardNumber, cardName, user_id) VALUES
                       ('$cardDate', '$cardNumber', '$cardName', '$user_id')";
        mysqli_query($conn, $card_query);
    }


    // Fetch user details
    $user_query = "SELECT name, surname, email FROM users WHERE id='$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    $user_row = mysqli_fetch_assoc($user_result);

    // Fetch quest room and address details using JOIN
    $quest_query = "
        SELECT q.name AS quest_name, a.buildingAdress AS quest_address 
        FROM quests q
        JOIN adress a ON q.adress_id = a.id
        WHERE q.id='$room_id'";
    $quest_result = mysqli_query($conn, $quest_query);
    $quest_row = mysqli_fetch_assoc($quest_result);

    $user_name = $user_row['name'];
    $user_surname = $user_row['surname'];
    $user_email = $user_row['email'];
    $quest_name = $quest_row['quest_name'];
    $quest_address = $quest_row['quest_address'];

    // Send confirmation email
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kirillquestroom@gmail.com'; // SMTP username
        $mail->Password = 'tdiscwhdffittzmz';        // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('kirillquestroom@gmail.com', 'KirillQuestRoom');
        $mail->addAddress($user_email, $user_name); // Add a recipient

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Reservation Successful';
        $mail->Body    = "Hello, $user_name $user_surname!<br><br>
                          Your reservation has been successfully made for the escape room \"$quest_name\" 
                          at $time on $date, located at: $quest_address.<br><br>
                          Best regards, Team Kirill Quest Room.";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

    echo "Reservation saved successfully!";
    echo "<p><a href='../room/quest_list.php'>Back to home page</a></p>";
}

mysqli_close($conn);
?>