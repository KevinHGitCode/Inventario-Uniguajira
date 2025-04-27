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

        // Establecer la zona horaria para esta conexión
        // Esto es para colombia pero no funciona en otros paises
        // $this->connection->query("SET time_zone = '-05:00'"); // Ajusta a tu zona horaria (ejemplo: GMT-5)
        $this->setTimezone();
        // Establecer el usuario actual si está disponible
        $this->setCurrentUser();
    }

    public function getConnection() {
        return $this->connection;
    }

    public function setCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            $userId = (int)$_SESSION['user_id'];
            $this->connection->query("SET @usuario_actual = $userId");
        }
    }

    public function setTimezone() {
        // Verificar si tenemos el offset de zona horaria en la sesión
        if (isset($_SESSION['timezone_offset'])) {
            $timezone_offset = $this->connection->real_escape_string($_SESSION['timezone_offset']);
            
            // Validar que el formato sea correcto (+/-HH:MM)
            if (preg_match('/^[+-]\d{2}:\d{2}$/', $timezone_offset)) {
                // Establecer la zona horaria para esta conexión
                $this->connection->query("SET time_zone = '$timezone_offset'");
                // echo "Zona horaria establecida: $timezone_offset";
            }
        } 
        // Si no hay offset en la sesión o no es válido, usar una zona horaria predeterminada
        else {
            $this->connection->query("SET time_zone = '-05:00'"); // Zona horaria predeterminada (GMT-5)
        }
        
        // Si quieres verificar que se aplicó correctamente
        /*
        $result = $this->connection->query("SELECT NOW() as current_time");
        $row = $result->fetch_assoc();
        echo "Hora actual del servidor con zona horaria aplicada: " . $row['current_time'];
        */
    }
    

    public function __destruct() {
        $this->connection->close();
    }
}

