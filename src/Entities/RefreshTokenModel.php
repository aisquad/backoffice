<?php
namespace Backoffice\Entities;

class RefreshTokenModel {
    public int $id;
    public int $user_id;
    public string $token;
    public DateTime $expires_at;
    public bool $revoked;

    public function __construct(array $dbData) {
        $this->id = (int)$dbData['id'];
        $this->user_id = (int)$dbData['user_id'];
        $this->token = (string)$dbData['token'];
        $this->expires_at = new DateTime($dbData['expires_at']);
        $this->revoked = (bool)$dbData['revoked'];
    }
}
