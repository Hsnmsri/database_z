<?php

namespace hsnmsri\DatabaseZ;

/**
 * Class DatabaseConnection
 * 
 * This class represents a connection to a MySQL database.
 * It contains the necessary parameters for establishing a database connection
 * and can be instantiated with or without specific parameters.
 */
class DatabaseConnection
{
    /**
     * @var string $host The hostname or IP address of the MySQL server.
     */
    public string $host;

    /**
     * @var string $username The username for connecting to the database.
     */
    public string $username;

    /**
     * @var string $password The password for connecting to the database.
     */
    public string $password;

    /**
     * @var string $database The name of the database to connect to.
     */
    public string $database;

    /**
     * @var int $port The port number for connecting to the MySQL server.
     */
    public int $port;

    /**
     * @var string $socket The socket or named pipe for connecting to the MySQL server.
     */
    public string $socket;

    /**
     * DatabaseConnection constructor.
     * 
     * Initializes the DatabaseConnection object with the provided parameters.
     * If no parameters are passed, default values (null) will be used.
     * 
     * @param string|null $host The hostname or IP address of the MySQL server.
     * @param string|null $username The username for connecting to the database.
     * @param string|null $password The password for connecting to the database.
     * @param string|null $database The name of the database to connect to.
     * @param int|null $port The port number for connecting to the MySQL server.
     * @param string|null $socket The socket or named pipe for connecting to the MySQL server.
     */
    public function __construct(
        ?string $host = null,
        ?string $username = null,
        ?string $password = null,
        ?string $database = null,
        ?int $port = null,
        ?string $socket = null
    ) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
        $this->socket = $socket;
    }
}
