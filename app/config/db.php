<?php

/**
 * Clase Database
 * 
 * Esta clase se encarga de gestionar la conexión a la base de datos utilizando MySQLi.
 * Implementa el patrón Singleton para asegurar una única instancia de conexión.
 * 
 * Métodos:
 * - getInstance(): Devuelve la única instancia de la clase Database.
 * - getConnection(): Devuelve la conexión activa a la base de datos.
 * - __destruct(): Cierra la conexión a la base de datos al destruir la instancia de la clase.
 * 
 */
class Database {
    private static $instance = null;
    protected $connection;

    /**
     * Constructor privado para evitar la creación directa de instancias.
     */
    private function __construct() {
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
        $this->setTimezone();
        // Establecer el usuario actual si está disponible
        $this->setCurrentUser();
    }

    /**
     * Obtiene la única instancia de la clase Database.
     * Si no existe, la crea.
     * 
     * @return Database La única instancia de Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prevenir la clonación del objeto
     */
    private function __clone() {}

    /**
     * Prevenir la deserialización
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton.");
    }

    /**
     * Obtener la conexión a la base de datos
     * 
     * @return mysqli La conexión a la base de datos
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Establecer el usuario actual en la conexión MySQL
     */
    public function setCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            $userId = (int)$_SESSION['user_id'];
            $this->connection->query("SET @usuario_actual = $userId");
        }
    }

    /**
     * Establecer la zona horaria para la conexión
     */
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
    
    /**
     * Destructor de la clase
     * Cierra la conexión cuando el script finaliza o cuando la instancia de Database es destruida
     */
    public function __destruct() {
        // Solo cerramos la conexión si el script está finalizando
        // para evitar cerrarla mientras otras clases siguen usándola
        if (self::$instance === $this && php_sapi_name() !== 'cli') {
            // Comprobamos si el script está terminando
            if (connection_status() === CONNECTION_NORMAL) {
                $this->connection->close();
            }
        }
    }
}