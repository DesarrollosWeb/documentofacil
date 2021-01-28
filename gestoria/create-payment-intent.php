<?php
include_once 'constants.php';
include_once 'Payment.php';
include_once 'vendor/autoload.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$input = file_get_contents('php://input');
$body = json_decode($input);

try {
    $paymentIntent = Payment::create_payment_intent(["amount" => $body->amount]);
    $output = [
        'publishableKey' => STRIPE_API,
        'clientSecret' => $paymentIntent->client_secret,
    ];
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "trace" => $e->getTraceAsString(),
        "line" => $e->getLine()
    ]);
}

echo json_encode($output);

