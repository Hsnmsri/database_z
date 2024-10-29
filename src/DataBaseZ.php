<?php

namespace hsnmsri\DatabaseZ;

use Exception;

enum QueryType: string
{
    case SELECT = 'select';
    case INSERT = 'insert';
    case UPDATE = 'update';
    case DELETE = 'delete';
}

class DataBaseZ
{
    private DatabaseConnection $db_connection;
    private \mysqli|false $mysqliConnection;
    private string $table_name;
    private QueryType $query_type;
    private array $where = [];
    private array $values = [];
    private array $columns = [];


    public function __construct(DatabaseConnection $db_connection)
    {
        $this->db_connection = $db_connection;
        $this->connectToDB();
    }

    public function table(string $table_name): DataBaseZ
    {
        $this->table_name = trim(string: $table_name);
        return $this;
    }

    public function where(string $column_name, string $condition = '=' | "<" | ">" | ">=" | "<=" | "<>", string $value): DataBaseZ
    {
        $this->where[] = [$column_name, $condition, $value];

        return $this;
    }

    public function values($valueArray = []): DataBaseZ
    {
        if (!is_array(value: $valueArray)) {
            throw new \Exception(message: "data of value is not array! error on line " . __LINE__ . " file " . __FILE__);
        }

        $this->values = $valueArray;

        return $this;
    }

    public function select($columns = '*'): DataBaseZ
    {
        $this->query_type = QueryType::SELECT;

        // select all columns
        if ($columns == '*') {
            $this->columns = [];
        }

        // select some columns
        if (is_array(value: $columns)) {
            $this->columns = $columns;
        }

        return $this;
    }

    public function insert(): DataBaseZ
    {
        $this->query_type = QueryType::INSERT;
        return $this;
    }

    public function delete(): DataBaseZ
    {
        $this->query_type = QueryType::DELETE;
        return $this;
    }

    public function update(): DataBaseZ
    {
        $this->query_type = QueryType::UPDATE;
        return $this;
    }

    public function execute()
    {
        var_dump($this->exeSelect());
    }


    private function connectToDB(): void
    {
        try {

            $this->mysqliConnection = mysqli_connect(
                hostname: $this->db_connection->host,
                username: $this->db_connection->username,
                password: $this->db_connection->password,
                database: $this->db_connection->database,
                port: $this->db_connection->port,
                socket: $this->db_connection->socket
            );
        } catch (Exception $errors) {
            throw new Exception(message: "database connection failed!");
        }
    }

    private function exeSelect(): array|bool
    {
        // Build the condition string
        $conditionString = "";
        if (!empty($this->where)) {
            $conditions = array_map('arrayToCondition', $this->where);
            $conditionString = implode(' AND ', $conditions);
        }

        // Build fields part of the query
        $fields = !empty($this->columns) ? implode(', ', array_map(fn($col) => "`$col`", $this->columns)) : '*';

        // Construct the final query
        $query = "SELECT $fields FROM `$this->table_name`";
        if (!empty($conditionString)) {
            $query .= " WHERE $conditionString";
        }

        try {
            // Execute the query
            $result = $this->mysqliConnection->query($query);

            if (!$result) {
                return false;
            }

            // Fetch all results as an associative array
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $error) {
            throw $error;
        }
    }

    private function exeInsert(): bool
    {
        // Convert field names and values to comma-separated strings
        $fields = implode(',', array_map(fn($field) => "`$field`", array_keys($this->values)));
        $values = implode(',', array_map(fn($value) => is_string($value) ? "'$value'" : $value, array_values($this->values)));

        // Execute query
        try {
            $query = "INSERT INTO `$this->table_name` ($fields) VALUES ($values)";
            $result = $this->mysqliConnection->query($query);

            if (!$result) {
                return false;
            }
        } catch (Exception $error) {
            throw $error;
        }

        return true;
    }

    private function arrayToCondition(array $condition): string
    {
        [$field, $operator, $value] = $condition;
        $value = is_string($value) ? "'$value'" : $value;
        return "`$field` $operator $value";
    }
}
