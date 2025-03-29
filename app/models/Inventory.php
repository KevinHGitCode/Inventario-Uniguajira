<?php
require_once __DIR__ . '/../config/db.php';

class Inventory extends Database {

    public function __construct() {
        parent::__construct();
    }

    // Obtener todos los inventarios
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM inventarios");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Crear inventario
    public function create($name, $grupoId) {
        $stmt = $this->connection->prepare("INSERT INTO inventarios (nombre, grupo_id, fecha_modificacion, estado_conservacion) VALUES (?, ?, NOW(), 1)");
        $stmt->bind_param("si", $name, $grupoId);
        return $stmt->execute();
    }

    // Cambiar estado de conservación
    public function updateConservation($id, $conservation) {
        $validConservations = [1, 2, 3];
        if (!in_array($conservation, $validConservations)) {
            return false; // Estado no válido
        }
        
        $stmt = $this->connection->prepare("UPDATE inventarios SET estado_conservacion = ? WHERE id = ?");
        $stmt->bind_param("ii", $conservation, $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    // Editar inventario
    public function update($id, $name, $grupoId, $state_conservation) {
        $stmt = $this->connection->prepare("UPDATE inventarios SET nombre = ?, estado_conservacion = ?, grupo_id = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("siii", $name, $state_conservation, $grupoId, $id);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    // Eliminar inventario (solo si no tiene bienes asociados)
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
