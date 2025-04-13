<?php

namespace App\Infrastructure\Persistence\MySQL;

use mysqli;

class MySQLConnection
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = $_ENV['MYSQL_HOST'];
        $username = $_ENV['MYSQL_USER'];
        $password = $_ENV['MYSQL_PASSWORD'];
        $database = $_ENV['MYSQL_DATABASE'];

        $this->connection = new mysqli($host, $username, $password, $database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
