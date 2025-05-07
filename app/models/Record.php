<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Record
 * 
 * Esta clase gestiona las operaciones relacionadas con el historial en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Record {

    protected $connection;

    /**
     * Constructor de la clase Record.
     * Obtiene la conexión a la base de datos desde la instancia Singleton de Database.
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
    }

    /**
     * Obtener los últimos 50 registros del historial.
     * 
     * @return array Lista de registros con todos los campos de la tabla 'historial'.
     */
    public function getLastRecords() {
        $stmt = $this->connection->prepare("
            SELECT 
            historial.id,
            usuarios.nombre_usuario AS usuario,
            historial.accion,
            historial.tabla,
            historial.registro_id,
            historial.detalles,
            historial.fecha_hora
            FROM historial
            INNER JOIN usuarios ON historial.usuario_id = usuarios.id
            ORDER BY historial.id DESC
            LIMIT 250;
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
