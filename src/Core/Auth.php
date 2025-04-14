<?php
namespace Backoffice\Core;

use Backoffice\Core\Sql\DataLoader;
use Backoffice\Core\Sql\SqlManager;
use Backoffice\Core\Jwt\LiteJwt;
use Backoffice\Core\Jwt\JwtHeader;
use Backoffice\Core\Jwt\Payload;
use Bacnoffice\Views\Errors\ErrorPage;

class Auth {
    private $dataLoader;
    private $sqlMgr;

    public function __construct()
    {
        $this->dataLoader = new DataLoader();
        $this->sqlMgr = new SqlManager();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function check($requiredRole = '*') {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    
        if ($requiredRole === '*') {
            return; // All users have granted access.
        }
    
        $user = $this->user();
        if (!$user) {
            header('Location: /login');
            exit();
        }
    
        $roles = ['user', 'staff', 'collaborator', 'owner', 'webadmin'];

        // Vérifiez que $user['role'] existe et est une chaîne
        if (!isset($user['role']) || !is_string($user['role'])) {
            ErrorPage::Err403();
            exit();
        }


        $userRoleIndex = array_search($user['role'], $roles);
        $requiredRoleIndex = array_search($requiredRole, $roles);
    
        if ($userRoleIndex === false || $requiredRoleIndex === false || $userRoleIndex < $requiredRoleIndex) {
            ErrorPage::Err403();
            exit();
        }
    }

    public function user() {
        if (!isset($_SESSION['user_id'])) return null;
        
        $query = "SELECT id, username, role FROM users WHERE id = ?";
        $params = [$_SESSION['user_id']];
        
        return $this->dataLoader->fetch($query, $params)[0];
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $params = [$username, $email, $hashedPassword];
        
        return $this->sqlMgr->execute($query, $params);
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        
        $user = $this->dataLoader->fetch($query, $params)[0];

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $jwt = new LiteJwt();
            $header = new JwtHeader();
            $payload = new Payload('xexulara.local', (int)$user['id']);
            $payload->setExpirationTime(minutes:5);
            $payload->generateUuid4();
            $jwt->encode($header, $payload);
            $jwt->save();

            header('Location: /home');
            return true;
        }
        return false;
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit();
    }
}
