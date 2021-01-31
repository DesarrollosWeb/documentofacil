<?php
define('DB_NAME', 'DO278_WP');
define('DB_USER', 'DO278_WP');
define('DB_PASSWORD', 'G8;yV2;qX1(a');
define('DB_HOST', 'documentofacil.com');
include_once 'constants.php';
include_once 'Payment.php';
include_once 'Procedure.php';
include_once 'vendor/autoload.php';
header('Content-Type: application/json');
$procedure = new Procedure("");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$input = file_get_contents('php://input');
$body = json_decode($input);


$result = $procedure->create_procedure($body->user_id,
    $body->procedure_type,
    $body->amount,
    $body->payment_info);

echo json_encode(["result" => $result]);
