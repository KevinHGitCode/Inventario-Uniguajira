<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Inventory
 * 
 * Esta clase maneja las operaciones relacionadas con los inventarios en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Inventory extends Database {

    /**
     * Constructor de la clase Inventory.
     * Llama al constructor de la clase padre para inicializar la conexión a la base de datos.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtener todos los inventarios.
     * 
     * @return array Arreglo asociativo con todos los registros de la tabla 'inventarios'.
     */
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM inventarios");
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
        $stmt = $this->connection->prepare("INSERT INTO inventarios (nombre, grupo_id, fecha_modificacion, estado_conservacion) VALUES (?, ?, NOW(), 1)");
        $stmt->bind_param("si", $name, $grupoId);
        return $stmt->execute();
    }

    /**
     * Cambiar el nombre de un inventario.
     * 
     * @param int $id ID del inventario.
     * @param string $name Nuevo nombre del inventario.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function updateName($id, $name) {
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

        return $stmt->affected_rows > 0;
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
            return false; // Estado no válido
        }
        
        $stmt = $this->connection->prepare("UPDATE inventarios SET estado_conservacion = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $conservation, $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
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
        $checkStmt = $this->connection->prepare("SELECT COUNT(*) FROM bienes_inventarios WHERE inventario_id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $count = $result->fetch_row()[0];
        if ($count > 0) {
            return false; // No se puede eliminar si tiene bienes asociados
        }

        $stmt = $this->connection->prepare("DELETE FROM inventarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }
}

?>
