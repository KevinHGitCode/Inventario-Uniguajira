<?php

require_once __DIR__ . '/../models/User.php';

class ctlHome {
    public static function getUserNameById($id) {
        $user = new User();
        $userData = $user->getById($id);
        return $userData['nombre']; // Assuming 'nombre' is the column name for the user's name
    }
}

// Test the method
// echo ctlHome::getUserNameById(1);