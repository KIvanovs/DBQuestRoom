<?php
session_start();
include '../includes/dbcon.php';
require '../vendor/autoload.php'; // Ensure this is the correct path to the autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mpdf\Mpdf;
use Stripe\Stripe;
use Stripe\Charge;

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='../register_login/loginform.php'>log in as user</a> to make a reservation. " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stripeToken = isset($_POST['stripeToken']) ? $_POST['stripeToken'] : null;

    if (!$stripeToken) {
        echo "Payment failed: Must provide source or customer.";
        exit;
    }

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

    if ($payment === 'card') {
        // Set your secret key. Remember to switch to your live secret key in production!
        Stripe::setApiKey('your_secret_key'); // замените 'your_secret_key' на ваш секретный ключ

        try {
            $charge = Charge::create([
                'amount' => 50, // amount in cents
                'currency' => 'eur',
                'description' => 'Quest Room Reservation',
                'source' => $stripeToken,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo 'Payment failed: ' . $e->getMessage();
            exit();
        }
    }

    // Insert reservation into the database
    $insert_query = "INSERT INTO reservation (date, time, cost, payment, room_id, client_id, creation_date)
                     VALUES ('$date', '$time', '$cost', '$payment', '$room_id', '$user_id', CURDATE())";
    mysqli_query($conn, $insert_query);

    // Check if save card information is checked and save the card details
    if (isset($_POST['save-card']) && $_POST['save-card'] === 'on') {
        $cardDate = $_POST['cardDate'];
        $cardNumber = $_POST['cardNumber'];
        $cardName = $_POST['cardName'];
    
        // Обработать форматирование данных карты перед вставкой в БД
        $cardDate = mysqli_real_escape_string($conn, $cardDate);
        $cardNumber = mysqli_real_escape_string($conn, $cardNumber);
        $cardName = mysqli_real_escape_string($conn, $cardName);
        $cardDate = date('m/y', strtotime($cardDate));
    
        // Вставить данные карты в таблицу
        $card_query = "INSERT INTO card (cardDate, cardNumber, cardName, user_id) VALUES ('$cardDate', '$cardNumber', '$cardName', '$user_id')";
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

    // Ensure the receipts directory exists
    $receipts_dir = '../receipts/';
    if (!is_dir($receipts_dir)) {
        mkdir($receipts_dir, 0777, true);
    }

    // Generate PDF receipt using MPDF
    $mpdf = new Mpdf();
    $receipt_content = "
        <style>
            body { font-family: Arial, sans-serif; }
            .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
            .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
            .invoice-box table td { padding: 5px; vertical-align: top; }
            .invoice-box table tr td:nth-child(2) { text-align: right; }
            .invoice-box table tr.top table td { padding-bottom: 20px; }
            .invoice-box table tr.information table td { padding-bottom: 40px; }
            .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
            .invoice-box table tr.details td { padding-bottom: 20px; }
            .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
            .invoice-box table tr.item.last td { border-bottom: none; }
            .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        </style>
        <div class='invoice-box'>
            <table cellpadding='0' cellspacing='0'>
                <tr class='top'>
                    <td colspan='2'>
                        <table>
                            <tr>
                                <td class='title'>
                                    <h2>Quest Room Reservation</h2>
                                </td>
                                <td>
                                    <strong>Reservation Date:</strong> " . date('Y-m-d') . "<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class='information'>
                    <td colspan='2'>
                        <table>
                            <tr>
                                <td>
                                    <strong>User:</strong> $user_name $user_surname<br>
                                    <strong>Email:</strong> $user_email
                                </td>
                                <td>
                                    <strong>Quest Room:</strong> $quest_name<br>
                                    <strong>Address:</strong> $quest_address<br>
                                    <strong>Reservation Date:</strong> $date<br>
                                    <strong>Time:</strong> $time
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class='heading'>
                    <td>Payment Method</td>
                    <td>Cost</td>
                </tr>
                <tr class='details'>
                    <td>$payment</td>
                    <td>$cost EUR</td>
                </tr>
            </table>
        </div>
    ";

    $mpdf->WriteHTML($receipt_content);
    $receipt_filename = $receipts_dir . "receipt_" . $user_id . "_" . date('Ymd_His') . ".pdf";
    $mpdf->Output($receipt_filename, \Mpdf\Output\Destination::FILE);

    // Send email with receipt as attachment using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Quest Room');
        $mail->addAddress($user_email, "$user_name $user_surname");

        $mail->isHTML(true);
        $mail->Subject = 'Reservation Confirmation';
        $mail->Body    = 'Thank you for your reservation. Please find the attached receipt.';
        $mail->addAttachment($receipt_filename);

        $mail->send();
        echo 'Reservation successful. Confirmation email sent.';
    } catch (Exception $e) {
        echo "Reservation successful, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Redirect to the home page or confirmation page
    header('Location: ../room/quest_list.php');
    exit();
} else {
    echo "Invalid request method.";
}
?>