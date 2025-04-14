<?php
namespace Backoffice\Core\Sql;

use Backoffice\Core\Logger;
use Backoffice\Core\Database;

$logger = new Logger('sql');


class SqlManager extends Database
{
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function select($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = "INSERT INTO $table (" . implode(', ', $keys) . ") VALUES (" . implode(', ', array_fill(0, count($keys), '?')) . ")";
        $this->query($sql, $values);
        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $where)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE $where";
        $values = array_values($data);
        $this->query($sql, $values);
    }

    public function delete($table, $where)
    {
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql);
    }

    public function execute($query, $params = [])
    {
        $stmt = $this->query($query, $params);
        return $stmt->rowCount();
    }
}

