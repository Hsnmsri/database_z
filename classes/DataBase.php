<?php

/**
 * DatabaseManager Class
 *
 * This class provides an encapsulated solution for performing essential CRUD operations
 * on a MySQL database using the mysqli extension. It offers streamlined methods for
 * connecting to the database, querying, inserting, updating, and deleting data. By utilizing
 * this class, you can efficiently manage your database interactions with ease.
 *
 * @version 1.0.0
 * @author Hossein Mansouri @hsnmsri
 */

class DataBase
{
    /**
     * @var mysqli|null The database connection instance.
     * Holds the active mysqli database connection.
     */
    private $dbConnection;

    /**
     * Constructor for initializing the database connection.
     *
     * @param string $db_host     Database host address.
     * @param string $db_name     Database name.
     * @param string $db_username Database username.
     * @param string $db_password Database password.
     */
    public function __construct($db_host, $db_name, $db_username, $db_password)
    {
        $this->db_connection($db_host, $db_name, $db_username, $db_password);
    }

    /**
     * Establishes a database connection using provided credentials.
     *
     * @param string $db_host     Database host address.
     * @param string $db_name     Database name.
     * @param string $db_username Database username.
     * @param string $db_password Database password.
     *
     * @throws Exception If connection fails, an error is thrown.
     */
    private function db_connection($db_host, $db_name, $db_username, $db_password)
    {
        try {
            $this->dbConnection = mysqli_connect($db_host, $db_username, $db_password, $db_name);
        } catch (Exception $error) {
            throw ('Error : Line ' . __LINE__ . ' #error ' . $error);
            die;
        }
    }

    /**
     * Executes a SELECT query on the database.
     *
     * @param string      $table_name        Name of the table to query.
     * @param string      $select_column     Columns to select (default is '*').
     * @param string|null $condition_column  Column for the WHERE condition (optional).
     * @param mixed|null  $condition_value   Value for the WHERE condition (optional).
     * @param bool        $fetch_all_mode    Determines if all rows should be fetched (default is true).
     *
     * @return array|null Fetched data from the query result or null if no data found.
     * @throws Exception If there's an error during the query execution.
     */
    public function Select($table_name, $select_column = '*', $condition_column = null, $condition_value = null, $fetch_all_mode = true)
    {
        $res_data = null;
        try {
            $res_data = mysqli_query($this->dbConnection, "SELECT $select_column FROM `$table_name` " . ((is_null($condition_column)) ? null : "WHERE `$condition_column`=" . ((is_numeric($condition_value)) ? $condition_value : "'$condition_value'")));
        } catch (Exception $error) {
            throw ("Error : $error");
            die;
        }
        return (empty($res_data) ? null : ($fetch_all_mode ? mysqli_fetch_all($res_data) : mysqli_fetch_assoc($res_data)));
    }

    /**
     * Inserts data into the specified table.
     *
     * @param string $table_name Name of the table to insert data into.
     * @param array  $data       Associative array containing column names as keys and values to insert.
     *
     * @return bool Returns true on successful insertion, false on failure.
     * @throws Exception If there's an error during the insertion query execution.
     */
    public function Insert($table_name, $data)
    {
        $column_list = '';
        $value_list = '';
        foreach ($data as $key => $value) {
            $column_list .= "`$key`" . ',';
        }
        $column_list = rtrim($column_list, ',');
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $value_list .= $value . ',';
            } else {
                $value_list .= "'$value'" . ',';
            }
        }
        $value_list = rtrim($value_list, ',');
        try {
            mysqli_query($this->dbConnection, "INSERT INTO `$table_name` ($column_list) VALUES ($value_list);");
        } catch (Exception $error) {
            return false;
            die;
        }
        return true;
    }

    /**
     * Deletes records from the specified table based on a condition.
     *
     * @param string $table_name       Name of the table to delete records from.
     * @param string $condition_column Column for the WHERE condition.
     * @param mixed  $condition_value  Value for the WHERE condition.
     *
     * @return bool Returns true on successful deletion, false on failure.
     */
    public function Delete($table_name, $condition_column, $condition_value)
    {
        try {
            mysqli_query($this->dbConnection, "DELETE FROM `$table_name` WHERE `$condition_column`=" . ((is_numeric($condition_value)) ? $condition_value : "'$condition_value'") . ';');
        } catch (Exception $error) {
            return false;
        }
        return true;
    }

    /**
     * Updates records in the specified table based on a condition.
     *
     * @param string $table_name       Name of the table to update records in.
     * @param array  $data             Associative array containing column names as keys and values to update.
     * @param string $condition_column Column for the WHERE condition.
     * @param mixed  $condition_value  Value for the WHERE condition.
     *
     * @return bool Returns true on successful update, false on failure.
     */
    public function Update($table_name, $data, $condition_column, $condition_value)
    {
        $data_string = '';
        foreach ($data as $key => $value) {
            $data_string .= "`$key`=" . ((is_numeric($value)) ? $value : "'$value'") . ',';
        }
        $data_string = rtrim($data_string, ',');
        try {
            mysqli_query($this->dbConnection, "UPDATE `$table_name` SET $data_string WHERE `$condition_column`=" . ((is_numeric($condition_value)) ? $condition_value : "'$condition_value'") . ';');
        } catch (Exception $error) {
            return false;
        }
        return true;
    }
}
