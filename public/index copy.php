<?php
require __DIR__.'/../vendor/autoload.php';

use Backoffice\Core\Router;

$router = new Router(
    basePath: '/xexulara', // Optionnel si dans sous-dossier
    viewPath: __DIR__.'/../src/Views'
);

$router->autoRegisterViews(__DIR__.'/../src/Views');

// Routes spéciales nécessitant un traitement particulier
$router->add('/profile/{id}', function($userId) {
    // Logique pour profile.php avec paramètre
    $_SESSION['user_id'] = $userId;
    require __DIR__.'/../src/Views/profile.php';
});

/* // Déclaration des routes
$router->add('/', 'home.php');
$router->add('', 'home.php'); 
$router->add('/about', 'about.php');
$router->add('/contact', 'contact.php');
$router->add('/dashboard', 'admin/dashboard.php');
$router->add('/blank', 'blank.php');
$router->add('/login', 'login.php');
$router->add('/register', 'register.php');
 */

// Route avec callback
$router->add('/status', function() {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'OK']);
});

$router->dispatch();
