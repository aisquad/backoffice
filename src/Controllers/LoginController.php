<?php
namespace Backoffice\Controllers;

use Backoffice\Entities\UserModel;

class LoginController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Vérifiez les identifiants (ceci est un exemple simplifié)
            if ($this->authenticate($username, $password)) {
                $_SESSION['user_id'] = $username; // En pratique, utilisez un ID unique
                $_SESSION['is_logged_in'] = true;
                
                // Rediriger vers la page d'accueil après connexion
                header('Location: /');
                exit;
            } else {
                // Gérer l'échec de connexion
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
                require_once __DIR__ . '/../Views/login.php';
            }
        }
    }

    public function logout()
    {
        // Détruire la session et rediriger vers la page de connexion
        session_destroy();
        header('Location: /login');
        exit;
    }

    private function authenticate($username, $password)
    {
        // Ceci est une méthode simplifiée pour l'exemple
        // En production, vous devriez vérifier les identifiants dans une base de données
        // et utiliser un hachage sécurisé pour les mots de passe
        $validUsername = 'admin';
        $validPassword = 'password123';

        return ($username === $validUsername && $password === $validPassword);
    }
}
