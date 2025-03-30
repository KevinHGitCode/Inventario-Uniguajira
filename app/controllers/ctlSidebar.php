<?php
require_once 'app/controllers/sessionCheck.php';
require_once __DIR__ . '/../models/User.php';

/**
 * Controlador para manejar las vistas de la barra lateral.
 */
class ctlSidebar {
    /**
     * Modelo de usuario.
     *
     * @var User
     */
    private $userModel;

    /**
     * Constructor de la clase ctlSidebar.
     * Inicializa el modelo de usuario.
     */
    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Muestra la vista principal (home).
     * Obtiene los datos del usuario con ID 1 y los pasa a la vista.
     *
     * @return void
     */
    public function home() {
        $username = $_SESSION['user_name'];
        // Pass $username to the view
        require __DIR__ . '/../views/home.php';
    }

    /**
     * Muestra la vista de bienes (goods).
     *
     * @return void
     */
    public function goods() {
        // Logic for goods view
        require __DIR__ . '/../views/goods.php';

        echo "Hola desde ctlSidebar - metodo goods";
    }

    /**
     * Muestra la vista de inventario (inventary).
     *
     * @return void
     */
    public function inventary() {
        // Logic for inventary view
        require __DIR__ . '/../views/inventary.php';

        echo "Hola desde ctlSidebar - metodo inventory";
    }

    /**
     * Muestra la vista de usuarios (users).
     *
     * @return void
     */
    public function users() {
        // Logic for users view
        require __DIR__ . '/../views/users.php';

        echo "Hola desde ctlSidebar - metodo users";
    }
}