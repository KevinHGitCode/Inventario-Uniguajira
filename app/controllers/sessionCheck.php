<?php
/**
 * sessionCheck.php
 * 
 * Este archivo verifica si existe una sesión activa para el usuario.
 * Si no hay una sesión activa, redirige al usuario a la página de inicio de sesión.
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit();
}

