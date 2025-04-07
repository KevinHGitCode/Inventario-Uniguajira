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
        // Verifica si la sesión está iniciada
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }}

    /**
     * Muestra la vista principal (home).
     * Obtiene los datos del usuario con ID 1 y los pasa a la vista.
     *
     * @return void
     */
    public function home() {
        require_once __DIR__ . '/../models/Tasks.php';
        $task = new Tasks();
        $dataTasks = $task->getByUser($_SESSION['user_id']); // Get tasks for the logged-in user
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
        $dataGoods = $goods->getAll(); // Get all goods from the model

        // Logic for goods view
        require __DIR__ . '/../views/goods.php';
    }

    /**
     * Muestra la vista de inventario (inventary).
     *
     * @return void
     */
    public function inventary() {
        require_once __DIR__ . '/../models/Groups.php';
        $groups = new Groups();

        $dataGroups = $groups->getAllGroups(); // Get all groups from the model

        // Pass $dataGroups to the view
        require __DIR__ . '/../views/inventary.php';
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