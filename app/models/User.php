<?php

class User {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
       
    }

    public function getById($id) {
        // TODO: Cambiar * por los campos que se necesitan
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // TODO: Agregar más métodos

    // metodo de para obtener todos los usuarios

    public function getAllUsers(){
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

      
    //metodo de crear un nuevo usuario

    public function createUser($nombre, $email, $contraseña, $rol) {
        try {
            $query = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
           // $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT); // Encriptar la contraseña
            $stmt->bind_param("sssi", $nombre, $email, $contraseña, $rol);
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

    //metodo editar datos de un usuario

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
    

    //metodo cambiar contraseña

     public function updatepassword($id, $contraseña){
        try{
            $query = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);
            $stmt -> bind_param("si", $hashedPassword, $id);
            $stmt -> execute();

            if($stmt->affected_rows > 0){
                echo ": <br>";
                return "contraseña actualizada ";

            }else{
                return "la contraseña no actualizo";
            }
        }catch(Exception $e){
            throw new Exception("error para actualizar contraseña:" . $e->get_mensaje());
        }
    }

    //metodo eliminar usuario
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

    // metodo autenticar usuario

    public function authentication($nombre, $contraseña){
       try {
              $query = "SELECT id, nombre, email, contraseña, rol FROM usuarios WHERE nombre = ?";
              $stmt = $this->connection->prepare($query);
              $stmt->bind_param("s", $nombre);
              $stmt->execute();
              $result = $stmt->get_result();
              $usuario = $result->fetch_assoc();
    
              if($usuario){
                if(password_verify($contraseña, $usuario['contraseña'])){
                     return true;
                }else{
                     return false;
                }
              }else{
                return false;
              }
       } catch (Exception $e) {
              throw new Exception("Error al autenticar el usuario: " . $e->getMessage());        
       }
    }

}
