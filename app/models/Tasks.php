<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Tasks
 * 
 * Esta clase maneja las operaciones CRUD relacionadas con las tareas en la base de datos.
 * Extiende la clase Database para utilizar la conexión a la base de datos.
 */
class Tasks {
    protected $connection;

    /**
     * Constructor de la clase Goods.
     * Obtiene la conexión a la base de datos desde la instancia Singleton de Database.
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
    }

    /**
     * Crear una tarea.
     * 
     * @param string $name Nombre de la tarea.
     * @param string $description Descripción de la tarea.
     * @param int $usuario_id ID del usuario asociado a la tarea.
     * @param string $estado Estado inicial de la tarea ('por hacer' o 'completado').
     * @return int|false ID de la tarea creada si fue exitoso, False si hubo un error.
     */
    public function create($name, $description, $usuario_id) {
        // Validar que el nombre no esté vacío
        if (empty(trim($name))) {
            return false; // Retorna false si el nombre está vacío
        }

        $stmt = $this->connection->prepare("
            INSERT INTO tareas 
            (nombre, descripcion, usuario_id, fecha) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssi", $name, $description, $usuario_id);
        
        if ($stmt->execute()) {
            return $stmt->insert_id; // Retorna el ID de la nueva tarea
        }
        
        return false;
    }

    /**
     * Obtener todas las tareas de un usuario separadas por estado.
     * 
     * @param int $usuario_id ID del usuario cuyas tareas se quieren obtener.
     * @return array Array asociativo con las tareas del usuario.
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
     * Modificar una tarea con verificación de propiedad.
     * 
     * @param int $id ID de la tarea a modificar.
     * @param string $name Nuevo nombre de la tarea.
     * @param string $description Nueva descripción de la tarea.
     * @param int $usuario_id ID del usuario propietario de la tarea.
     * @return bool Devuelve true si la tarea se actualizó correctamente, false en caso contrario.
     */
    public function updateName($id, $name, $description, $usuario_id) {
        // Validar que el nombre no esté vacío
        if (empty(trim($name))) {
            return false; // Retorna false si el nombre está vacío
        }
        $stmt = $this->connection->prepare("UPDATE tareas SET nombre = ?, descripcion = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ssii", $name, $description, $id, $usuario_id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Eliminar una tarea con verificación de propiedad.
     * 
     * @param int $id ID de la tarea a eliminar.
     * @param int $usuario_id ID del usuario propietario de la tarea.
     * @return bool Devuelve true si la tarea se eliminó correctamente, false en caso contrario.
     */
    public function delete($id, $usuario_id) {
        // Verificar que el usuario sea administrador o dueño de la tarea
        $stmt = $this->connection->prepare("
            DELETE FROM tareas 
            WHERE id = ? 
            AND (usuario_id = ? OR ? IN (SELECT id FROM usuarios WHERE rol = 'administrador'))
        ");
        $stmt->bind_param("iii", $id, $usuario_id, $usuario_id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Cambiar el estado de una tarea.
     * 
     * Cambia el estado de la tarea entre 'por hacer' y 'completado'.
     * 
     * @param int $id ID de la tarea cuyo estado se desea cambiar.
     * @param int $usuario_id ID del usuario propietario de la tarea.
     * @return bool Devuelve true si el estado se cambió correctamente, false en caso contrario.
     */
    public function changeState($id, $usuario_id) {
        $stmt = $this->connection->prepare("
            UPDATE tareas 
            SET estado = CASE 
                WHEN estado = 'por hacer' THEN 'completado' 
                ELSE 'por hacer' 
            END 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->bind_param("ii", $id, $usuario_id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}
?>