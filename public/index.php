<?php
require __DIR__ . '/../vendor/autoload.php';
use Backoffice\Core\Router;

$router = new Router(__DIR__ . '/../src/Views');
$router->autoRegisterViews();

// Exemple de route callback
$router->add('status', function() {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'OK']);
});

$router->dispatch();