<?php
namespace Backoffice\Core;

use Backoffice\Core\Auth;

$auth = new Auth();
$user = $auth->user();

if (!$user) {
    http_response_code(401);
    $json = ["error" => "Unauthorized"];
    exit;
}

$userId = $user['id'];
$payload = json_encode([
    'user_id' => $userId,
    'exp' => time() + 3600
]);

$secret = $user['secret_key'];
$signature = hash_hmac('sha256', $payload, $secret);
$token = base64_encode("$payload.$signature");