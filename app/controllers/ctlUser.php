<?php
require_once __DIR__ . '/../models/User.php';

class ctlUser {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Método para iniciar sesión.
     * Obtiene los parámetros desde $_POST.
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
     * Obtiene los parámetros desde $_POST.
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
     * Obtiene el ID desde $_GET.
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
     * Obtiene los parámetros desde $_POST.
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
