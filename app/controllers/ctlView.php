<?php

/**
 * Clase ctlView
 * Controlador para manejar las vistas de la aplicación.
 */
class ctlView {

    /**
     * Muestra la vista principal (index).
     *
     * @return void
     */
    public function index() {
        include 'app/views/index.php';
    }

    /**
     * Muestra la vista de inicio de sesión (login).
     *
     * @return void
     */
    public function login() {
        include 'app/views/login.html';
    }

    /**
     * Muestra la vista de documentación (doc).
     *
     * @return void
     */
    public function doc() {
        include 'documentation/doc.php';
    }

    /**
     * Muestra la vista de error 404 (not found).
     *
     * @return void
     */
    public function notFound() {
        include 'app/views/errors/not-found.html';
    }

    public function test() {
        header('Location: tests/models/.init-tests.php');
        exit;        
    }

    /**
     * Muestra la vista de perfil de usuario (Profile).
     *
     * @return void
     */
    public function Profile() {
        //obtener datos de la sesión, incluyendo todos los atributos del usuario
        require_once __DIR__ . '/sessionCheck.php';

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user_name = $_SESSION['user_name'];
            $user_email = $_SESSION['user_email'];
            $user_rol = $_SESSION['user_rol'];
            // $user_img = $_SESSION['user_img']; // Uncomment if needed
            include 'app/views/editProfile.php';
        }
    }
}