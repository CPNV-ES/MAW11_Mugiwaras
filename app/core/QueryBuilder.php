<?php

namespace Mugiwaras\Framework\Core;

use PDO;


class QueryBuilder
{
    private static $instance = null;
    private string $idPrefix = "id_";
    private $pdo;

    private string $tableName;
    private array $whereClauses = [];
    private array $orderClauses = [];

    private string $query = "";

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
        $this->query = "SELECT * FROM " . $this->tableName;
        $this->query = $this->prepareWhereClause($this->query);
        $this->query = $this->prepareOrderClause($this->query);
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function addWhereClauseToArray(QueryClause $whereClause){
        array_push($this->whereClauses, $whereClause);
        return $this;
    }

    public function where($column, $operator, $value, $type = "AND")
    {
        $this->addWhereClauseToArray(new QueryClause($column, $operator, $value, $type));
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $this->where($column, $operator, $value, "OR");
        return $this;
    }

    private function addOrderClauseToArray(QueryClause $orderClause){
        array_push($this->orderClauses, $orderClause);
    }

    public function desc(array $columns){
        $this->addOrderClauseToArray(new QueryClause($columns, "DESC"));
        return $this;
    }

    public function asc(array $columns){
        $this->addOrderClauseToArray(new QueryClause($columns, "ASC"));
        return $this;
    }

    private function prepareWhereClause($query)
    {
        if (count($this->whereClauses) == 0) {
            return $query;
        }
        $query .= " WHERE";
        foreach ($this->whereClauses as $key => $whereClause) {
            if ($key > 0) {
                $query .= " " . $whereClause->getType() . " ";
            }
            $query .= " " . $whereClause;
        }
        $this->whereClauses = array();
        return $query;
    }

    private function prepareOrderClause($query){
        if(count($this->orderClauses) == 0){
            return $query;
        }
        $query .= " ORDER BY";
        foreach($this->orderClauses as $key => $orderClause){
            if($key > 0){
                $query .= ",";
            }
            $query .= " " . $orderClause;
        }
        $this->orderClauses = array();
        return $query;
    }
}
