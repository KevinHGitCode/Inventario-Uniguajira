<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase User
 * Maneja las operaciones relacionadas con los usuarios en la base de datos.
 */
class User extends Database {

    /**
     * Constructor de la clase User.
     * Inicializa la conexión a la base de datos.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtiene un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array|null Datos del usuario o null si no se encuentra.
     */
    public function getById($id) {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                nombre, 
                nombre_usuario, 
                email,
                contraseña,
                rol, 
                fecha_creacion, 
                fecha_ultimo_acceso, 
                foto_perfil 
            FROM usuarios 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Obtiene todos los usuarios.
     *
     * @return array Lista de usuarios.
     */
    public function getAllUsers() {
        $stmt = $this->connection->prepare("
            SELECT 
                id, 
                nombre, 
                nombre_usuario, 
                email, 
                rol, 
                fecha_creacion, 
                fecha_ultimo_acceso, 
                foto_perfil 
            FROM usuarios
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param string $nombre Nombre del usuario.
     * @param string $nombre_usuario Nombre de usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $contraseña Contraseña del usuario.
     * @param int $rol Rol del usuario.
     * @param string|null $foto_perfil Ruta de la foto de perfil.
     * @return int|false ID del usuario creado o false si falla.
     */
    public function createUser($nombre, $nombre_usuario, $email, $contraseña, $rol, $foto_perfil = null) {
        $query = "INSERT INTO usuarios (nombre, nombre_usuario, email, contraseña, rol, foto_perfil) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);
        $stmt->bind_param("ssssis", $nombre, $nombre_usuario, $email, $hashedPassword, $rol, $foto_perfil);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return $stmt->insert_id;
        }
        
        return false;
    }

    /**
     * Actualiza los datos de un usuario, incluyendo su rol.
     *
     * @param int $id ID del usuario.
     * @param string $nombre Nombre del usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $contraseña Contraseña del usuario.
     * @param int $rol Rol del usuario.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateUserRol($id, $nombre, $email, $contraseña, $rol) {
        $query = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ?, rol = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);
        $stmt->bind_param("sssii", $nombre, $email, $hashedPassword, $rol, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Actualiza los datos básicos de un usuario sin modificar su rol.
     *
     * @param int $id ID del usuario.
     * @param string $nombre Nombre del usuario.
     * @param string $nombre_usuario Nombre de usuario.
     * @param string $email Correo electrónico del usuario.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateUser($id, $nombre, $nombre_usuario, $email) {
        $query = "UPDATE usuarios SET nombre = ?, nombre_usuario = ?, email = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssi", $nombre, $nombre_usuario, $email, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param int $id ID del usuario.
     * @param string $contraseña Nueva contraseña del usuario.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updatePassword($id, $contraseña) {
        $query = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);
        $stmt->bind_param("si", $hashedPassword, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Actualiza la fecha del último acceso de un usuario.
     *
     * @param int $id ID del usuario.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateUltimoAcceso($id) {
        $query = "UPDATE usuarios SET fecha_ultimo_acceso = NOW() WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id ID del usuario a eliminar.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     */
    public function deleteUser($id) {
        // Protección para el usuario con ID 1
        if ($id == 1) {
            return false;
        }
        
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Autentica a un usuario por su identificador (nombre de usuario o email) y contraseña.
     *
     * @param string $identificador Nombre de usuario o email.
     * @param string $contraseña Contraseña del usuario.
     * @return array|false Datos del usuario si la autenticación es exitosa, false en caso contrario.
     */
    public function authentication($identificador, $contraseña) {
        $query = "SELECT id, nombre, nombre_usuario, email, 
            rol, fecha_creacion, fecha_ultimo_acceso, foto_perfil,
            contraseña FROM usuarios WHERE BINARY nombre_usuario = ? OR BINARY email = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $identificador, $identificador);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            unset($usuario['contraseña']);
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Actualiza la imagen de perfil de un usuario.
     *
     * @param int $id ID del usuario.
     * @param string $ruta Ruta de la imagen de perfil.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateUserImage($id, $ruta) {
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $ruta, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
?>