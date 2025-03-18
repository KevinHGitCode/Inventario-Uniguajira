<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';

class ctlHome {
    public static function getUserNameById($id) {
        $database = new Database();
        $connection = $database->getConnection();
        $user = new User($connection);
        $userData = $user->getById($id);
        return $userData['nombre']; // Assuming 'nombre' is the column name for the user's name
    }
}

// Test the method
// echo ctlHome::getUserNameById(1);