<?php
namespace Backoffice\Middlewares;

use Backoffice\Core\Jwt\LiteJwt;

class JwtMiddleware
{
    public function handle()
    {
        $jwt = new LiteJwt();

        // Get the JWT token from the Authorization header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            return false; // Token not provided
        }

        // Verify the token
        return $jwt->verify($token);
    }
}