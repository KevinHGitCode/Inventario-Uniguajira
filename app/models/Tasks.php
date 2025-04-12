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

    // TODO: El estado al crear una tarea debería ser 'por hacer' por defecto.
    // TODO: Solo crear la tarea si el usuario es administrador.
    //
    public function create($name, $description, $usuario_id, $estado = 'por hacer') {
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
            throw new Exception('No tienes permisos para crear tareas');
        }
        
        $stmt = $this->connection->prepare("
            INSERT INTO tareas 
            (nombre, descripcion, usuario_id, estado, fecha) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssis", $name, $description, $usuario_id, $estado);
        return $stmt->execute();
    }

    /**
     * Obtener todas las tareas de un usuario separadas por estado
     * 
     * @param int $usuario_id ID del usuario cuyas tareas se quieren obtener
     * @return array Array asociativo con dos keys: 'por_hacer' y 'completadas'
     */
    public function getByUser($usuario_id) {
        $stmt = $this->connection->prepare("
            SELECT id, nombre, descripcion, fecha, estado 
            FROM tareas 
            WHERE usuario_id = ?
            ORDER BY 
            CASE WHEN estado = 'por hacer' THEN 0 ELSE 1 END, -- Ordena por hacer primero
            fecha DESC
        ");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Devuelve todas las tareas juntas
    }

    /**
     * Obtener la lista de todas las tareas.
     * 
     * @return array Devuelve un arreglo asociativo con las tareas (id, nombre, descripción, estado).
     */
    public function getAll() {

        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
            return []; // O podrías lanzar una excepción
        }

        // Obtener todas las tareas
        // Solo los administradores pueden ver todas las tareas
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                nombre, 
                descripcion, 
                estado 
            FROM tareas
        ");

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Modificar una tarea con verificación de propiedad.
     * 
     * @param int $id ID de la tarea a modificar.
     * @param string $name Nuevo nombre de la tarea.
     * @param string $description Nueva descripción de la tarea.
     * @return bool Devuelve true si la tarea se actualizó correctamente, false en caso contrario.
     */
    public function updateName($id, $name, $description, $usuario_id) {
        $stmt = $this->connection->prepare("UPDATE tareas SET nombre = ?, descripcion = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ssii", $name, $description, $id, $usuario_id);
        return $stmt->execute();
    }

    /**
     * Eliminar una tarea con verificación de propiedad.
     * 
     * @param int $id ID de la tarea a eliminar.
     * @return bool Devuelve true si la tarea se eliminó correctamente, false en caso contrario.
     */
    public function delete($id, $usuario_id) {
        $stmt = $this->connection->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuario_id);
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
    public function changeState($id, $usuario_id) {
        $stmt = $this->connection->prepare("UPDATE tareas SET estado = IF(estado = 'por hacer', 
                                            'completado', 'por hacer') WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuario_id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}

?>