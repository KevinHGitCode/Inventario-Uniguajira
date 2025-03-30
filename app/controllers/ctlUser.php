<?php
require_once __DIR__ . '/../models/User.php';

class ctlUser {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        echo "Login method called.";
    }

    public function register($nombre, $email, $contraseña, $rol) {
        echo "Register method called.";
    }

    public function profile($id) {
        echo "Profile method called.";
    }

    public function edit($id, $nombre, $email, $contraseña, $rol) {
        echo "Edit method called.";
    }
}
