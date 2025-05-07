<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Groups
 * 
 * Esta clase gestiona las operaciones relacionadas con los grupos en la base de datos.
 * Utiliza el patrón Singleton de Database para la conexión a la base de datos.
 */
class Groups {
    protected $connection;

    /**
     * Constructor de la clase Groups.
     * Obtiene la conexión a la base de datos desde la instancia Singleton de Database.
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
    }

    /**
     * Obtener todos los grupos con la cantidad de inventarios que tiene cada uno.
     * 
     * @return array Lista de grupos con los campos 'id', 'nombre' y 'total_inventarios'.
     */
    public function getAllGroups() {
        $stmt = $this->connection->prepare("
            SELECT 
                g.id AS id, 
                g.nombre AS nombre,
                COUNT(i.id) AS total_inventarios
            FROM grupos g
            LEFT JOIN inventarios i ON g.id = i.grupo_id
            GROUP BY g.id, g.nombre
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener información de un grupo por su ID.
     * 
     * @param int $id ID del grupo.
     * @return array|null Datos del grupo o null si no se encuentra.
     */
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT id, nombre FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Crear un nuevo grupo.
     * 
     * @param string $nombre Nombre del grupo.
     * @return int|false ID del grupo creado si fue exitoso, false en caso contrario.
     */
    public function create($nombre) {
        if ($this->existsByName($nombre)) {
            return false; // El nombre ya existe
        }

        $stmt = $this->connection->prepare("INSERT INTO grupos (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        if ($stmt->execute()) {
            return $stmt->insert_id; // Retornar el ID del grupo recién creado
        }

        return false;
    }

    /**
     * Actualizar el nombre de un grupo.
     * 
     * @param int $id ID del grupo.
     * @param string $nuevoNombre Nuevo nombre del grupo.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function rename($id, $nuevoNombre) {
        if ($this->existsByName($nuevoNombre)) {
            return false; // No se puede actualizar si el nombre ya existe
        }

        $stmt = $this->connection->prepare("UPDATE grupos SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevoNombre, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Eliminar un grupo si no tiene inventarios asociados.
     * 
     * @param int $id ID del grupo.
     * @return bool True si el grupo fue eliminado exitosamente, false si tiene inventarios asociados o no se eliminó.
     */
    public function delete($id) {
        $stmt = $this->connection->prepare("SELECT id FROM inventarios WHERE grupo_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return false; // No se puede eliminar si tiene inventarios asociados
        }

        $stmt = $this->connection->prepare("DELETE FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Verificar si existe un grupo con un nombre específico.
     * 
     * @param string $nombre Nombre del grupo.
     * @return bool True si el grupo existe, false en caso contrario.
     */
    public function existsByName($nombre) {
        $stmt = $this->connection->prepare("SELECT id FROM grupos WHERE nombre = ?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Obtener inventarios de un grupo específico.
     * 
     * @param int $groupId ID del grupo.
     * @return array Lista de inventarios del grupo.
     */
    public function getInventoriesByGroup($groupId) {
        $stmt = $this->connection->prepare("
            SELECT 
                i.id AS id, 
                i.nombre AS nombre, 
                i.responsable AS responsable, 
                i.fecha_modificacion AS fecha_modificacion, 
                i.estado_conservacion AS estado_conservacion,
                COUNT(DISTINCT b.id) AS cantidad_tipos_bienes,
                COALESCE(SUM(bc.cantidad), 0) + COUNT(be.id) AS cantidad_total_bienes
            FROM inventarios i
            LEFT JOIN bienes_inventarios bi ON i.id = bi.inventario_id
            LEFT JOIN bienes b ON bi.bien_id = b.id
            LEFT JOIN bienes_cantidad bc ON bi.id = bc.bien_inventario_id
            LEFT JOIN bienes_equipos be ON bi.id = be.bien_inventario_id
            WHERE i.grupo_id = ?
            GROUP BY i.id, i.nombre, i.responsable, i.fecha_modificacion, i.estado_conservacion
        ");
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
