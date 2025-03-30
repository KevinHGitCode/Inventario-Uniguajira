<?php

require_once __DIR__ . '/../models/User.php';

/**
 * Clase ctlSidebar
 * Controlador para manejar las vistas de la barra lateral.
 */
class ctlSidebar {
    private $userModel;

    /**
     * Constructor de la clase ctlSidebar.
     * Inicializa el modelo de usuario.
     */
    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Método home.
     * Carga la vista principal y pasa el nombre del usuario al archivo de vista.
     */
    public function home() {
        $userData = $this->userModel->getById(1);
        $username = $userData['nombre'];
        // Pass $username to the view
        require __DIR__ . '/../views/home.php';
    }

    /**
     * Método goods.
     * Carga la vista de bienes.
     */
    public function goods() {
        // Logic for goods view
        require __DIR__ . '/../views/goods.php';

        echo "Hola desde ctlSidebar - metodo goods";
    }

    /**
     * Método inventary.
     * Carga la vista del inventario.
     */
    public function inventary() {
        // Logic for inventary view
        require __DIR__ . '/../views/inventary.php';

        echo "Hola desde ctlSidebar - metodo inventory";
    }

    /**
     * Método users.
     * Carga la vista de usuarios.
     */
    public function users() {
        // Logic for users view
        require __DIR__ . '/../views/users.php';

        echo "Hola desde ctlSidebar - metodo users";
    }
}