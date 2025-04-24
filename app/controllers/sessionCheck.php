<?php
/**
 * sessionCheck.php
 * 
 * Este archivo verifica si existe una sesión activa para el usuario.
 * Si no hay una sesión activa, redirige al usuario a la página de inicio de sesión.
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    $acceptsJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
    $isApiCall = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

    if ($acceptsJson || $isApiCall) {
        http_response_code(401); // Unauthorized
        echo json_encode([
            'success' => false,
            'message' => 'Debe iniciar sesión antes'
        ]);
    } else {
        header("Location: /login");
    }

    exit();
}