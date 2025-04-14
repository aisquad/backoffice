<?php
namespace Backoffice\Core;

use Backoffice\Core\Config;
use \PDO;

class Database
{
    protected $pdo;

    public function __construct()
    {
        $config = Config::getInstance();
        $dsn = "mysql:host={$config->db_host};port={$config->db_port};dbname={$config->db_name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $config->db_user, $config->db_pswd, $options);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Connection failed: " . $e->getMessage());
        }
    }
}

