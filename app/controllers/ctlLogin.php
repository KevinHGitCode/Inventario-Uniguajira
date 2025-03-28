<?php
session_start();
require_once '../models/User.php';
require_once '../config/config.php'; // Asegúrate de incluir la conexión a la base de datos

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // devuelve el json de la de prueba
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'data' => [
            'user_id' => 1,
            'username' => 'testuser'
        ]
    ]);
    exit;
}
