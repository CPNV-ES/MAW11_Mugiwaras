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
    private string $limitQuery = "";
    private string $joinQuery = "";

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

    public function get(array $columns = ['*'])
    {
        $this->query = "SELECT ";
        foreach ($columns as $key => $column) {
            if ($key > 0) {
                $this->query .= ",";
            }
            $this->query .= $column;
            if ($key == count($columns) - 1) {
                $this->query .= " FROM " . $this->tableName;
            }
        }

        $this->query .= $this->joinQuery;
        $this->prepareClause($this->whereClauses, "WHERE", function ($clause) {
            return $clause->getType();
        });
        $this->prepareClause($this->orderClauses, "ORDER BY", " ");
        $this->query .= $this->limitQuery;

        $this->joinQuery = "";
        $this->limitQuery = "";

        $stmt = $this->pdo->prepare($this->query);
        print_r($stmt);
        die();
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // todo refactor to accept key named array as paramerters instead of two arrays
    public function save($columns, $values)
    {
        $clause = new QueryClause($columns, null, $values);
        $query = "INSERT INTO " . $this->tableName . " (" . $clause->getColumns() . ") VALUES (" . $clause->getValues() . ")";
        $this->query = $query;
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function delete()
    {
        $this->query = "DELETE FROM " . $this->tableName;
        $this->prepareClause($this->whereClauses, "WHERE");
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function update(array $updateSet)
    {
        $this->query = "UPDATE " . $this->tableName . " SET ";
        $index = array_flip(array_keys($updateSet));
        foreach ($updateSet as $key => $value) {
            $this->query .= $key . '="' . $value . '"';
            if ($index[$key] < count($updateSet) - 1) {
                $this->query .= ", ";
            }
        }
        $this->prepareClause($this->whereClauses, "WHERE");
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();
        return $stmt->rowCount();
    }

    private function addWhereClauseToArray(QueryClause $whereClause)
    {
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

    private function addOrderClauseToArray(QueryClause $orderClause)
    {
        array_push($this->orderClauses, $orderClause);
    }

    public function desc(array|string $columns)
    {
        $this->addOrderClauseToArray(new QueryClause($columns, "DESC"));
        return $this;
    }

    public function asc(array|string $columns)
    {
        $this->addOrderClauseToArray(new QueryClause($columns, "ASC"));
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        if ($offset > 0) {
            $this->limitQuery .= " LIMIT " . $limit . " OFFSET " . $offset;
            return $this;
        }
        $this->limitQuery .= " LIMIT " . $limit;
        return $this;
    }

    public function join($table, $extIdName, $intIdName)
    {
        $this->joinQuery .= " JOIN " . $table . " ON " . $extIdName . " = " . $intIdName;
        return $this;
    }

    private function prepareClause($clauses, $keyword, $separator = ",")
    {
        if (count($clauses) == 0) {
            return;
        }
        $this->query .= " " . $keyword;

        foreach ($clauses as $key => $clause) {
            if ($key > 0) {
                if (is_callable($separator)) {
                    $this->query .= " " . $separator($clause);
                } else {
                    $this->query .= $separator;
                }
            }
            $this->query .= " " . $clause;
        }
        $clauses = array();
    }
}
