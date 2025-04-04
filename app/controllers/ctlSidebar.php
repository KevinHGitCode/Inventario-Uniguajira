<?php
require_once 'app/controllers/sessionCheck.php';

/**
 * Controlador para manejar las vistas de la barra lateral.
 */
class ctlSidebar {
    /**
     * Constructor de la clase ctlSidebar.
     */
    public function __construct() {
        // Constructor vacío, los modelos se cargarán en los métodos correspondientes.
    }

    /**
     * Muestra la vista principal (home).
     * Obtiene los datos del usuario con ID 1 y los pasa a la vista.
     *
     * @return void
     */
    public function home() {
        require_once __DIR__ . '/../models/Tasks.php';
        $task = new Tasks();

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
        require_once __DIR__ . '/../models/Goods.php';
        $goods = new Goods();

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
        require_once __DIR__ . '/../models/Inventory.php';
        $inventory = new Inventory();

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
        require_once __DIR__ . '/../models/User.php';
        $user = new User();
        // variables 
        $num = 1000;
        $name   = "Juanito Perez";

        // Logic for users view
        require __DIR__ . '/../views/users.php';

        echo "Hola desde ctlSidebar - metodo users";
    }
}