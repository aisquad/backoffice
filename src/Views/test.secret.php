<?php
namespace Backoffice\Views;

use Backoffice\Core\RsaHandler;

echo "<pre>";
echo "Version OpenSSL: " . OPENSSL_VERSION_TEXT . "\n";
echo "OPPENSSL_CONF:" . getenv('OPENSSL_CONF') . "\n";

require_once __DIR__ . "/core/secret.php";

$rsaHandler = new RsaHandler();
// $rsaHandler->generateKeyPair();
// $rsaHandler->writeKeys();
$rsaHandler->loadKeys();
$data = 'test';
$signature = $rsaHandler->sign($data);
$test = $rsaHandler->verify($data, $signature) ? 'true' : 'false';
echo "---- TEST: {$test}";
echo "</pre>";