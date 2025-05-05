<?php
require_once __DIR__ . '/sessionCheck.php';

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
        
        // Obtener todas las tareas
        $allTasks = $task->getByUser($_SESSION['user_id']);
        
        // Filtrar en el controlador
        $dataTasks = [
            'pendientes' => array_filter($allTasks, function($t) {
                return $t['estado'] === 'por hacer';
            }),
            'completadas' => array_filter($allTasks, function($t) {
                return $t['estado'] === 'completado';
            })
        ];
        
        $username = $_SESSION['user_name'];
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
    public function inventory() {
        require_once __DIR__ . '/../models/Groups.php';
        $groups = new Groups();

        $dataGroups = $groups->getAllGroups(); // Get all groups from the model

        // Pass $dataGroups to the view
        require __DIR__ . '/../views/inventory/groups.php';
    }

    /**
     * Muestra la vista de reportes (reports).
     *
     * @return void
     */
    public function reports() {
        require_once __DIR__ . '/../models/Reports.php';
        $reports = new Reports();
        $dataReportFolders = $reports->getAllFolders(); // Get all groups from the model

        // Logic for reports view
        require __DIR__ . '/../views/reports/folders.php';
    }

    /**
     * Muestra la vista de usuarios (users).
     *
     * @return void
     */
    public function users() {
        require_once __DIR__ . '/../models/User.php';
        $user = new User();
      
       $dataUsers = $user->getAllUsers(); // Get all users from the model

        // Logic for users view
        require __DIR__ . '/../views/users.php';

       
    }
    
    /**
     * Muestra la vista de registros (records).
     * Obtiene los últimos registros desde el modelo y los pasa a la vista.
     *
     * @return void
     */
    public function record() {
        require_once __DIR__ . '/../models/Record.php';
        $record = new Record();
      
        $dataRecords = $record->getLastRecords(); // Obtener los últimos registros desde el modelo

        // Pasar los datos a la vista de registros
        require __DIR__ . '/../views/record.php';
    }
}