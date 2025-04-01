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
        $stmt = $this->connection->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE id = ?");
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
            $query = "SELECT id, nombre, email, rol FROM usuarios";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $usuarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            return $usuarios;
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
    public function createUser($nombre, $email, $contraseña, $rol) {
        try {
            $query = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT); // Encriptar la contraseña
            $stmt->bind_param("sssi", $nombre, $email, $hashedPassword, $rol);
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
    public function updateUserR($id, $nombre, $email, $contraseña, $rol) {
        try {
            $query = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ?, rol = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT); // Encriptar la contraseña
            $stmt->bind_param("sssii", $nombre, $email, $hashedPassword, $rol, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo ": <br>";
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
    public function updateUser($id, $nombre, $email, $contraseña) {
        try {
            $query = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT); // Encriptar la contraseña
            $stmt->bind_param("sssi", $nombre, $email, $hashedPassword, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo ": <br>";
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
    public function authentication($nombre, $contraseña) {
        try {
            $query = "SELECT id, nombre, email, contraseña, rol FROM usuarios WHERE nombre = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();

            if ($usuario) {
                if (password_verify($contraseña, $usuario['contraseña'])) {
                    return $usuario;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error al autenticar el usuario: " . $e->getMessage());
        }
    }
}
