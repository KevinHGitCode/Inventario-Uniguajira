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
     * @throws Exception Si ocurre un error al obtener los usuarios.
     */
    public function getAllUsers() {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Error al obtener los usuarios: " . $e->getMessage());
        }
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param string $nombre Nombre del usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $contraseña Contraseña del usuario.
     * @param int $rol Rol del usuario.
     * @return string Mensaje de éxito.
     * @throws Exception Si ocurre un error al crear el usuario.
     */
    public function createUser($nombre, $nombre_usuario, $email, $contraseña, $rol, $foto_perfil = null) {
        try {
            $query = "INSERT INTO usuarios (nombre, nombre_usuario,  email, contraseña, rol, foto_perfil) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT); // Encriptar la contraseña
            $stmt->bind_param("ssssis", $nombre, $nombre_usuario, $email, $hashedPassword, $rol, $foto_perfil);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return "Usuario creado exitosamente.";
            } else {
                throw new Exception("No se pudo crear el usuario.");
            }
        } catch (Exception $e) {
            throw new Exception("Error al crear el usuario nuevo: " . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de un usuario, incluyendo su rol.
     *
     * @param int $id ID del usuario.
     * @param string $nombre Nombre del usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $contraseña Contraseña del usuario.
     * @param int $rol Rol del usuario.
     * @return string Mensaje de éxito o de que no hubo cambios.
     * @throws Exception Si ocurre un error al actualizar el usuario.
     */
    public function updateUserRol($id, $nombre, $email, $contraseña, $rol) {
        try {
            $query = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ?, rol = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT); // Encriptar la contraseña
            $stmt->bind_param("sssii", $nombre, $email, $hashedPassword, $rol, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return "Usuario actualizado exitosamente.";
            } else {
                return "No se realizaron cambios en los datos del usuario.";
            }
        } catch (Exception $e) {
            throw new Exception("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de un usuario sin modificar su rol.
     *
     * @param int $id ID del usuario.
     * @param string $nombre Nombre del usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $contraseña Contraseña del usuario.
     * @return string Mensaje de éxito o de que no hubo cambios.
     * @throws Exception Si ocurre un error al actualizar el usuario.
     */
    public function updateUser($id, $nombre, $email) {
        try {
            $query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssi", $nombre, $email, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return "Usuario actualizado exitosamente.";
            } else {
                return "No se realizaron cambios en los datos del usuario.";
            }
        } catch (Exception $e) {
            throw new Exception("Error al actualizar el usuario: " . $e->getMessage());
        }
    }

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param int $id ID del usuario.
     * @param string $contraseña Nueva contraseña del usuario.
     * @return string Mensaje de éxito o de que no hubo cambios.
     * @throws Exception Si ocurre un error al actualizar la contraseña.
     */
    public function updatepassword($id, $contraseña) {
        try {
            $query = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);
            $stmt->bind_param("si", $hashedPassword, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return "contraseña actualizada";
            } else {
                return "la contraseña no actualizo";
            }
        } catch (Exception $e) {
            throw new Exception("error para actualizar contraseña:" . $e->getMessage());
        }
    }

    // TODO: Problema, el ultimo acceso no se guarda como la hora real
    // explicaion: la fecha se guarda pero la hora es diferente
    public function updateUltimoAcceso($id) {
        try {
            $query = "UPDATE usuarios SET fecha_ultimo_acceso = NOW() WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al actualizar el último acceso: " . $e->getMessage());
        }
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return string Mensaje de éxito o de que no se encontró el usuario.
     * @throws Exception Si ocurre un error al eliminar el usuario o si se intenta eliminar el usuario con ID 1.
     */
    public function deleteUser($id) {
        try {
            if ($id == 1) {
                throw new Exception("El usuario con ID 1 no puede ser eliminado.");
            }

            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return "Usuario eliminado exitosamente.";
            } else {
                return "No se encontró un usuario con el ID proporcionado.";
            }
        } catch (Exception $e) {
            throw new Exception("Error al eliminar el usuario: " . $e->getMessage());
        }
    }

    /**
     * Autentica a un usuario por su nombre y contraseña.
     *
     * @param string $nombre Nombre del usuario.
     * @param string $contraseña Contraseña del usuario.
     * @return array|false Datos del usuario si la autenticación es exitosa, false en caso contrario.
     * @throws Exception Si ocurre un error al autenticar al usuario.
     */
    // TODO: Actualizar el metodo, a autenticacion debe ser con nombre de usuario o email
    // TODO: No debe regresar la contraseña
    // TODO: Debe distinguir entre mayusculas y minusculas
    public function authentication($identificador, $contraseña) {
        try {
            // Consulta para buscar al usuario por nombre (sensible a mayúsculas y minúsculas)
            $query = "SELECT id, nombre, email, 
                rol, fecha_creacion, fecha_ultimo_acceso, foto_perfil,
                 contraseña FROM usuarios WHERE BINARY nombre_usuario = ? OR BINARY email = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ss", $identificador, $identificador);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
    
            if ($usuario) {
                // Verificar si la contraseña coincide
                if (password_verify($contraseña, $usuario['contraseña'])) {
                    // Eliminar la contraseña antes de devolver los datos
                    unset($usuario['contraseña']);
                    return $usuario; // Devuelve los datos del usuario si las credenciales coinciden
                } else {
                    return false; // Contraseña incorrecta
                }
            } else {
                return false; // Usuario no encontrado
            }
        } catch (Exception $e) {
            throw new Exception("Error al autenticar el usuario: " . $e->getMessage());
        }
    }
    
    public function updateUserImage($id, $ruta){
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $ruta, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    
}
?>
