<?php
require_once __DIR__ . '/../config/db.php';

class Groups {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    // Obtener todos los grupos
    public function getAllGroups() {
        $stmt = $this->connection->prepare("SELECT * FROM grupos");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener inventarios de un grupo específico
    public function getInventoriesByGroup($groupId) {
        $stmt = $this->connection->prepare("SELECT * FROM inventarios WHERE grupo_id = ?");
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Crear un nuevo grupo
    public function createGroup($name) {
        // Verificar si el nombre ya existe
        $checkStmt = $this->connection->prepare("SELECT id FROM grupos WHERE nombre = ?");
        $checkStmt->bind_param("s", $name);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
    
        if ($result->num_rows > 0) {
            return false; // El nombre ya existe
        }
    
        // Si no existe, insertamos el nuevo grupo
        $stmt = $this->connection->prepare("INSERT INTO grupos (nombre) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }
    

    // Editar un grupo (solo cambiar el nombre)
    public function updateGroup($id, $newName) {
        // Primero, verificamos si el grupo existe
        $checkStmt = $this->connection->prepare("SELECT nombre FROM grupos WHERE id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
    
        if ($result->num_rows === 0) {
            return false; // El grupo no existe
        }
    
        $row = $result->fetch_assoc();
        if ($row['nombre'] === $newName) {
            return false; // El nombre es el mismo, no hay cambios
        }
    
        // Ahora sí, realizamos la actualización
        $stmt = $this->connection->prepare("UPDATE grupos SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }
    

    // Eliminar un grupo si no tiene inventarios asociados
    public function deleteGroup($id) {

        // Verificar si el grupo existe antes de eliminar
        $checkGroupStmt = $this->connection->prepare("SELECT id FROM grupos WHERE id = ?");
        $checkGroupStmt->bind_param("i", $id);
        $checkGroupStmt->execute();
        $groupResult = $checkGroupStmt->get_result();
    
        if ($groupResult->num_rows === 0) {
            return false; // El grupo no existe
        }

        // Verificar si el grupo tiene inventarios asociados
        $checkStmt = $this->connection->prepare("SELECT id FROM inventarios WHERE grupo_id = ?");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
    
        if ($result->num_rows > 0) {
            return false; // No se puede eliminar porque tiene inventarios asociados
        }
    
        // Intentar eliminar el grupo
        $stmt = $this->connection->prepare("DELETE FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0; // Solo retorna true si eliminó algo
    }
    
}
?>
