<?php
file_put_contents(
    __DIR__ . '/../../logs/rewrite_debug.log',
    date('Y-m-d H:i:s') . " - " . $_SERVER['REQUEST_URI'] . "\n",
    FILE_APPEND
);

require __DIR__ . '/../../vendor/autoload.php';

use Backoffice\Api\ApiRoutes;
use Backoffice\Core\Logger;

$logger = new Logger('php');
ob_start();

// Autoriser les requêtes cross-origin
header('Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
header('Access-Control-Allow-Methods: GET, PATCH, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, Origin');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the HTTP method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Clean the URI (remove '/api' prefix if necessary)
$uri = str_replace('/api', '', $uri);

// Create an instance of Routes
$routes = new ApiRoutes();

// Handle the request and get the response
$response = $routes->handleRequest($method, $uri);

// misformatted JSON
if (!is_array($response) || !array_key_exists('body', $response)) {
    file_put_contents(
        __DIR__ . '/../../logs/api_errors.log',
        date('Y-m-d H:i:s') . " - Invalid response format: " . print_r($response, true) . "\n",
        FILE_APPEND
    );
    $response = [
        'status' => 500,
        'body' => ['error' => 'Internal server error']
    ];
}

$output = ob_get_clean();
if(!empty($output)) {
    $logger->debug("Unexpected output: " . $output);
}

// Send the response to the client
http_response_code($response['status']);
header('Content-Type: application/json');
echo json_encode($response['body']);

