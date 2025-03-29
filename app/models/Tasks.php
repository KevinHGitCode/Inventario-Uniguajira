<?php
require_once __DIR__ . '/../config/db.php';

class Tasks extends Database {

    public function __construct() {
        parent::__construct();
    }

    // Crear una tarea
    public function create($name, $description, $usuario_id, $estado) {
        $stmt = $this->connection->prepare("INSERT INTO tareas (nombre, descripcion, usuario_id, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $description, $usuario_id, $estado);
        return $stmt->execute();
    }

    // Obtener la lista de todas las tareas (nombre, descripción, estado)
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT id, nombre, descripcion, estado FROM tareas");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Modificar una tarea (id, nombre, descripción)
    public function updateName($id, $name, $description) {
        $stmt = $this->connection->prepare("UPDATE tareas SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    // Eliminar una tarea
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Cambiar el estado de una tarea
    public function changeState($id){
        // En la base de datos el campo estado es un "ENUM('por hacer','completado')"
        $stmt = $this->connection->prepare("UPDATE tareas SET estado = IF(estado = 'por hacer', 'completado', 
        'por hacer') WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}

?>