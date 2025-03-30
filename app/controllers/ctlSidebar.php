<?php

require_once __DIR__ . '/../models/User.php';

class ctlSidebar {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function home() {
        $userData = $this->userModel->getById(1);
        $username = $userData['nombre'];
        // Pass $username to the view
        require __DIR__ . '/../views/home.php';
    }

    public function goods() {
        // Logic for goods view
        require __DIR__ . '/../views/goods.php';

        echo "Hola desde ctlSidebar - metodo goods";
    }

    public function inventary() {
        // Logic for inventary view
        require __DIR__ . '/../views/inventary.php';

        echo "Hola desde ctlSidebar - metodo inventory";
    }

    public function users() {
        // Logic for users view
        require __DIR__ . '/../views/users.php';

        echo "Hola desde ctlSidebar - metodo users";
    }
}