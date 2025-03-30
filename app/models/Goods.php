<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Goods
 * 
 * Esta clase gestiona las operaciones relacionadas con los bienes en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Goods extends Database {

    /**
     * Constructor de la clase Goods.
     * Llama al constructor de la clase padre para inicializar la conexión a la base de datos.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtener la lista de todos los bienes.
     * 
     * @return array Lista de bienes con sus campos 'id', 'nombre' y 'tipo'.
     */
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT id, nombre, tipo FROM bienes");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crear un nuevo bien.
     * 
     * @param string $nombre Nombre del bien.
     * @param int $tipo Tipo del bien (representado como un entero).
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public function create($nombre, $tipo) {
        $stmt = $this->connection->prepare("INSERT INTO bienes (nombre, tipo) VALUES (?, ?)");
        $stmt->bind_param("si", $nombre, $tipo);
        return $stmt->execute();
    }
    
    /**
     * Modificar el nombre de un bien.
     * 
     * @param int $id ID del bien a modificar.
     * @param string $nombre Nuevo nombre del bien.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public function updateName($id, $nombre) {
        $stmt = $this->connection->prepare("UPDATE bienes SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    /**
     * Eliminar un bien.
     * 
     * Nota: Solo se puede eliminar si el bien no tiene relaciones.
     * 
     * @param int $id ID del bien a eliminar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM bienes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>
