<?php
require_once __DIR__ . '/../models/User.php';
require_once 'app/helpers/imageHelper.php';


/**
 * Controlador para la gestión de usuarios.
 * 
 * Este controlador incluye métodos para manejar las operaciones relacionadas con los usuarios,
 * como iniciar sesión, registrar nuevos usuarios, obtener perfiles y editar información.
 * 
 * Instrucciones para el desarrollo de cada método:
 * - Validar los parámetros de entrada.
 * - Interactuar con el modelo `User` para realizar las operaciones necesarias.
 * - Manejar las respuestas adecuadas (redirecciones, mensajes de error, etc.).
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
     * 
     * Este método valida las credenciales del usuario y crea una sesión si son correctas.
     * 
     * @TODO: Implementar validaciones adicionales y manejo de errores más robusto.
     */
    public function login() {
        try {
            // Validar si existen los parámetros necesarios
            if (!isset($_POST['username']) || !isset($_POST['password'])) {
                throw new InvalidArgumentException('Parámetros faltantes para iniciar sesión');
            }
    
            // Obtener parámetros desde $_POST
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Autenticar usuario
            $dataUser = $this->userModel->authentication($username, $password);
            if (!$dataUser) {
                throw new RuntimeException('Credenciales incorrectas');
            }
    
            // Iniciar sesión y establecer datos de usuario
            session_start();
            $_SESSION['user_id'] = $dataUser['id'];
            $_SESSION['user_name'] = $dataUser['nombre'];
            $_SESSION['user_username'] = $dataUser['nombre_usuario'];
            $_SESSION['user_email'] = $dataUser['email'];
            $_SESSION['user_rol'] = $dataUser['rol'];
            $_SESSION['user_img'] = $dataUser['foto_perfil'];
            $this->userModel->updateUltimoAcceso($dataUser['id']);
    
            // Retornar éxito sin redireccionar
            // return ['success' => true, 'user' => $dataUser];
    
        } catch (Exception $e) {
            // Lanzar la excepción para que sea manejada por el manejador de errores
            throw $e;
        }
    }

    /**
     * Método para registrar un nuevo usuario.
     * 
     * Este método valida los datos de entrada y registra un nuevo usuario en el sistema.
     * 
     * @TODO: Implementar la lógica para guardar el usuario en la base de datos.
     */
    public function register() {
        // Validar si existen los parámetros necesarios
        if (!isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['contraseña']) || !isset($_POST['rol'])) {
            echo "Error: Parámetros faltantes para registrar un usuario.";
            return;
        }

        // Obtener parámetros desde $_POST
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];
        $rol = $_POST['rol'];

        // Validar y procesar los datos
        echo "Método de registro llamado con nombre: $nombre, correo: $email";
    }

    /**
     * Método para obtener el perfil de un usuario.
     * 
     * Este método recupera la información del perfil de un usuario específico.
     * 
     * @TODO: Implementar la lógica para obtener los datos del usuario desde el modelo.
     */
    public function profile() {
        // Validar si existe el parámetro necesario
        if (!isset($_GET['id'])) {
            echo "Error: Parámetro faltante para obtener el perfil.";
            return;
        }

        // Obtener parámetros desde $_GET
        $id = $_GET['id'];

        // Validar y procesar los datos
        echo "Método de perfil llamado para el ID: $id";
    }

    /**
     * Método para editar la información de un usuario.
     * 
     * Este método actualiza los datos de un usuario existente en el sistema.
     * 
     * @TODO: Implementar la lógica para actualizar los datos en la base de datos.
     */
    public function editProfile() {
        session_start();
        header('Content-Type: application/json');

        $id = (int) $_SESSION['user_id'] ?? 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $imagen = $_FILES['imagen'] ?? null;

        if (!is_numeric($id) || $nombre === '' || $nombre_usuario === '' ||   $email === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos.']);
            return;
        }

        $userModel = new User();
        $usuarioActual = $userModel->getById($id);

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

            // Actualizar solo imagen (si tienes un método dedicado)
            $userModel->updateUserImage($id, $nuevaRuta);
            $_SESSION['user_img'] = $nuevaRuta;
        }

        // Actualizar nombre y correo
        $resultado = $userModel->updateUser($id, $nombre, $nombre_usuario, $email);
        
        if ($resultado) {
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_username'] = $nombre_usuario;
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar los datos.']);
        }
    }

    public function edit() {
        session_start();
        header('Content-Type: application/json');
    
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $email = trim($_POST['email'] ?? '');
    
        // Validaciones básicas
        if ($id <= 0 || $nombre === '' || $nombre_usuario === '' ||  $email === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Todos los campos (id, nombre, email) son requeridos.'
            ]);
            return;
        }
    
        // Obtener usuario actual
        $userModel = new User();
        $usuarioActual = $userModel->getById($id);
    
        if (!$usuarioActual) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ]);
            return;
        }
    
        // Actualizar nombre y correo
        $resultado = $userModel->updateUser($id, $nombre, $nombre_usuario, $email);
    
        if ($resultado) {
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
    
            echo json_encode([
                'success' => true,
                'message' => 'Usuario actualizado correctamente.'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo actualizar los datos.'
            ]);
        }
    }
    

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function create(){
        header('Content-Type: application/json');
        
        if (!isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['contraseña']) || !isset($_POST['rol'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Error: Parámetros faltantes para crear un usuario."]);

            return;
        }

        // Obtener parámetros desde $_POST
        $nombre = $_POST['nombre'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];
        $rol =(int) $_POST['rol'];


        // Crear el nuevo usuario
        $result = $this->userModel->createUser($nombre, $nombre_usuario, $email, $contraseña, $rol );
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
        }
    }

    public function updatePassword() {
        session_start();
        header('Content-Type: application/json');

        $id = (int) $_SESSION['user_id'] ?? 0;
        $password = trim($_POST['contraseña'] ?? '');
        $newPassword = trim($_POST['nueva_contraseña'] ?? '');
        $newPasswordConfirm = trim($_POST['confirmar_contraseña'] ?? '');

        if (!is_numeric($id) || $password === '' || $newPassword === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos.']);
            return;
        }

        if ($newPassword !== $newPasswordConfirm) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            return;
        }

        $userModel = new User();
        $usuarioActual = $userModel->getById($id);

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
        $resultado = $userModel->updatepassword($id, $newPassword);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la contraseña.']);
        }
    }

    public function deleteUser() {
        header('Content-Type: application/json');
    
        // Obtener el ID del usuario desde los parámetros de la solicitud
        $id = (int) ($_POST['id'] ?? 0);
        
    
        // Verificar si el ID es válido
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de usuario inválido.']);
            return;
        }
    
        // Condición: No permitir eliminar al usuario con ID = 1
        if ($id === 1) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'El usuario con ID 1 no puede ser eliminado.']);
            return;
        }
    
        // Llamar al modelo para eliminar el usuario
        $resultado = $this->userModel->deleteUser($id);
    
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario.']);
        }
    }
}
