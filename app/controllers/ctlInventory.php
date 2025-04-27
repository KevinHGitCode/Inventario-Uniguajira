<?php
require_once __DIR__ . '/../models/User.php';
require_once 'app/helpers/imageHelper.php';
require_once 'app/helpers/validate-http.php';


/**
 * Controlador para la gestión de usuarios.
 * 
 * Este controlador incluye métodos para manejar las operaciones relacionadas con los usuarios,
 * como iniciar sesión, registrar nuevos usuarios, obtener perfiles y editar información.
 */
class ctlUser {
    private $userModel;

    /**
     * Constructor de la clase.
     * Inicializa el modelo de usuario.
     */
    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Método para iniciar sesión.
     * Este método valida las credenciales del usuario y crea una sesión si son correctas.
     */
    public function login() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['username', 'password'])) {
            return;
        }
    
        // Obtener parámetros desde $_POST
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Autenticar usuario
        $dataUser = $this->userModel->authentication($username, $password);
        
        if (!$dataUser) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
            return;
        }

        // Iniciar sesión y establecer datos de usuario
        session_start();
        // Establecer la zona horaria antes
        $_SESSION['timezone_offset'] = $_POST['timezone_offset'] ?? -5; // UTC-5 para Colombia
        $_SESSION['timezone_name'] = $_POST['timezone_name'] ?? 'America/Bogota';
        $this->userModel->setTimezone();
        $_SESSION['user_id'] = $dataUser['id'];
        $_SESSION['user_name'] = $dataUser['nombre'];
        $_SESSION['user_username'] = $dataUser['nombre_usuario'];
        $_SESSION['user_email'] = $dataUser['email'];
        $_SESSION['user_rol'] = $dataUser['rol'];
        $_SESSION['user_img'] = $dataUser['foto_perfil'];
        
        // Actualizar último acceso
        $this->userModel->updateUltimoAcceso($dataUser['id']);

        // Retornar éxito
        echo json_encode([
            'success' => true, 
            'message' => 'Inicio de sesión exitoso',
            'user' => [
                'id' => $dataUser['id'],
                'nombre' => $dataUser['nombre'],
                'rol' => $dataUser['rol']
            ]
        ]);
    }

    /**
     * Método para registrar un nuevo usuario.
     */
    public function register() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['nombre', 'email', 'contraseña', 'rol'])) {
            return;
        }

        // Obtener parámetros desde $_POST
        $nombre = $_POST['nombre'];
        $nombre_usuario = $_POST['nombre_usuario'] ?? '';
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];
        $rol = (int) $_POST['rol'];

        // Crear el nuevo usuario
        $userId = $this->userModel->createUser($nombre, $nombre_usuario, $email, $contraseña, $rol);
        
        if ($userId) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente.', 'userId' => $userId]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
        }
    }

    /**
     * Método para editar el perfil de un usuario.
     */
    public function editProfile() {
        session_start();
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['nombre', 'nombre_usuario', 'email'])) {
            return;
        }

        $id = (int) $_SESSION['user_id'] ?? 0;
        $nombre = trim($_POST['nombre']);
        $nombre_usuario = trim($_POST['nombre_usuario']);
        $email = trim($_POST['email']);
        $imagen = $_FILES['imagen'] ?? null;

        // Obtener usuario actual
        $usuarioActual = $this->userModel->getById($id);

        if (!$usuarioActual) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            return;
        }

        // Procesar imagen si se subió una nueva
        $nuevaRuta = null;
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $resultadoImagen = validarYGuardarImagen($imagen, 'assets/uploads/img/users/', 10); // 10MB para usuarios

            if (!$resultadoImagen['success']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $resultadoImagen['message']]);
                return;
            }

            // Eliminar imagen anterior si existe
            if (!empty($usuarioActual['foto_perfil']) && file_exists($usuarioActual['foto_perfil'])) {
                unlink($usuarioActual['foto_perfil']);
            }

            $nuevaRuta = $resultadoImagen['path'];

            // Actualizar solo imagen
            $this->userModel->updateUserImage($id, $nuevaRuta);
            $_SESSION['user_img'] = $nuevaRuta;
        }

        // Actualizar nombre, nombre de usuario y correo
        $resultado = $this->userModel->updateUser($id, $nombre, $nombre_usuario, $email);
        
        if ($resultado || $nuevaRuta) {
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_username'] = $nombre_usuario;
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar los datos.']);
        }
    }

    /**
     * Método para editar un usuario (para admin).
     */
    public function edit() {
        header('Content-Type: application/json');
    
        if (!validateHttpRequest('POST', ['id', 'nombre', 'nombre_usuario', 'email'])) {
            return;
        }
    
        $id = (int) $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $nombre_usuario = trim($_POST['nombre_usuario']);
        $email = trim($_POST['email']);
    
        // Obtener usuario actual
        $usuarioActual = $this->userModel->getById($id);
    
        if (!$usuarioActual) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            return;
        }
    
        // Actualizar datos
        $resultado = $this->userModel->updateUser($id, $nombre, $nombre_usuario, $email);
    
        if ($resultado) {
            // Actualizar la sesión si es el usuario actual
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                $_SESSION['user_name'] = $nombre;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_username'] = $nombre_usuario;
            }
            
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar los datos.']);
        }
    }
    
    /**
     * Método para cerrar sesión.
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login");
        exit();
    }

    /**
     * Método para crear un nuevo usuario (para admin).
     */
    public function create() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['nombre', 'nombre_usuario', 'email', 'contraseña', 'rol'])) {
            return;
        }

        // Obtener parámetros desde $_POST
        $nombre = $_POST['nombre'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];
        $rol = (int) $_POST['rol'];

        // Crear el nuevo usuario
        $userId = $this->userModel->createUser($nombre, $nombre_usuario, $email, $contraseña, $rol);
        
        if ($userId) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.', 'userId' => $userId]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
        }
    }

    /**
     * Método para actualizar la contraseña de un usuario.
     */
    public function updatePassword() {
        session_start();
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['contraseña', 'nueva_contraseña', 'confirmar_contraseña'])) {
            return;
        }

        $id = (int) $_SESSION['user_id'] ?? 0;
        $password = trim($_POST['contraseña']);
        $newPassword = trim($_POST['nueva_contraseña']);
        $newPasswordConfirm = trim($_POST['confirmar_contraseña']);

        if ($newPassword !== $newPasswordConfirm) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            return;
        }

        $usuarioActual = $this->userModel->getById($id);

        if (!$usuarioActual) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            return;
        }

        // Verifica la contraseña
        if (!password_verify($password, $usuarioActual['contraseña'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
            return;
        }

        // Actualizar contraseña
        $resultado = $this->userModel->updatePassword($id, $newPassword);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la contraseña.']);
        }
    }

    /**
     * Método para eliminar un usuario.
     */
    public function deleteUser($id) {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('DELETE')) {
            return;
        }
        
        // Validar que tengamos un ID válido
        $id = (int)$id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
            return;
        }
        
        // Llamar al modelo para eliminar el usuario
        $resultado = $this->userModel->deleteUser($id);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario']);
        }
    }
}