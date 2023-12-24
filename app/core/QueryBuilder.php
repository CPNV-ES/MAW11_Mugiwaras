<?php

namespace Mugiwaras\Framework\Core;

use PDO;


class QueryBuilder
{
    private $pdo;

    private static $instance = null;

    private string $tableName = "";

    private array $whereClauses = [];
    private array $orderClauses = [];

    private string $limitQuery = "";
    private string $joinQuery = "";

    private string $query = "";

    private function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));
    }

    /**
     * get the instance of QueryBuilder
     *
     * @return QueryBuilder
     */
    public static function getInstance(): QueryBuilder
    {
        if (self::$instance == null) {
            self::$instance = new QueryBuilder();
        }

        return self::$instance;
    }

    /**
     * registers table name to be used in query
     *
     * @param  string $name
     * @return object
     */
    public function table(string $name)
    {
        $this->tableName = $name;
        return $this;
    }

    /**
     * gets all rows from table with specified columns. If parameters are not specified, all columns are returned. this method is chainable and must be called as the last method in the chain.
     *
     * @param  array $columns
     * @return array|bool
     */
    public function get(array $columns = ['*']): array|bool
    {
        $this->check(['table']);

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

        $this->prepareClause($this->whereClauses, "WHERE", function (QueryClause $clause) {
            return $clause->getType();
        });
        $this->prepareClause($this->orderClauses, "ORDER BY");

        $this->query .= $this->limitQuery;

        return $this->execute()->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * inserts a row into the table
     *
     * @param  array $columns
     * @param  array $values
     * @return void
     */
    public function save(array $insertSet)
    {
        $this->check(['table']);

        $columns = array_keys($insertSet);
        $values = array_values($insertSet);

        $clause = new QueryClause($columns, null, $values);

        $query = "INSERT INTO " . $this->tableName . " (" . $clause->getColumns() . ") VALUES (" . $clause->getValues() . ")";

        $this->query = $query;
        $this->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * Deletes rows specified with the where method from table
     *
     * @return void
     */
    public function delete()
    {
        $this->check(['table', 'where']);

        $this->query = "DELETE FROM " . $this->tableName;

        $this->prepareClause($this->whereClauses, "WHERE");

        return $this->execute()->rowCount();
    }

    /**
     * updates rows specified with the where method and with the values specified in the parameter
     *
     * @param  array $updateSet
     * @return void
     */
    public function update(array $updateSet)
    {
        $this->check(['table', 'where']);

        $this->query = "UPDATE " . $this->tableName . " SET ";

        $index = array_flip(array_keys($updateSet));

        foreach ($updateSet as $key => $value) {
            $this->query .= $key . '="' . $value . '"';
            if ($index[$key] < count($updateSet) - 1) {
                $this->query .= ", ";
            }
        }

        $this->prepareClause($this->whereClauses, "WHERE", function (QueryClause $clause) {
            return $clause->getType();
        });
        
        return $this->execute()->rowCount();
    }

    private function addWhereClauseToArray(QueryClause $whereClause)
    {
        array_push($this->whereClauses, $whereClause);
        return $this;
    }

    /**
     * adds a (AND) where clause to the query
     *
     * @param  string $column
     * @param  string $operator
     * @param  string $value
     * @param  string $type
     * @return void
     */
    public function where(string $column, string $operator, string|null $value, string $type = "AND")
    {
        if ($value == null) {
            $value = "";
        }
        $this->addWhereClauseToArray(new QueryClause($column, $operator, $value, $type));
        return $this;
    }

    /**
     * adds an or where clause to the query
     *
     * @param  string $column
     * @param  string $operator
     * @param  string $value
     * @return void
     */
    public function orWhere(string $column, string $operator, string $value)
    {
        $this->where($column, $operator, $value, "OR");
        return $this;
    }

    private function addOrderClauseToArray(QueryClause $orderClause)
    {
        array_push($this->orderClauses, $orderClause);
    }

    /**
     * orders the query DESC by the specified columns in descending order
     *
     * @param  array|string $columns
     * @return void
     */
    public function desc(array|string $columns)
    {
        $this->addOrderClauseToArray(new QueryClause($columns, "DESC"));
        return $this;
    }

    /**
     * orders the query ASC by the specified columns in ascending order
     *
     * @param  array|string $columns
     * @return void
     */
    public function asc(array|string $columns)
    {
        $this->addOrderClauseToArray(new QueryClause($columns, "ASC"));
        return $this;
    }

    /**
     * limits the query to the specified number of rows and starts from the specified offset
     *
     * @param  string $limit
     * @param  int $offset
     * @return void
     */
    public function limit(string $limit, int $offset = 0)
    {
        if ($offset > 0) {
            $this->limitQuery .= " LIMIT " . $limit . " OFFSET " . $offset;
            return $this;
        }

        $this->limitQuery .= " LIMIT " . $limit;

        return $this;
    }

    /**
     * joins the specified table with the specified id names
     *
     * @param  string $table
     * @param  string $extIdName
     * @param  string $intIdName
     * @return void
     */
    public function join(string $table, string $extIdName, string $intIdName)
    {
        $this->joinQuery .= " JOIN " . $table . " ON " . $extIdName . " = " . $intIdName;
        return $this;
    }

    private function prepareClause(array $clauses, string $keyword, $separator = ",")
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

    private function execute()
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();

        $this->clean();
        
        return $stmt;
    }

    private function checkIfWhereClausesAreSet()
    {
        if (count($this->whereClauses) == 0) {
            throw new \Exception("No where clause specified");
        }
    }

    private function checkIfTableNameIsSet()
    {
        if ($this->tableName == "") {
            throw new \Exception("No table name specified");
        }
    }

    private function check(array $checkers = [])
    {
        if (count($checkers) == 0) {
            return;
        }
        if (in_array("table", $checkers)) {
            $this->checkIfTableNameIsSet();
        }
        if (in_array("where", $checkers)) {
            $this->checkIfWhereClausesAreSet();
        }
        $checkers = array();
    }

    private function clean()
    {
        $this->tableName = "";
        $this->whereClauses = [];
        $this->orderClauses = [];
        $this->limitQuery = "";
        $this->joinQuery = "";
        $this->query = "";
    }
}
