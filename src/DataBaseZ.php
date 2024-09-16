<?php

namespace hsnmsri\DatabaseZ;

class DataBaseZ
{
    private DatabaseConnection $db_connection;
    private string $table_name;
    private array $where = [];

    public function __construct(DatabaseConnection $db_connection)
    {
        $this->db_connection = $db_connection;
    }

    public function table(string $table_name): DataBaseZ
    {
        $this->table_name = trim(string: $table_name);
        return $this;
    }

    public function where(string $column_name, ?string $condition, string $value): DataBaseZ
    {
        // set column name
        $where[] = $column_name;

        // set condition
        if (!is_null(value: $condition)) {
            $where[] = $condition;
        } else {
            $where[] = '=';
        }

        // set value
        $where[] = $value;

        $this->where[] = $where;

        return $this;
    }
}
