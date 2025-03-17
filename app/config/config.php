<?php

class Database {
    private $connection;

    public function __construct() {
        $config = require __DIR__ . '/db.php';
        $this->connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        // echo "Connected successfully";
    }

    public function getConnection() {
        return $this->connection;
    }

    public function __destruct() {
        $this->connection->close();
    }
}


// create a new instance of the Database class
// $database = new Database();
// $connection = $database->getConnection();