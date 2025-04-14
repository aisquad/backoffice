<?php
namespace Backoffice\Core\Sql;

use Backoffice\Core\Logger;
use Backoffice\Core\Database;

$logger = new Logger('sql');


class DataLoader extends Database
{
    public function getData($table, $limit = 1000)
    {
        $query = "SELECT * FROM $table LIMIT :limit";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function fetch($query, $params = [])
    {
        if (!preg_match('/^\s*SELECT/i', $query)) {
            throw new \InvalidArgumentException("The fetch method only accepts SELECT queries");
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function load($query, $params = [])
    {
        return json_encode($this->fetch($query, $params));
    }

    public function checkUsername(string $username)
    {
        return $this->fetch("SELECT username FROM users WHERE username = ?", [$username]);
    }

    public function generateUsername(string $name, string $email, string $username)
    {
        return $this->findNearestUsernames($name, $username, $email);
    }

    public function findNearestUsernames(string $name, string $username, string $email)
    {
        $name_parts = explode(' ', $name, 2);
        $name1 = $name_parts[0] ?? '';
        $name2 = $name_parts[1] ?? '';
        
        $email_parts = explode('@', $email);
        $email_username = $email_parts[0] ?? '';
        
        $query = "SELECT username FROM users WHERE 
                  username LIKE :name1 OR 
                  username LIKE :name2 OR 
                  username LIKE :email OR 
                  username LIKE :username";
        
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute([
            ':name1' => $name1 . '%',
            ':name2' => $name2 . '%',
            ':email' => $email_username . '%',
            ':username' => $username . '%'
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
