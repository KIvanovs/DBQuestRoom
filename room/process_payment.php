<?php
// process_payment.php
require '../vendor/autoload.php';

// Установите свои ключи API Stripe
\Stripe\Stripe::setApiKey('your_secret_key_here');

header('Content-Type: application/json');

$payload = @file_get_contents('php://input');
$data = json_decode($payload, true);

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $data['amount'],
        'currency' => 'usd',
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?>