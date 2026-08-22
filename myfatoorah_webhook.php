<?php
include_once 'GameEngine/config.php';
include_once 'GameEngine/Database.php';
include_once 'GameEngine/CentralGold.php';
include_once 'GameEngine/PaymentShop.php';
include_once 'GameEngine/Mailer.php';
header('Content-Type: application/json');

$secret = defined('MYFATOORAH_WEBHOOK_SECRET') ? (string) MYFATOORAH_WEBHOOK_SECRET : '';
$given = (string) ($_SERVER['HTTP_X_MYFATOORAH_SECRET'] ?? $_POST['secret'] ?? '');
if ($secret === '' || !hash_equals($secret, $given)) { http_response_code(403); echo json_encode(['ok' => false, 'error' => 'invalid_secret']); exit; }
$payload = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($payload)) $payload = $_POST;
$paymentId = (string) ($payload['paymentId'] ?? $payload['payment_id'] ?? $payload['InvoiceId'] ?? '');
$status = (string) ($payload['status'] ?? $payload['InvoiceStatus'] ?? '');
if ($paymentId === '' || $status === '') { http_response_code(400); echo json_encode(['ok' => false, 'error' => 'payment_id_and_status_required']); exit; }
echo json_encode(['ok' => PaymentShop::confirm($paymentId, $status)]);