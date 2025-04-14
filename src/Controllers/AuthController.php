<?php
namespace Backoffice\Controllers;

use Backoffice\Core\Auth;
use Backoffice\Core\Jwt\LiteJwt;
use Backoffice\Core\Jwt\JwtHeader;
use Backoffice\Core\Jwt\Payload;
use Backoffice\Core\Sql\DataLoader;

class AuthController
{
    private Auth $auth;
    private LiteJwt $jwt;
    private Dataloader $dataLoader;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->jwt = new LiteJwt();
        $this->dataLoader = new DataLoader();
    }

    public function login()
    {
        // Get the request data
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $query = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        
        $user = $this->dataLoader->fetch($query, $params)[0];

       // Authenticate the user
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            // Generate a JWT token
            $header = new JwtHeader();
            $payload = new Payload('xexulara.local', (int)$user['id']);
            $payload->setExpirationTime(minutes:5);
            $payload->generateUuid4();
            $token = $this->jwt->encode($header, $payload);

            return [
                'status' => 200,
                'body' => [
                    'token' => $token,
                    'user' => $user['id'],
                    'message' => 'Login successful'
                ]
            ];
        } else {
            return [
                'status' => 401,
                'body' => ['error' => 'Invalid credentials', 'code' => 'AUTH_FAILED'],
            ];
        }
    }
}