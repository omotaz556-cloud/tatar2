<?php
include_once 'GameEngine/config.php';
include_once 'GameEngine/Generator.php';
include_once 'GameEngine/CentralGold.php';
include_once 'GameEngine/PaymentShop.php';

$key = strtoupper(trim((string) ($_GET['package'] ?? $_POST['package'] ?? '')));
$packages = PaymentShop::packages();
$message = '';
if (!isset($session->uid) || !isset($packages[$key])) {
    $message = 'Invalid package or session.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string) getenv('MYFATOORAH_API_TOKEN');
    $apiUrl = rtrim((string) getenv('MYFATOORAH_API_URL'), '/');
    if ($token === '' || $apiUrl === '') {
        $message = 'MyFatoorah is not configured. Please contact support.';
    } else {
        $email = trim((string) ($session->userinfo['email'] ?? ''));
        if ((string) ($session->userinfo['act'] ?? '') !== '') {
            $message = 'يجب تأكيد البريد الإلكتروني قبل شراء الذهب.';
        } else {
        $request = json_encode(['InvoiceValue' => $packages[$key]['amount'], 'CurrencyIso' => defined('PAYPAL_CURRENCY') ? PAYPAL_CURRENCY : 'EUR', 'CustomerName' => (string) $session->username, 'CustomerEmail' => $email, 'CallBackUrl' => (defined('HOMEPAGE') ? HOMEPAGE : SERVER) . 'plus.php?id=3&paid=1', 'ErrorUrl' => (defined('HOMEPAGE') ? HOMEPAGE : SERVER) . 'myfatoorah.php?package=' . urlencode($key), 'Language' => (defined('LANG') && LANG === 'ar') ? 'AR' : 'EN', 'CustomerReference' => (int) $session->uid . '-' . $key . '-' . time()]);
        $curl = curl_init($apiUrl . '/v2/SendPayment');
        curl_setopt_array($curl, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $request, CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Type: application/json']]);
        $data = json_decode((string) curl_exec($curl), true); curl_close($curl);
        $invoiceId = (string) ($data['Data']['InvoiceId'] ?? '');
        $invoiceUrl = (string) ($data['Data']['InvoiceURL'] ?? '');
        if ($invoiceId !== '' && $invoiceUrl !== '' && PaymentShop::create($session->uid, $email, $key, $invoiceId)) { header('Location: ' . $invoiceUrl); exit; }
        $message = 'The payment invoice could not be created. Please try again later.';
        }
    }
}
?><!doctype html><html <?php echo tz_html_dir_attrs(); ?>><head><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>MyFatoorah</title><?php echo tz_global_stylesheet_tag(); ?></head><body><h1><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></h1><?php if (isset($packages[$key])): ?><p><?php echo (int) $packages[$key]['gold']; ?> Gold - <?php echo number_format($packages[$key]['amount'], 2); ?></p><form method="post"><input type="hidden" name="package" value="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>"><button type="submit">Pay with MyFatoorah</button></form><?php endif; ?></body></html>