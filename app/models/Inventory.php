<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Inventory
 * 
 * Esta clase maneja las operaciones relacionadas con los inventarios en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Inventory {
    protected $connection;

    /**
     * Constructor de la clase Inventory.
     * Obtiene la conexión a la base de datos desde la instancia Singleton de Database.
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
    }

    /**
     * Obtener información de un inventario por su ID.
     * 
     * @param int $id ID del inventario.
     * @return array|false Arreglo asociativo con la información del inventario o false si no existe.
     */
    public function getInventoryById($id) {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                nombre, 
                grupo_id, 
                fecha_modificacion, 
                estado_conservacion 
            FROM inventarios 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        return $result->fetch_assoc();
    }

    /**
     * Obtener todos los inventarios.
     * 
     * @return array Arreglo asociativo con todos los registros de la tabla 'inventarios'.
     */
    public function getAll() {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                nombre, 
                grupo_id, 
                fecha_modificacion, 
                estado_conservacion 
            FROM inventarios
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crear un nuevo inventario.
     * 
     * @param string $name Nombre del inventario.
     * @param int $grupoId ID del grupo al que pertenece el inventario.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function create($name, $grupoId) {
        // Validar el nombre
        if (empty($name) || strlen($name) > 100) {
            return false;
        }

        // Verificar si ya existe el nombre en el mismo grupo
        $checkStmt = $this->connection->prepare("SELECT id FROM inventarios WHERE nombre = ? AND grupo_id = ?");
        $checkStmt->bind_param("si", $name, $grupoId);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            return false;
        }

        $stmt = $this->connection->prepare("INSERT INTO inventarios (nombre, grupo_id, fecha_modificacion, estado_conservacion) VALUES (?, ?, NOW(), 1)");
        $stmt->bind_param("si", $name, $grupoId);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /** 
     * 
     * @param int $id ID del inventario.
     * @param string $name Nuevo nombre del inventario.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function updateName($id, $name) {
        // Validar el nombre
        if (empty($name) || strlen($name) > 255) {
            return false;
        }

        // Verificar que el inventario existe y obtener su grupo_id
        $checkStmt = $this->connection->prepare("SELECT grupo_id FROM inventarios WHERE id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }
        $grupoId = $result->fetch_assoc()['grupo_id'];

        // Verificar si ya existe el nombre en el mismo grupo (excluyendo el inventario actual)
        $duplicateStmt = $this->connection->prepare("SELECT id FROM inventarios WHERE nombre = ? AND grupo_id = ? AND id != ?");
        $duplicateStmt->bind_param("sii", $name, $grupoId, $id);
        $duplicateStmt->execute();
        if ($duplicateStmt->get_result()->num_rows > 0) {
            return false;
        }

        $stmt = $this->connection->prepare("UPDATE inventarios SET nombre = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Mover un inventario a otro grupo.
     * 
     * @param int $id ID del inventario.
     * @param int $grupoId Nuevo ID del grupo al que pertenece.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function updateGroup($id, $grupoId) {
        $stmt = $this->connection->prepare("UPDATE inventarios SET grupo_id = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $grupoId, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Return true if updated, false otherwise
    }

    /**
     * Actualizar el estado de conservación de un inventario.
     * 
     * @param int $id ID del inventario.
     * @param int $conservation Nuevo estado de conservación (1, 2 o 3).
     * @return bool True si la operación fue exitosa, false si el estado es inválido o no se actualizó.
     */
    public function updateConservation($id, $conservation) {
        $validConservations = [1, 2, 3];
        if (!in_array($conservation, $validConservations)) {
            return false; // Invalid conservation state
        }
        $stmt = $this->connection->prepare("UPDATE inventarios SET estado_conservacion = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $conservation, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Return true if updated, false otherwise
    }


    /**
     * Verificar si un inventario tiene bienes asociados.
     * 
     * @param int $id ID del inventario.
     * @return bool True si tiene bienes asociados, false en caso contrario.
     */
    public function hasAssociatedAssets($id) {
        $checkStmt = $this->connection->prepare("SELECT COUNT(*) FROM bienes_inventarios WHERE inventario_id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $count = $result->fetch_row()[0];
        return $count > 0;
    }

    /**
     * Eliminar un inventario.
     * 
     * Nota: Solo se puede eliminar si no tiene bienes asociados.
     * 
     * @param int $id ID del inventario.
     * @return bool True si la operación fue exitosa, false si tiene bienes asociados o no se eliminó.
     */
    public function delete($id) {
        // Verificar si hay bienes asociados
        if ($this->hasAssociatedAssets($id)) {
            return false; // No se puede eliminar si tiene bienes asociados
        }

        $stmt = $this->connection->prepare("DELETE FROM inventarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0; // Return true if deleted, false otherwise
    }

    /**
     * Actualizar el responsable de un inventario.
     * 
     * @param int $id ID del inventario.
     * @param string $responsable Nuevo responsable del inventario.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function updateResponsable($id, $responsable) {
        if (empty($responsable) || strlen($responsable) > 255) {
            return false; // Validar que el responsable no esté vacío y no exceda el límite de caracteres
        }

        $stmt = $this->connection->prepare("UPDATE inventarios SET responsable = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("si", $responsable, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Retorna true si se actualizó, false en caso contrario
    }
}

?>
