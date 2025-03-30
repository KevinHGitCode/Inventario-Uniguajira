<?php
require_once __DIR__ . '/../models/User.php';

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
        // Validar si existen los parámetros necesarios
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            echo "Error: Parámetros faltantes para iniciar sesión.";
            return;
        }

        // Obtener parámetros desde $_POST
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Autenticar usuario
        $dataUser = $this->userModel->authentication($username, $password);
        if ($dataUser) {
            session_start();
            $_SESSION['user_id'] = $dataUser['id'];
            $_SESSION['user_name'] = $dataUser['nombre'];
            $_SESSION['user_email'] = $dataUser['email'];
            $_SESSION['user_rol'] = $dataUser['rol'];
            // $_SESSION['user_img'] = $dataUser['img'];

            header("Location: /");
        } else {
            echo "Error: Credenciales incorrectas.";
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
    public function edit() {
        // Validar si existen los parámetros necesarios
        if (!isset($_POST['id']) || !isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['contraseña']) || !isset($_POST['rol'])) {
            echo "Error: Parámetros faltantes para editar un usuario.";
            return;
        }

        // Obtener parámetros desde $_POST
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];
        $rol = $_POST['rol'];

        // Validar y procesar los datos
        echo "Método de edición llamado para el ID: $id con nombre: $nombre, correo: $email";
    }
}
