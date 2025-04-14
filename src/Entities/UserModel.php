<?php

namespace Backoffice\Entities;

class UserModel
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(array $dbUser)
    {
        $this->id = $dbUser['id'];
        $this->username = $dbUSer['username'];
        $this->email = $dbUser['email'];
        $this->password = $dbUser['password'];
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // Setters
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        // En production, utilisez toujours un hachage sécurisé comme password_hash()
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}
