<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Tasks
 * 
 * Esta clase maneja las operaciones CRUD relacionadas con las tareas en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Tasks extends Database {

    /**
     * Constructor de la clase Tasks.
     * Llama al constructor de la clase padre para inicializar la conexión a la base de datos.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Crear una tarea.
     * 
     * @param string $name Nombre de la tarea.
     * @param string $description Descripción de la tarea.
     * @param int $usuario_id ID del usuario asociado a la tarea.
     * @param string $estado Estado inicial de la tarea ('por hacer' o 'completado').
     * @return bool Devuelve true si la tarea se creó correctamente, false en caso contrario.
     */
    public function create($name, $description, $usuario_id, $estado) {
        $stmt = $this->connection->prepare("INSERT INTO tareas (nombre, descripcion, usuario_id, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $description, $usuario_id, $estado);
        return $stmt->execute();
    }

    /**
     * Obtener la lista de todas las tareas.
     * 
     * @return array Devuelve un arreglo asociativo con las tareas (id, nombre, descripción, estado).
     */
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT id, nombre, descripcion, estado FROM tareas");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Modificar una tarea.
     * 
     * @param int $id ID de la tarea a modificar.
     * @param string $name Nuevo nombre de la tarea.
     * @param string $description Nueva descripción de la tarea.
     * @return bool Devuelve true si la tarea se actualizó correctamente, false en caso contrario.
     */
    public function updateName($id, $name, $description) {
        $stmt = $this->connection->prepare("UPDATE tareas SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    /**
     * Eliminar una tarea.
     * 
     * @param int $id ID de la tarea a eliminar.
     * @return bool Devuelve true si la tarea se eliminó correctamente, false en caso contrario.
     */
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Cambiar el estado de una tarea.
     * 
     * Cambia el estado de la tarea entre 'por hacer' y 'completado'.
     * 
     * @param int $id ID de la tarea cuyo estado se desea cambiar.
     * @return bool Devuelve true si el estado se cambió correctamente, false en caso contrario.
     */
    public function changeState($id) {
        $stmt = $this->connection->prepare("UPDATE tareas SET estado = IF(estado = 'por hacer', 'completado', 
        'por hacer') WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}

?>