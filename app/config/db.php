<?php

/**
 * Clase Database
 * 
 * Esta clase se encarga de gestionar la conexión a la base de datos utilizando MySQLi.
 * 
 * Métodos:
 * - __construct(): Establece la conexión a la base de datos utilizando los parámetros de configuración.
 * - getConnection(): Devuelve la conexión activa a la base de datos.
 * - __destruct(): Cierra la conexión a la base de datos al destruir la instancia de la clase.
 * 
 */
class Database {
    protected $connection;

    public function __construct() {
        $config = require __DIR__ . '/config.php';
        $this->connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port']
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

