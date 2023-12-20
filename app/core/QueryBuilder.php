<?php

namespace Mugiwaras\Framework\Core;

use mysqli;
use PDO;


class QueryBuilder
{
    private static $instance = null;
    private string $idPrefix = "id_";
    private $pdo;

    private string $tableName;
    private array $whereClauses = [];

    private function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));
    }

    public static function getInstance(): QueryBuilder
    {
        if (self::$instance == null) {
            self::$instance = new QueryBuilder();
        }

        return self::$instance;
    }

    public function table($name)
    {
        $this->tableName = $name;
        return $this;
    }

    public function get()
    {
        $query = "SELECT * FROM " . $this->tableName;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
